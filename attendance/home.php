<?php
require_once "db.php";

class homePage extends functionList {

    public function loginHome() {
        if (self::check_login()) {
            include_once 'html/home-html.php';
            if (isset($_COOKIE['email'])) {
                echo 'welcome ' . $_COOKIE['email'];
            }
        } else {
            include_once 'html/login-html.php';
        }
        echo '<br>';

        if (filter_input(INPUT_GET, 'logout') == 'logout') {
            session_destroy();

            setcookie("email", false, -1);
            setcookie("password", FALSE, -1);
            echo 'Successfully logged out';
            echo '<script type="text/javascript">
              window.location = "home.php"
              </script>';
        }
    }

    public function logininfo() {
        if (isset($_COOKIE['password']) && isset($_COOKIE['email'])) {

            $this->found = self::getObject()->query("SELECT * FROM `users` WHERE `email`='" . $_COOKIE['email'] . "' AND "
                    . "`password`='" . $_COOKIE['password'] . "'");
            $this->result = $this->found->fetch(PDO::FETCH_ASSOC);
            return $this->result;
        }
    }

    public function attend() {
        $login = $this->logininfo();

        $u_id = $login['id'];
        $this->found_attend = self::getObject()->query("SELECT * FROM `attend` WHERE `user_id`='$u_id'");
        while ($this->result_attend = $this->found_attend->fetch(PDO::FETCH_ASSOC)) {

            echo '<tr><td>' . $this->result_attend['date'] . '</td>';
            echo '<td>' . $this->result_attend['day'] . '</td>';
            echo '<td>' . $this->result_attend['hour'] . ':' . $this->result_attend['min'] . '</td>';
            echo '<td>' . $this->result_attend['break_hour'] . ':' . $this->result_attend['break_min'] . '</td>';
            echo '<td>' . $this->result_attend['work_hour_finish'] . ':' . $this->result_attend['work_min_finish'] . $this->result_attend['pm_am_finish'] . '</td></tr>';
        }
    }

    public function attend_count() {
        $login = $this->logininfo();

        $u_id = $login['id'];
        $late_hour = 0;
        $late_min = 0;
        $count = 0;
        $count_min = 0;
        $this->found_attend = self::getObject()->query("SELECT * FROM `attend` WHERE `user_id`='$u_id'");
        if ($this->found_attend) {
            while ($this->result_attend = $this->found_attend->fetch(PDO::FETCH_ASSOC)) {
                if ($this->result_attend['statue'] === 'accepted') {

                    $count += $this->result_attend['work_hour'];
                    $count_min += $this->result_attend['work_min'];
                    $late_time = $this->result_attend['hour'] - 10;
                    $late_hour += $late_time;
                    $late_min +=$this->result_attend['min'];
                }
            }
            if ($count_min) {
                echo 'Total Attendance : ' . $count . 'Hour ';
                echo $count_min . 'Minute<br>';
                echo 'late : ' . $late_hour . ':' . $late_min;
            } else {
                echo 'There\'s No accepted Recordes';
            }
        } else {
            echo 'Nothing here';
        }
    }

    public function extra() {
        $login = $this->logininfo();

        $u_id = $login['id'];

        $this->found_extra = self::getObject()->query("SELECT * FROM `extra` WHERE `user_id`='$u_id'");

        while ($this->result_extra = $this->found_extra->fetch(PDO::FETCH_ASSOC)) {

            echo '<tr><td>' . $this->result_extra['date'] . '</td>';
            echo '<td>' . $this->result_extra['day'] . '</td>';
            echo '<td>' . $this->result_extra['hour'] . ':' . $this->result_extra['min'] . '</td>';
            echo '<td>' . $this->result_extra['break_hour'] . ':' . $this->result_extra['break_min'] . '</td>';
            echo '<td>' . $this->result_extra['finish_hour_extra'] . ':' . $this->result_extra['finish_min_extra'] . '</td>';
            echo '<td>' . $this->result_extra['statue'] . '</td></tr>';
        }
    }

    public function extra_count() {
        $login = $this->logininfo();

        $u_id = $login['id'];
        $count = 0;
        $count_min = 0;
        $this->found_extra = self::getObject()->query("SELECT * FROM `extra` WHERE `user_id`='$u_id'");
        if ($this->found_extra) {
            while ($this->result_extra = $this->found_extra->fetch(PDO::FETCH_ASSOC)) {
                if ($this->result_extra['statue'] === 'accepted') {
                    $count += $this->result_extra['work_hour_extra'];
                    $count_min += $this->result_extra['work_min_extra'];
                }
            }if ($count) {
                echo 'Total extra : ' . $count . 'Hour ';
                echo $count_min . 'Minute<br>';
            } else {
                echo 'No Accepted Extra Time';
            }
        } else {
            echo 'Nothing Here';
        }
    }

