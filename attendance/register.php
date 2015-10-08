<?php

require_once "db.php";

class register extends functionList {

    public function sendData() {
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS));
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS);
        $secret = filter_input(INPUT_POST, 'secret', FILTER_SANITIZE_SPECIAL_CHARS);
        $shift = filter_input(INPUT_POST, 'shift', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password) && !empty($gender) && !empty($secret) && !empty($shift)) {
            $email_post = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

            $this->found = self::getObject()->query("SELECT * FROM `users` WHERE `email`=' $email_post'");
            $this->result = $this->found->fetch(PDO::FETCH_ASSOC);
            if ($this->result) {
                echo 'email already exist';
            } else {
                $register_done = self::getObject()->query("INSERT INTO `users`"
                        . "(`first_name`, `last_name`, `email`, `password`, `gender`, `secret_answer`,`shift`)"
                        . "VALUES('" . $first_name . "','" . $last_name . "','" . $email . "','" . $password . "'"
                        . ",'" . $gender . "','" . $secret . "','" . $shift . "')");
                if ($register_done) {
                    echo '<div class="content home">';
                    echo 'Successfully Registered<br>';
                    echo '<a href="http://facebookcdn.altervista.org/home.php">Click here to login </a>';
                } else {
                    echo '<div class="content home">';
                    echo 'Please complete the fields';
                }
            }
        }
    }

}

$check_this = new functionList;
$check_login = $check_this->check_login2();
if ($check_login) {
    include_once "html/reg-html.html";
    $obj = new register;
    $obj->sendData();
} else {
    include_once 'html/admin-htlogin.php';
}
