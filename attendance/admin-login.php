<?php

require_once 'db.php';

class adminPanel extends functionList {
    public  $logged_2;
    private $user;
    private $password_2;
    private $found;
    private $result;

    public function loginInfo2() {
        $this->logged_2 = FALSE;
        $this->user = filter_input(INPUT_POST, 'user');
        $this->password_2 = filter_input(INPUT_POST, 'password_2', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->found = self::getObject()->query("SELECT * FROM `admin` WHERE `user`='" . filter_input(INPUT_POST, 'user') . "' AND "
                . "`password`='" . $this->password_2 . "'");
        $this->result = $this->found->fetch(PDO::FETCH_ASSOC);
        
    }

    public function checkLogin2() {
        $this->loginInfo2();
        if ($this->result) {
                session_start();
                $_SESSION["user"] = filter_input(INPUT_POST, 'user');
                $_SESSION["password_2"] = filter_input(INPUT_POST, 'password_2', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $this->logged_2 = true;
        }
        
    }



$check = new adminPanel;

$check->checkLogin2();
if ($check->logged_2 == false) {
    include ('html/admin-htlogin.php');
}

if ($check->logged_2 == TRUE) {
    echo 'logged in';
    echo '<script type="text/javascript">
              window.location = "admin.php"
             </script>';
} else {
    echo 'worng email or password';
}