    public function loginTime() {
        $login = $this->logininfo();

        $u_id = $login['id'];
        $date = date("Y-m-d");

        $this->found2 = self::getObject()->query("SELECT * FROM `extra` WHERE `user_id`='" . $u_id . "' AND "
                . "`date`='" . $date . "'");
        $this->result2 = $this->found2->fetch(PDO::FETCH_ASSOC);
        return $this->result2;
    }

    public function loginTime2() {
        $login = $this->logininfo();

        $u_id = $login['id'];
        $date = date("Y-m-d");
        $this->found3 = self::getObject()->query("SELECT * FROM `attend` WHERE `user_id`='" . $u_id . "' AND "
                . "`date`='" . $date . "'");
        $this->result3 = $this->found3->fetch(PDO::FETCH_ASSOC);
        return $this->result3;
    }

    public function login_done() {
        $login_time = $this->loginTime2();
        $login_time2 = $this->loginTime();

        $login = $this->logininfo();
        if (self::check_login()) {
            if (filter_input(INPUT_GET, 'logout') == 'logout') {
                date_default_timezone_set("Egypt");

                $hours_attend = $login_time['hour'];
                $min_attend = $login_time['min'];
                $hours_extra = $login_time2['hour'];
                $min_extra = $login_time2['min'];
                $logout_time = date('H');
                $logout_hour = date('h');

                $logout_min = date('i');
                $hours_output_attend = $logout_time - $hours_attend;
                $hours_output_extra = $logout_time - $hours_extra;

                $date = date("Y-m-d");
                $min_output_attend1 = (60 - $min_attend) - (60 - $logout_min);
                $pattern_this = preg_match("/[0-9]+/", $min_output_attend1, $min_output_attend);
                $min_output_extra1 = (60 - $min_extra) - (60 - $logout_min);
                $pattern_this1 = preg_match("/[0-9]+/", $min_output_extra1, $min_output_extra);
                $day = date('l');
                $time = date('H');
                $user_id = $login['id'];

                if ($login['shift'] === 'day' || $login['shift'] === 'dayoff') {
                    if ($time === '10' || $time === '11' || $time === '12' || $time === '13' || $time === '14' || $time === '15' || $time === '16' || $time === '17' || $time === '18') {
                        self::getObject()->query("UPDATE attend SET work_hour= '$hours_output_attend',work_min= '$min_output_attend[0]',work_hour_finish='$logout_hour',work_min_finish='$logout_min' "
                                . "WHERE user_id='$user_id' AND `date`='$date'");
                    }
                }if ($login['shift'] === 'day' || $login['shift'] === 'dayoff') {
                    if ($time === '19' || $time === '20' || $time === '21' || $time === '22' || $time === '23') {
                        self::getObject()->query("UPDATE extra SET work_hour_extra= '$hours_output_extra',work_min_extra= '$min_output_extra[0]',finish_hour_extra='$logout_hour',finish_min_extra='$logout_min' "
                                . "WHERE user_id='$user_id' AND `date`='$date'");
                    }
                }if ($login['shift'] === 'day' || $login['shift'] === 'dayoff' || $login['shift'] === 'night') {
                    if ($time === '00' || $time === '01' || $time === '02' || $time === '03' || $time === '04' || $time === '05' || $time === '06' || $time === '07' || $time === '08' || $time === '09') {
                        self::getObject()->query("UPDATE extra SET work_hour_extra= '$hours_output_extra',work_min_extra= '$min_output_extra[0]',finish_hour_extra='$logout_hour',finish_min_extra='$logout_min' "
                                . "WHERE user_id='$user_id' AND `date`='$date'");
                    }
                } if ($login['shift'] === 'night') {
                    self::getObject()->query("UPDATE `attend` SET `work_hour`='$hours_output_attend',`work_min`='$min_output_attend[0]',work_hour_finish='$logout_hour',work_min_finish='$logout_min' "
                            . "WHERE `user_id`='$user_id' AND `date`='$date'");
                }
            }

            if (filter_input(INPUT_POST, 'break_hours') || filter_input(INPUT_POST, 'break_min')) {
                $hours_attend = $login_time['work_hour'];
                $min_attend = $login_time['work_min'];
                $hours_extra = $login_time2['work_hour_extra'];
                $min_extra = $login_time2['work_min_extra'];
                $time = date('H');
                if (filter_input(INPUT_POST, 'break_hours') == -1) {
                    $break_hour_attend = $hours_attend + 1;
                    $break_time_db = "no break";
                } elseif (filter_input(INPUT_POST, 'break_hours') == 0) {
                    $break_time_db = "2hours";
                } else {
                    $break_hour_attend = $hours_attend - filter_input(INPUT_POST, 'break_hours');
                    $break_time_db = "3hours";
                }
                $break_min_attend = $min_attend - filter_input(INPUT_POST, 'break_min');
                $break_hour_extra = $hours_extra - filter_input(INPUT_POST, 'break_hours');
                $break_min_extra = $min_extra - filter_input(INPUT_POST, 'break_min');
                $user_id = $login['id'];
                $date = date("Y-m-d");
                if ($login_time['break_hour'] === "2hour" || $login_time['break_hour'] === "2hours" || $login_time['break_hour'] === "3hours") {
                    echo 'you took a break before';
                } else {
                    if ($time === '14' || $time === '15' || $time === '16') {
                        self::getObject()->query("UPDATE `attend` SET `work_hour`='$break_hour_attend',`work_min`='$break_min_attend',break_hour='$break_time_db',break_min='" . filter_input(INPUT_POST, 'break_min') . "' "
                                . "WHERE `user_id`='$user_id' AND `date`='$date'");
                    } elseif ($break_time_db === "no break" && $login_time['break_hour'] === "1") {
                        self::getObject()->query("UPDATE `attend` SET `work_hour`='$break_hour_attend',`work_min`='$break_min_attend',break_hour='$break_time_db',break_min='" . filter_input(INPUT_POST, 'break_min') . "' "
                                . "WHERE `user_id`='$user_id' AND `date`='$date'");
                    } else {
                        echo 'sorry you cannot take break at this hour break from 2~5';
                    }
                }
                /* if ($time === '14' || $time === '15' || $time === '16' || $time === '17') {
                  self::getObject()->query("UPDATE `attend` SET `work_hour`='$break_hour_attend',`work_min`='$break_min_attend',break_hour='" . filter_input(INPUT_POST, 'break_hours') . "',break_min='" . filter_input(INPUT_POST, 'break_min') . "' "
                  . "WHERE `user_id`='$user_id' AND `date`='$date'");
                  } */
                /* if ($time === '19' || $time === '20' || $time === '21' || $time === '22' || $time === '23' || $time === '00' || $time === '01' || $time === '02' || $time === '03' || $time === '04' || $time === '05' || $time === '06' || $time === '07' || $time === '08' || $time === '09') {
                  self::getObject()->query("UPDATE `extra` SET `work_hour_extra`='$break_hour_extra',`work_min_extra`='$break_min_extra',break_hour='" . filter_input(INPUT_POST, 'break_hour') . "',break_min='" . filter_input(INPUT_POST, 'break_min') . "' "
                  . "WHERE `user_id`='$user_id' AND `date`='$date'");
                  } */
            }
        }
    }

