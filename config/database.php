<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "16062004Du!321";// đổi mật khẩu nếu cần
    private $dbname = "dental_clinic";
    public $conn;

    public function connect() {
        $this->conn = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname
        );

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>