<?php

class functionList {

    private $email;
    private $user;
    private $user_info_2;
    private $password_2;
    private $info_user;
    private $info_passwor_2;
    private $email_cookie;
    private $user_info;
    private $info_email;
    private $info_password;
    private $password;
    public $myDb;
    static $object;
    private $DBSettings = array(
        'server' => 'localhost',
        'user' => 'root',
        'password' => '',
        'dbName' => 'training'
    );

    static function getObject() {

        if (!functionList::$object) {
            functionList::$object = new functionList();
        }
        return functionList::$object;
    }

    public function __construct() {
        $this->myDb = new PDO("mysql:dbname={$this->DBSettings['dbName']};"
                . "host=" . $this->DBSettings['server'], $this->DBSettings['user'], $this->DBSettings['password']);
    }

    public function query($query) {
        return $this->myDb->query($query);
    }

    public function num_rows($st) {

        return $st->rowCount();
    }

    public function startSession() {

        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function check_login() {

        $this->startSession();
        $this->email_cookie = filter_input(INPUT_COOKIE, 'email', FILTER_VALIDATE_EMAIL);
        $this->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($this->email_cookie) {
            $this->info_email = filter_input(INPUT_COOKIE, 'email', FILTER_VALIDATE_EMAIL);
            $this->info_password = filter_input(INPUT_COOKIE, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
        } 



        if (isset($this->info_email) && isset($this->info_password)) {
            $this->email = $this->info_email;
            $this->password = $this->info_password;


            return $this->user_info = $this->grab_user_account($this->email, $this->password);
        }

        return false;
    }

    public function grab_user_account($email, $password) {


        return self::getObject()->query("SELECT * FROM `users` WHERE `email`='$email' "
                        . "AND `password`='$password'");
    }

    public function startSession2() {

        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function check_login2() {
        $this->startSession2();

        $this->user = filter_input(INPUT_POST, 'user');

        $this->password_2 = filter_input(INPUT_POST, 'password_2', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($this->user) {
            $_SESSION['user'] = $this->info_user = filter_input(INPUT_POST, 'user');
            $_SESSION['password_2'] = $this->info_password_2 = filter_input(INPUT_POST, 'password_2', FILTER_SANITIZE_SPECIAL_CHARS);
        } else if (isset($_SESSION['user'])) {
            $this->info_user = $_SESSION['user'];
            $this->info_password_2 = $_SESSION['password_2'];
        }



        if (isset($this->info_user) && isset($this->info_password_2)) {
            $this->user = $this->info_user;
            $this->password_2 = md5($this->info_password_2);


            return $this->user_info_2 = $this->grab_user_account2($this->user, $this->password_2);
        }

        return false;
    }

    public function grab_user_account2($user, $password_2) {


        return self::getObject()->query("SELECT * FROM `admin` WHERE `user`='$user' "
                        . "AND `password`='$password_2' ");
    }

}
