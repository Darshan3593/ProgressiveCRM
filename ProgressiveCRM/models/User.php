<?php
// models/User.php

class User {
    private $conn;
    private $table_name = "users";

    // Added the ID property
    public $id; 
    public $username;
    public $fname;
    public $Lname;
    public $user_type;
    public $emailid;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if the username already exists
    public function usernameExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->username]);
        return $stmt->rowCount() > 0;
    }

    // Check if the email already exists
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE emailid = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->emailid]);
        return $stmt->rowCount() > 0;
    }

    public function register() {
        // Prevent duplicate usernames or emails
        if ($this->usernameExists() || $this->emailExists()) {
            return false; 
        }

        // Notice we do NOT insert the 'id' here, MySQL does it for us
        $query = "INSERT INTO " . $this->table_name . " (username, fname, Lname, user_type, emailid, password) 
                  VALUES (:username, :fname, :Lname, :user_type, :emailid, :password)";
        
        $stmt = $this->conn->prepare($query);

        $this->username  = htmlspecialchars(strip_tags($this->username));
        $this->fname     = htmlspecialchars(strip_tags($this->fname));
        $this->Lname     = htmlspecialchars(strip_tags($this->Lname));
        $this->user_type = htmlspecialchars(strip_tags($this->user_type));
        $this->emailid   = htmlspecialchars(strip_tags($this->emailid));
        
        $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":fname", $this->fname);
        $stmt->bindParam(":Lname", $this->Lname);
        $stmt->bindParam(":user_type", $this->user_type);
        $stmt->bindParam(":emailid", $this->emailid);
        $stmt->bindParam(":password", $hashed_password);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Add this to models/User.php inside the User class

    public function login() {
        // Fetch user data based on username
        $query = "SELECT id, username, fname, Lname, user_type, password 
                  FROM " . $this->table_name . " 
                  WHERE username = ? LIMIT 0,1";
                  
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->execute([$this->username]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify the hashed password
            if (password_verify($this->password, $row['password'])) {
                // Populate object properties for session creation
                $this->id = $row['id'];
                $this->fname = $row['fname'];
                $this->Lname = $row['Lname'];
                $this->user_type = $row['user_type'];
                return true;
            }
        }
        return false;
    }

    // --- ADD THIS TO models/User.php ---
    public function getAllUsers() {
        // Fetch all users except passwords, ordered by newest first
        $query = "SELECT id, username, fname, Lname, user_type, emailid, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
}
?>