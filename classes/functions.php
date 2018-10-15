<?php
/**
 * Created by PhpStorm.
 * User: joeriroijenga
 * Date: 10-10-18
 * Time: 15:36
 */

class functions extends database
{
    /**
     * functions constructor.
     * @param string $dbname
     * @param string $username
     */
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    /**
     * @param $value
     * @return string
     */
    public function checkValue($value) {
        $value = stripslashes($value);
        $value = str_replace("/", "", $value);
        $value = htmlspecialchars($value);
        $value = trim($value);

        return $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function checkEmail($value) {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $this->checkValue($value);
        }
        return "";
    }

    /**
     * @param $password
     * @param $passwordCheck
     * @return string
     */
    public function checkPassword($password, $passwordCheck) {
        if ($password === $passwordCheck) {
            return md5($password);
        }

        return false;
    }

    /**
     * @param $value
     * @return bool]
     */
    public function checkDiet($value) {
        if (is_numeric($value) && !empty($value)) {
            return $value;
        }

        return false;
    }

    /**
     * @param $date
     * @return bool|false|string
     */
    public function checkDate($date) {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return date("Y-m-d", strtotime($date));
        }

        return False;
    }

    public function registerClient($firstname, $lastname, $email, $password, $birthday, $diet) {
        $a = "INSERT INTO clients 
              (`first_name`, `last_name`, `email`, `password`, `birthday`, `diet`) 
              VALUES 
              ('" . $this->conn-mysqli_real_escape_string($firstname) . "', '" . $this->conn-mysqli_real_escape_string($lastname) . "', '" . $this->conn-mysqli_real_escape_string($email) . "', ''" . $this->conn-mysqli_real_escape_string($password) . ", '" . $this->conn-mysqli_real_escape_string($birthday) . "', '" . $this->conn-mysqli_real_escape_string($diet) . "');";

        if ($this->insertItem("INSERT INTO clients 
                                      (`first_name`, `last_name`, `email`, `password`, `birthday`, `diet`) 
                                      VALUES 
                                      ('" . $this->conn-mysqli_real_escape_string($firstname) . "', '" . $this->conn-mysqli_real_escape_string($lastname) . "', '" . $this->conn-mysqli_real_escape_string($email) . "', ''" . $this->conn-mysqli_real_escape_string($password) . ", '" . $this->conn-mysqli_real_escape_string($birthday) . "', '" . $this->conn-mysqli_real_escape_string($diet) . "');")) {
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @param $password
     * @return bool|mysqli_result
     */
    public function checkAccount($email, $password) {
        $result = $this->getItems("
            SELECT * FROM clients
            WHERE email = '" . $this->conn->real_escape_string($email) . "' AND password = '" . $this->conn->real_escape_string(md5($password)) . "';
        ");

        return $result;
    }

    /**
     * @param $id
     * @return bool
     */
    public function updateDateTime($id) {
        $dateTime = date("Y-m-d H:i:s");

        if ($this->updateItem("UPDATE clients SET last_online = '" . $dateTime . "' WHERE client_id = " . $id . ";")) {
            return true;
        }

        return false;
    }

    /**
     * @param $account
     * @return bool
     */
    public function setLogin($account) {
        $account = $account->fetch_assoc();

        try {
            $_SESSION["id"] = $account["client_id"];
            $_SESSION["name"] = $account["first_name"] . " " . $account["last_name"];
            $_SESSION["email"] = $account["email"];
            $_SESSION["timeout"] = time() + 3600;

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return bool|mysqli_result
     */
    public function getDiets() {
        return $this->getItems("SELECT * FROM diets;");
    }

    /**
     *
     */
    public function __destruct() {
        parent::__destruct();
    }
}