    public function search() {
        if (self::check_login()) {
            if (isset($_REQUEST['search'])) {


                $this->search_name = $_REQUEST['search'];

                if (!empty($this->search_name)) {
                    echo '<hr>Search Result<br>';
                    $this->search_show = self::getObject()->query("SELECT * FROM `posts` WHERE `article`  LIKE '%" . $this->search_name . "%'"
                            . " OR `title` LIKE '%" . $this->search_name . "%' OR `id` = '" . $this->search_name . "' ");



                    while ($this->search_result_2 = $this->search_show->fetch(PDO::FETCH_ASSOC)) {
                        echo $this->search_result_2['id'] . '- ';
                        echo $this->search_result_2['date'] . '  ';
                        echo $this->search_result_2['publisher'] . '<br>';
                        echo $this->search_result_2['title'] . '<br>';
                        echo $this->search_result_2['article'] . '<br><hr>';
                    }
                    echo 'hi';
                }
            }
        }
    }

}

$grabber = new functionList;
$home = new homePage();
$home->loginHome();

if ($grabber->check_login()) {
    $home->login_done();
    ?>
    <h2>Normal Attend</h2>

    <table style="width:100%">
        <tr>
            <th>Date</th>
            <th>day</th>		
            <th>Attend</th>
            <th>Break</th>
            <th>Finish Time</th>
        </tr>
        <?php
        $home->attend();
        echo '</table>';
        $home->attend_count();
        ?>
        <h2>Extra Attend</h2>

        <table style = "width:100%">
            <tr>
                <th>Date</th>
                <th>day</th>
                <th>Attend</th>
                <th>Break</th>
                <th>Finish Time</th>
                <th>Status</th>

            </tr>
            <?php
            $home->extra();
            echo '</table>';
            $home->extra_count();
        }
        ?>


        