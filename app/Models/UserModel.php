<?php
// app/Models/UserModel.php

require_once __DIR__ . '/../../config/database.php';

class UserModel {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Authenticate user by username and password
     * @param string $username
     * @param string $password
     * @return array|false User data if authenticated, false otherwise
     */
    public function authenticate($username, $password) {
        $query = "SELECT UserID, Username, Password_hash, Name, Role, Phone, Age 
                  FROM " . $this->table_name . " 
                  WHERE Username = :username 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if (password_verify($password, $row['Password_hash'])) {
                // Remove password hash from returned data
                unset($row['Password_hash']);
                return $row;
            }
        }

        return false;
    }

    /**
     * Get user by ID
     * @param int $userId
     * @return array|false
     */
    public function getUserById($userId) {
        $query = "SELECT UserID, Username, Name, NationalID, Phone, Age, Role, Created_At 
                  FROM " . $this->table_name . " 
                  WHERE UserID = :user_id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    /**
     * Get patient details by user ID
     * @param int $userId
     * @return array|false
     */
    public function getPatientByUserId($userId) {
        $query = "SELECT p.PatientID, p.UserID, p.Email, p.Address, p.InsuranceID,
                         u.Username, u.Name, u.NationalID, u.Phone, u.Age
                  FROM patient p
                  INNER JOIN " . $this->table_name . " u ON p.UserID = u.UserID
                  WHERE p.UserID = :user_id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    /**
     * Get employee details by user ID
     * @param int $userId
     * @return array|false
     */
    public function getEmployeeByUserId($userId) {
        $query = "SELECT e.EmployeeID, e.UserID, e.Speciality, e.Academic_title,
                         u.Username, u.Name, u.NationalID, u.Phone, u.Age, u.Role
                  FROM employee e
                  INNER JOIN " . $this->table_name . " u ON e.UserID = u.UserID
                  WHERE e.UserID = :user_id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    /**
     * Create new patient user
     * @param array $userData
     * @return int|false User ID if successful, false otherwise
     */
    public function createPatient($userData) {
        try {
            $this->conn->beginTransaction();

            // Insert into users table
            $query = "INSERT INTO " . $this->table_name . "
                      (Username, Password_hash, Name, NationalID, Phone, Age, Role)
                      VALUES (:username, :password_hash, :name, :national_id, :phone, :age, 'Patient')";

            $stmt = $this->conn->prepare($query);
            $password_hash = password_hash($userData['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':password_hash', $password_hash);
            $stmt->bindParam(':name', $userData['name']);
            $stmt->bindParam(':national_id', $userData['national_id']);
            $stmt->bindParam(':phone', $userData['phone']);
            $stmt->bindParam(':age', $userData['age']);

            $stmt->execute();
            $userId = $this->conn->lastInsertId();

            // Insert into patient table
            $query = "INSERT INTO patient (UserID, Email, Address, InsuranceID)
                      VALUES (:user_id, :email, :address, :insurance_id)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':address', $userData['address'] ?? null);
            $stmt->bindParam(':insurance_id', $userData['insurance_id'] ?? null);

            $stmt->execute();

            $this->conn->commit();
            return $userId;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * Check if username exists
     * @param string $username
     * @return bool
     */
    public function usernameExists($username) {
        $query = "SELECT UserID FROM " . $this->table_name . " WHERE Username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
