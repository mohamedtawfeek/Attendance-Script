<?php

require_once "db.php";

class loginPage extends functionList {

    public $logged;
    private $email;
    private $password;
    private $found;
    private $result;
    public $attend;

    public function loginInfo() {
        $this->logged = FALSE;
        $this->email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $this->password = md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS));
        $this->found = self::getObject()->query("SELECT * FROM `users` WHERE `email`='" . filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) . "' AND "
                . "`password`='" . md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)) . "'");
        $this->result = $this->found->fetch(PDO::FETCH_ASSOC);
        return $this->result;
    }

    public function checkLogin() {
        $this->loginInfo();
        $time = date('H');
        $pm_am = date('a');

        if ($this->loginInfo()) {
            $day = date('l');
            if ($day === 'Saturday' || $day === 'Friday') {
                self::getObject()->query("UPDATE `users` SET `shift`='dayoff'");
            } elseif ($day === 'Sunday' || $day === 'Monday' || $day === 'Tuesday') {
                self::getObject()->query("UPDATE `users` SET `shift`='day'");
                self::getObject()->query("UPDATE `users` SET `shift`='night' WHERE `email`='omar.mohamed@gmail.com'");
            } elseif ($day === 'Wednesday' || $day === 'Thursday') {
                self::getObject()->query("UPDATE `users` SET `shift`='day'");

                self::getObject()->query("UPDATE `users` SET `shift`='night' WHERE `email`='mohamed.abdo@gmail.com'");
            }
            if ($this->loginInfo()['shift'] === 'day') {
                if ($time === '10' || $time === '11' || $time === '12' || $time === '13' || $time === '14' || $time === '15' || $time === '16' || $time === '17') {
                    date_default_timezone_set("Egypt");

                    $hours = 18;
                    $min = 60;
                    $hours_output = $hours - (date('H')) - 1;
                    $hours_cookie = $hours_output * 3600;
                    $min_output = $min - (date('i'));
                    $min_cookie = $min_output * 60;
                    $exp = time() + $hours_cookie + $min_cookie;
                    $user_id = $this->loginInfo()['id'];
                    $date = date("Y-m-d");
                    $hours_db = date('H');
                    $hours_attend = date('h');

                    $min_db = date('i');
                    setcookie("email", filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL), $exp);
                    setcookie("password", md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)), $exp);
                    self::getObject()->query("INSERT INTO `attend`(`user_id`,`date`,`hour`,`min`,`work_hour`,`work_min`,`day`,`shift`,`pm_am`,`work_hour_finish`,`work_min_finish`,`pm_am_finish`,`break_hour`,`break_min`,`statue`) "
                            . "VALUES('$user_id','$date','$hours_db','$min_db','$hours_output','$min_output','$day','day','$pm_am','7','00','pm','1','00','accepted')");
                } elseif ($time === '18') {
                    date_default_timezone_set("Egypt");

                    $hours = 18;
                    $min = 60;
                    $hours_output = $hours - (date('H'));
                    $hours_cookie = $hours_output * 3600;
                    $min_output = $min - (date('i'));
                    $min_cookie = $min_output * 60;
                    $exp = time() + $hours_cookie + $min_cookie;
                    $user_id = $this->loginInfo()['id'];
                    $date = date("Y-m-d");
                    $hours_db = date('H');
                    $hours_attend = date('h');

                    $min_db = date('i');
                    setcookie("email", filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL), $exp);
                    setcookie("password", md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)), $exp);
                    self::getObject()->query("INSERT INTO `attend`(`user_id`,`date`,`hour`,`min`,`work_hour`,`work_min`,`day`,`shift`,`pm_am`,`work_hour_finish`,`work_min_finish`,`pm_am_finish`,`break_hour`,`break_min`,`statue`) "
                            . "VALUES('$user_id','$date','$hours_db','$min_db','$hours_output','$min_output','$day','day','$pm_am','18','60','pm','0','00','accepted')");
                }
            }if ($this->loginInfo()['shift'] === 'day' || $this->loginInfo()['shift'] === 'dayoff') {
                if ($time === '19' || $time === '20' || $time === '21' || $time === '22' || $time === '23') {
                    date_default_timezone_set("Egypt");

                    $hours = 23;
                    $min = 60;
                    $hours_output = $hours - (date('H'));
                    $hours_cookie = $hours_output * 3600;
                    $min_output = $min - (date('i'));
                    $min_cookie = $min_output * 60;
                    $exp = time() + $hours_cookie + $min_cookie;
                    $user_id = $this->loginInfo()['id'];
                    $date = date("Y-m-d");
                    $hours_db = date('H');
                    $hours_attend = date('h');

                    $min_db = date('i');
                    setcookie("email", filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL), $exp);
                    setcookie("password", md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)), $exp);
                    self::getObject()->query("INSERT INTO `extra`(`user_id`,`date`,`hour`,`min`,`work_hour_extra`,`work_min_extra`,`day`,`statue`,`shift`,`pm_am`,`finish_hour_extra`,`finish_min_extra`,`pm_am_finish`,`break_hour`,`break_min`,`statue`) "
                            . "VALUES('$user_id','$date','$hours_db','$min_db','$hours_output','$min_output','$day','pending','day','pm','11','60','pm','0','00','pending')");
                }
            }if ($this->loginInfo()['shift'] === 'day' || $this->loginInfo()['shift'] === 'dayoff' || $this->loginInfo()['shift'] === 'night') {
                if ($time === '00' || $time === '01' || $time === '02' || $time === '03' || $time === '04' || $time === '05' || $time === '06' || $time === '07' || $time === '08' || $time === '09') {
                    date_default_timezone_set("Egypt");

                    $hours = 9;
                    $min = 60;
                    $hours_output = $hours - (date('H'));
                    $hours_cookie = $hours_output * 3600;
                    $min_output = $min - (date('i'));
                    $min_cookie = $min_output * 60;
                    $exp = time() + $hours_cookie + $min_cookie;
                    $user_id = $this->loginInfo()['id'];
                    $date = date("Y-m-d");
                    $hours_db = date('H');
                    $hours_attend = date('h');

                    $min_db = date('i');
                    setcookie("email", filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL), $exp);
                    setcookie("password", md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)), $exp);
                    self::getObject()->query("INSERT INTO `extra`(`user_id`,`date`,`hour`,`min`,`work_hour_extra`,`work_min_extra`,`day`,`statue`,`shift`,`pm_am`,`finish_hour_extra`,`finish_min_extra`,`pm_am_finish`,`break_hour`,`break_min`,`statue`) "
                            . "VALUES('$user_id','$date','$hours_db','$min_db','$hours_output','$min_output','$day','pending','day','$pm_am','9','60','$pm_am','0','00','pending')");
                }
            } if ($this->loginInfo()['shift'] === 'night') {
                date_default_timezone_set("Egypt");
                $hours = 21;
                $min = 60;
                $hours_output = $hours - (date('H'));
                $hours_cookie = $hours_output * 3600;
                $min_output = $min - (date('i'));
                $min_cookie = $min_output * 60;
                $exp = time() + $hours_cookie + $min_cookie;
                $user_id = $this->loginInfo()['id'];
                $date = date("Y-m-d");
                $hours_db = date('H');

                $min_db = date('i');
                setcookie("email", filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL), $exp);
                setcookie("password", md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)), $exp);
                self::getObject()->query("INSERT INTO `attend`(`user_id`,`date`,`hour`,`min`,`work_hour`,`work_min`,`day`,`shift`,`pm_am`,`work_hour_finish`,`work_min_finish`,`pm_am_finish`,`break_hour`,`break_min`,`statue`) "
                        . "VALUES('$user_id','$date','$hours_db','$min_db','$hours_output','$min_output','$day','night','$pm_am','11','60','pm','1','00','accepted')");
            }
            if ($this->loginInfo()['shift'] === 'dayoff') {
                if ($time === '10' || $time === '11' || $time === '12' || $time === '13' || $time === '14' || $time === '15' || $time === '16' || $time === '17') {
                    date_default_timezone_set("Egypt");

                    $hours = 18;
                    $min = 60;
                    $hours_output = $hours - (date('H')) - 1;
                    $hours_cookie = $hours_output * 3600;
                    $min_output = $min - (date('i'));
                    $min_cookie = $min_output * 60;
                    $exp = time() + $hours_cookie + $min_cookie;
                    $user_id = $this->loginInfo()['id'];
                    $date = date("Y-m-d");
                    $hours_db = date('H');
                    $hours_attend = date('h');

                    $min_db = date('i');
                    setcookie("email", filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL), $exp);
                    setcookie("password", md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS)), $exp);
                    self::getObject()->query("INSERT INTO `attend`(`user_id`,`date``,`hour`,`min`,`work_hour`,`work_min`,`day`,`shift`,`pm_am`,`work_hour_finish`,`work_min_finish`,`pm_am_finish`,`break_hour`,`break_min`,`statue`) "
                            . "VALUES('$user_id','$date','$hours_db','$min_db','$hours_output','$min_output','$day','day','$pm_am','7','00','pm','0','00','pending')");
                }
            }
            $this->logged = true;
        }
    }

}

$check = new loginPage;
$check->checkLogin();
if ($check->logged == false) {

    include ('html/login-html.php');
}

if ($check->logged == TRUE) {
    echo 'logged in';
    echo '<script type="text/javascript">
              window.location = "home.php"
             </script>';
} else {
    echo 'worng email or password';
}
