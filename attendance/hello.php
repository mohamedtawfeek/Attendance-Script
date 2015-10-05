<?php

require_once "db.php";
error_reporting(E_ALL);
ini_set('display_errors', true);

class register extends functionList {

    private $first_name;
    private $last_name;
    private $email;
    private $password;
    private $gender;
    private $secret;
    private $found;
    private $result;
    private $shift;

    public function checkData() {
        $this->first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $this->password = md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS));
        $this->gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->secret = filter_input(INPUT_POST, 'secret', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->shift = filter_input(INPUT_POST, 'shift', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function ifSet() {
        $this->checkData();
        return !empty($this->first_name && $this->last_name && $this->email && $this->password && $this->gender && $this->secret);
    }

    public function sendData() {
        $this->ifSet();
        if ($this->ifSet()) {
            $this->found = self::getObject()->query("SELECT * FROM `users` WHERE `email`='" . filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) . "'");
            $this->result = $this->found->fetch(PDO::FETCH_ASSOC);
            if ($this->result) {
                echo 'email already exist';
            } else {
                $this->checkData();

                self::getObject()->query("INSERT INTO `users`"
                        . "(`first_name`, `last_name`, `email`, `password`, `gender`, `secret_answer`,`shift`)"
                        . "VALUES('" . $this->first_name . "','" . $this->last_name . "','" . $this->email . "','" . $this->password . "'"
                        . ",'" . $this->gender . "','" . $this->secret . "','" . $this->shift . "')");


                return;
            }
        }
    }

}

$check_this = new functionList;
if ($check_this->check_login2()) {
    include_once 'html/reg-html.php';

    $obj = new register;
    $obj->ifSet();
    $st = $obj->checkData();
    $new = $obj->sendData();
} else {
    include_once 'html/admin-htlogin.php';
}
