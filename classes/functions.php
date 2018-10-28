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
     * @return bool|string
     */
    public function checkEmail($value) {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $this->checkValue($value);
        }

        return false;
    }

    /**
     * @param $password
     * @param $passwordCheck
     * @return string
     */
    public function checkPassword($password, $passwordCheck) {
        if ($password === $passwordCheck && ($password !== "" && $passwordCheck !== "")) {
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

    /**
     * @param $firstname
     * @param $lastname
     * @param $email
     * @param $password
     * @param $birthday
     * @param $diet
     * @return bool
     */
    public function registerClient($firstname, $lastname, $email, $password, $birthday, $diet) {
        $conn = $this->getConn();
        $query = "INSERT INTO clients 
              (`first_name`, `last_name`, `email`, `password`, `date_of_birth`, `diet`, `create_date`, `last_online`, `permission`) 
              VALUES 
              ('" . $conn->real_escape_string($firstname) . "', '" . $conn->real_escape_string($lastname) . "', '" . $conn->real_escape_string($email) . "', '" . $conn->real_escape_string($password) . "', '" . $conn->real_escape_string($birthday) . "', '" . $conn->real_escape_string($diet) . "', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', '1');";

        if ($this->insertItem($query)) {
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @param string $password
     * @return bool|mysqli_result|string
     */
    public function checkAccount($email, $password = "") {
        $query = "SELECT * FROM clients
            WHERE email = '" . $this->conn->real_escape_string($email) . "'";

        if (!empty($password)) {
            $query .= " AND password = '" . $this->conn->real_escape_string(md5($password)) . "'";
        }

        $query .= ";";

        return $this->getItems($query);
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