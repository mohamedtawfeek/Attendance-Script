<?php
include_once "html/forget-html.html";
require_once "db.php";
class forget extends functionList {

    public function forget_password() {

        $this->email_forget = self::getObject()->query("SELECT * FROM `users` WHERE `email`='" . filter_input(INPUT_POST, 'email') . "' "
                . "AND `secret_answer`='" . filter_input(INPUT_POST, 'secret') . "'");
        if ($this->found_out = $this->email_forget->fetch(PDO::FETCH_ASSOC)) {
            echo 'Your Password Is : ' . $this->found_out['password'];
        } else {
            echo 'wrong email or secret answer';
        }
    }

}

$forget = new forget;
$email_post = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password_post = filter_input(INPUT_POST, 'secret', FILTER_SANITIZE_SPECIAL_CHARS);
if (!empty($email_post) && !empty($password_post)) {
    $forget->forget_password();
}
