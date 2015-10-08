<?php
include_once "html/forget-html.html";
require_once "db.php";

class forget extends functionList {

public function forget_password() {

$this->email_forget = self::getObject()->query("SELECT * FROM `users` WHERE `email`='" . filter_input(INPUT_POST, 'email') . "' "
. "AND `secret_answer`='" . filter_input(INPUT_POST, 'secret') . "'");
if ($this->found_out = $this->email_forget->fetch(PDO::FETCH_ASSOC)) {
?>
<form method="POST" onsubmit="accept_delete()">
    <input type="hidden" value="<?php $this->found_out['id'] ?>" name="user_id">
    password <input type="text" placeholder="new password" name="new_pass">
    Confirm password <input type="text" placeholder="confirm new password" name="new_pass_conf">
    <input type="submit" value="done" name="Set">
</form>
<?php
$new_pass = filter_input(POST, 'new_pass', FILTER_SANITIZE_SPECIAL_CHARS);
$user_id = filter_input(POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS);

$new_pass_conf = filter_input(POST, 'new_pass', FILTER_SANITIZE_SPECIAL_CHARS);
if ($new_pass === $new_pass_conf) {
self::getObject()->query("UPDATE `users` SET `password`='$new_pass' WHERE `id`='$user_id'");
            }else{
                echo 'passwords not the same';
            }
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
