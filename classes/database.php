<?php
/**
 * Created by PhpStorm.
 * User: joeriroijenga
 * Date: 10-10-18
 * Time: 15:32
 */

class database
{
    /**
     * @var mysqli
     */
    protected $conn;

    /**
     * database constructor.
     * @param string $dbname
     * @param string $username
     * @param string $password
     * @param string $servername
     */
    protected function __construct($dbname = "", $username = "recipeworld", $password = "root", $servername = "localhost") {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error)
            die("Connection failed: " . $this->conn->connect_error);
    }

    /**
     * @param $query
     * @return bool|mysqli_result
     */
    protected function getItems($query) {
        return $this->conn->query($query);
    }

    /**
     * @param $query
     * @return bool|mysqli_result
     */
    protected function updateItem($query) {
        return $this->conn->query($query);
    }

    /**
     * @param $query
     * @return bool|mysqli_result
     */
    protected function insertItem($query) {
        return $this->conn->query($query);
    }

    /**
     * @param $query
     * @return bool|mysqli_result
     */
    protected function removeItem($query) {
        return $this->conn->query($query);
    }

    /**
     * @return mysqli
     */
    protected function getConn() {
        return $this->conn;
    }
    /**
     *
     */
    protected function __destruct() {
        $this->conn->close();
    }
}