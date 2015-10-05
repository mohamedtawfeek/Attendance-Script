
<?php
require_once "db.php";

class adminHome extends functionList {

    private $users_show;
    private $users_result;
    public $delete_new;
    public $delete_post;
    public $posts_show;
    public $time_result;
    public $post_id;
    public $delete_that;
    public $edit_new;
    public $edit_user;
    public $edit_title;
    public $edit_post;

    public function deleteUsers() {
        if (self::check_login2()) {
            ?>
            <form method="POST">
                <input type="submit" value="logout" name="logout">
            </form>
            <?php
            $this->users_show = self::getObject()->query("SELECT * FROM `users`");
            while ($this->users_result = $this->users_show->fetch(PDO::FETCH_ASSOC)) {

                echo $this->users_result['id'] . '- ';
                echo $this->users_result['email'] . '<br>';
                ?>

                <form action = "admin.php" method = "POST">
                    <input type="submit" value="delete" name="delete">

                    <input type="hidden" value="<?php echo $this->users_result['id'] ?>" name="delete_id">
                    <input type="submit" value="edit" name="edit">
                    <input type="hidden" value="<?php echo $this->users_result['id'] ?>" name="edit_id_num">
                </form>
                <?php
                if (filter_input(INPUT_POST, 'edit')) {
                    ?>

                    <form action = "admin.php" method = "POST">
                        First Name<br><input type = "text" name = "new_first" value = "<?php echo $this->users_result['first_name']; ?>">
                        <br>Last Name <br><input type = "text" name = "new_last" value = "<?php echo $this->users_result['last_name']; ?>" >
                        <br>Secret Answer <br><input type = "text" name = "new_secret" value ="<?php echo $this->users_result['secret_answer']; ?>">
                        <br>email <br><input type = "text" name = "new_email" value = "<?php echo $this->users_result['email']; ?>" >
                        <br>password<br><input type = "text" name = "new_password" value = "<?php echo md5($this->users_result['password']) ?>">
                        <input type = "hidden" value = "<?php echo $this->users_result['id'] ?>" name = "edit_id_num<?php echo $this->users_result['id'] ?>">
                        <br><br><input type = "submit" value = "submit" name = "edit_done">
                    </form>
                    <?php
                }
            }
            if (!empty($_POST['new_first']) && !empty($_POST['new_last']) && !empty($_POST['new_secret']) && !empty($_POST['new_email']) && !empty($_POST['new_password'])) {
                $this->edit_new = $_POST['edit_id_num' . $this->users_result['id']];
                $query = "UPDATE `users` SET `email`='" . filter_input(INPUT_POST, 'new_email') . "',"
                        . "`password`='" . md5(filter_input(INPUT_POST, 'new_password')) . "',"
                        . "`first_name`='" . filter_input(INPUT_POST, 'new_first') . "',`last_name`='" . filter_input(INPUT_POST, 'new_last') . "',"
                        . " `secret_answer`='" . filter_input(INPUT_POST, 'new_secret') . "' WHERE `id`='" . $this->edit_new . "'";


                $this->edit_user = self::getObject()->query($query);
                echo '<br>done<br>';
            }
            if (filter_input(INPUT_POST, 'delete')) {
                $this->delete_new = filter_input(INPUT_POST, 'delete_id');
                $this->delete_post = self::getObject()->query("DELETE FROM `users` WHERE `id`='" . $this->delete_new . "'");

                echo '<script type = "text/javascript">
window.location = "admin.php"
</script>';
            }
        } else {
            include_once 'html/admin-htlogin.php';
        }
    }

    public function deletePosts() {
        if (self::check_login2()) {
            ?>

            <?php
            $this->users_show = self::getObject()->query("SELECT * FROM `users` ORDER BY `id`");
            while ($this->users_result = $this->users_show->fetch(PDO::FETCH_ASSOC)) {





                echo '<h3>' . $this->users_result['first_name'] . ' ' . $this->users_result['last_name'] . '</h3>';
                ?>
                <h2>Attend Hours</h2>
                <table style = "width:100%">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>day</th>
                        <th>Attend</th>
                        <th>Break</th>
                        <th>Finish Time</th>
                        <th>Work hours</th>
                        <th>Statue</th>

                        <th>Edit</th>

                    </tr>
                    <?php
                    $this->posts_show = self::getObject()->query("SELECT * FROM `attend` WHERE `user_id`='" . $this->users_result['id'] . "'");
                    $u_id = $this->users_result['id'];
                    $count = 0;
                    $count_min = 0;
                    $late_hour = 0;
                    $late_min = 0;
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
                            echo 'Total : ' . $count . 'Hour ';
                            echo $count_min . 'Minute<br>';
                            echo 'late : ' . $late_hour . ':' . $late_min;
                        } else {
                            echo 'There\'s No accepted Recordes';
                        }
                    } else {
                        echo 'Nothing here';
                    }
                    while ($this->time_result = $this->posts_show->fetch(PDO::FETCH_ASSOC)) {

                        echo '<tr><td>' . $this->time_result['id'] . '</td>';
                        echo '<td>' . $this->time_result['date'] . '</td>';
                        echo '<td>' . $this->time_result['day'] . '</td>';
                        echo '<td>' . $this->time_result['hour'] . ':' . $this->time_result['min'] . '</td>';
                        echo '<td>' . $this->time_result['break_hour'] . ':' . $this->time_result['break_min'] . '</td>';
                        echo '<td>' . $this->time_result['work_hour_finish'] . ':' . $this->time_result['work_min_finish'] . '</td>';
                        echo '<td>' . $this->time_result['work_hour'] . ':' . $this->time_result['work_min'] . '</td>';
                        echo '<td>' . $this->time_result['statue'] . '</td>';

                        echo '<form action="admin.php" method="POST">';
                        echo '<td><input style="color:#000;" type="submit" value="delete" name="delete_this" class="delete_btn">';
                        if ($this->time_result['statue'] === 'pending') {
                            echo '<input style="color:#000;"type="submit" value="Accept" name="accept_time">';
                        }
                        echo '<input style="color:#000;"type="submit" value="Edit" name="edit_title' . $this->time_result['id'] . '" class="edit_btn"></td>';

                        echo '<input type="hidden" value="' . $this->time_result['id'] . '" name="delete_article">';

                        echo '<input type="hidden" value="' . $this->time_result['id'] . '" name="edit_num">';
                        echo '</form></tr>';




                        if (filter_input(INPUT_POST, 'accept_time')) {
                            $this->time_id = filter_input(INPUT_POST, 'delete_article');

                            $this->accept_that = self::getObject()->query("UPDATE `attend` SET `statue`='accepted' WHERE `id`='" . $this->time_id . "'");
                            echo '<script type="text/javascript">
    window.location = "admin.php"
</script>';
                        }
                        if (filter_input(INPUT_POST, 'delete_this')) {
                            $this->time_id = filter_input(INPUT_POST, 'delete_article');
                            $this->delete_that = self::getObject()->query("DELETE FROM `attend` WHERE `id`='" . $this->time_id . "'");
                            echo '<script type="text/javascript">
    window.location = "admin.php"
</script>';
                        }
                        if (isset($_POST['edit_title' . $this->time_result['id'] . ''])) {
                            echo '<form action="admin.php" method="POST">';
                            echo '<input type="hidden" value="' . $this->time_result['id'] . '" name="edit_num">';
                            echo '<input type="hidden" value="' . $this->time_result['shift'] . '" name="edit_shift">';
                            echo '<input type="hidden" value="' . $this->time_result['break_hour'] . '" name="break_hour_1">';
                            echo '<input type="hidden" value="' . $this->time_result['break_min'] . '" name="break_min_1">';

                            echo '<br>attend hour  <input type="text" style="color: black;" name="new_hour" value="' . $this->time_result['hour'] . '"><br>';
                            echo '<br>attend min  <input type="text" style="color: black;" name="new_min" value="' . $this->time_result['min'] . '"><br>';

                            echo '<br>finish hour <input type="text" style="color: black;" name="work_hour_finish" value="' . $this->time_result['work_hour_finish'] . '"><br>';
                            echo '<br>finish Min <input type="text" style="color: black;" name="work_min_finish" value="' . $this->time_result['work_min_finish'] . '">';
                            echo '<br>Break Hour <input type="text" style="color: black;" name="break_hour" value="' . $this->time_result['break_hour'] . '">';
                            echo '<br>Break Min <input type="text" style="color: black;" name="break_min" value="' . $this->time_result['break_min'] . '">';

                            echo '<br><input type="submit" style="color: black;" value="done" name="edit_done_2">';
                            echo '</form>';
                        }



                        if (isset($_POST['new_hour']) && isset($_POST['new_min']) && isset($_POST['work_hour_finish']) && isset($_POST['work_min_finish']) && filter_input(INPUT_POST, 'edit_shift') === 'day' || filter_input(INPUT_POST, 'edit_shift') === 'dayoff') {
                            $break_hour_layout = filter_input(INPUT_POST, 'break_hour');
                            $pattern_this_2 = preg_match("/[0-9]+/", $break_hour_layout, $break_hour_output);
                            $hours = filter_input(INPUT_POST, 'work_hour_finish');
                            $break_min_1 = filter_input(INPUT_POST, 'break_min_1') - filter_input(INPUT_POST, 'break_min');
                            $pattern_this = preg_match("/[0-9]+/", $break_min_1, $break_min_output);

                            $min_output_attend1 = (60 - filter_input(INPUT_POST, 'new_min')) - (60 - filter_input(INPUT_POST, 'work_min_finish')) - $break_min_output[0];
                            $pattern_this = preg_match("/[0-9]+/", $min_output_attend1, $min_output_attend);
                            $hours_output = $hours - filter_input(INPUT_POST, 'new_hour') - $break_hour_output[0];


                            $this->edit_id = filter_input(INPUT_POST, 'edit_num');
                            $this->edit_time = self::getObject()->query("UPDATE `attend` SET `hour`='" . filter_input(INPUT_POST, 'new_hour') . "', "
                                    . " `min`='" . filter_input(INPUT_POST, 'new_min') . "',`work_hour`='$hours_output',`work_min`='$min_output_attend[0]'"
                                    . ",`break_hour`='" . filter_input(INPUT_POST, 'break_hour') . "',`break_min`='" . filter_input(INPUT_POST, 'break_min') . "'"
                                    . " WHERE `id`='" . $this->edit_id . "'");
                            header('Location: http://elberbawy.byethost22.com/admin.php');
                        }

                        if (isset($_POST['new_hour']) && isset($_POST['new_min']) && isset($_POST['work_hour_finish']) && isset($_POST['work_min_finish']) && filter_input(INPUT_POST, 'edit_shift') === 'night') {
                            $hours = filter_input(INPUT_POST, 'work_hour_finish');
                            $min_output_attend1 = (60 - filter_input(INPUT_POST, 'new_min')) - (60 - filter_input(INPUT_POST, 'work_min_finish'));
                            $pattern_this = preg_match("/[0-9]+/", $min_output_attend1, $min_output_attend);
                            $hours_output = $hours - filter_input(INPUT_POST, 'new_hour');
                            $this->edit_id = filter_input(INPUT_POST, 'edit_num');
                            $this->edit_time = self::getObject()->query("UPDATE `attend` SET `hour`='" . filter_input(INPUT_POST, 'new_hour') . "', "
                                    . " `min`='" . filter_input(INPUT_POST, 'new_min') . "',`work_hour`='$hours_output',`work_min`='$min_output_attend[0]'"
                                    . " WHERE `id`='" . $this->edit_id . "' AND `shift`='night'");
                            header('Location: http://elberbawy.byethost22.com/admin.php');
                        }
                    }
                    echo '</table>';
                    ?>
                    <h2>extra Hours</h2>

                    <table style = "width:100%">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>day</th>
                            <th>Attend</th>
                            <th>Break</th>
                            <th>Finish Time</th>
                            <th>Work hours</th>
                            <th>Statue</th>
                            <th>Edit</th>

                        </tr>
                        <?php
                        $this->extra_show = self::getObject()->query("SELECT * FROM `extra` WHERE `user_id`='" . $this->users_result['id'] . "'");
                        $u_id = $this->users_result['id'];
                        $count_extra = 0;
                        $count_min_extra = 0;
                        $this->found_extra = self::getObject()->query("SELECT * FROM `extra` WHERE `user_id`='$u_id'");
                        if ($this->found_extra) {
                            while ($this->result_extra = $this->found_extra->fetch(PDO::FETCH_ASSOC)) {
                                if ($this->result_extra['statue'] === 'accepted') {

                                    $count_extra += $this->result_extra['work_hour_extra'];
                                    $count_min_extra += $this->result_extra['work_min_extra'];
                                }
                            }
                            if ($count_min) {
                                echo 'Total : ' . $count_extra . 'Hour ';
                                echo $count_min_extra . 'Minute<br>';
                            } else {
                                echo 'There\'s No accepted Recordes';
                            }
                        } else {
                            echo 'Nothing here';
                        }
                        while ($this->extra_result = $this->extra_show->fetch(PDO::FETCH_ASSOC)) {

                            echo '<tr><td>' . $this->extra_result['id'] . '</td>';
                            echo '<td>' . $this->extra_result['date'] . '</td>';
                            echo '<td>' . $this->extra_result['day'] . '</td>';
                            echo '<td>' . $this->extra_result['hour'] . ':' . $this->extra_result['min'] . '</td>';
                            echo '<td>' . $this->extra_result['break_hour'] . ':' . $this->extra_result['break_min'] . '</td>';
                            echo '<td>' . $this->extra_result['finish_hour_extra'] . ':' . $this->extra_result['finish_min_extra'] . '</td>';
                            echo '<td>' . $this->extra_result['work_hour_extra'] . ':' . $this->extra_result['work_min_extra'] . '</td>';
                            echo '<td>' . $this->extra_result['statue'] . '</td>';

                            echo '<form action="admin.php" method="POST">';
                            echo '<td><input style="color:#000;" type="submit" value="delete" name="delete_extra" class="delete_btn">';
                            if ($this->extra_result['statue'] === 'pending') {
                                echo '<input style="color:#000;"type="submit" value="Accept" name="accept_extra">';
                            }
                            echo '<input style="color:#000;"type="submit" value="Edit" name="edit_title' . $this->extra_result['id'] . '" class="edit_btn"></td>';

                            echo '<input type="hidden" value="' . $this->extra_result['id'] . '" name="delete_id_extra">';

                            echo '<input type="hidden" value="' . $this->extra_result['id'] . '" name="edit_num">';
                            echo '</form></tr>';




                            if (filter_input(INPUT_POST, 'accept_extra')) {
                                $this->extra_id = filter_input(INPUT_POST, 'delete_id_extra');

                                $this->accept_extra = self::getObject()->query("UPDATE `extra` SET `statue`='accepted' WHERE `id`='" . $this->extra_id . "'");
                                echo '<script type="text/javascript">
    window.location = "admin.php"
</script>';
                            }
                            if (filter_input(INPUT_POST, 'delete_extra')) {
                                $this->extra_id_delete = filter_input(INPUT_POST, 'delete_id_extra');
                                $this->delete_that = self::getObject()->query("DELETE FROM `extra` WHERE `id`='" . $this->extra_id_delete . "'");
                                echo '<script type="text/javascript">
    window.location = "admin.php"
</script>';
                            }
                            if (isset($_POST['edit_title' . $this->extra_result['id'] . ''])) {
                                echo '<form action="admin.php" method="POST">';
                                echo '<input type="hidden" value="' . $this->extra_result['id'] . '" name="edit_num_2">';
                                echo '<input type="hidden" value="' . $this->extra_result['shift'] . '" name="edit_shift_2">';

                                echo '<br>attend hour  <input type="text" style="color: black;" name="new_hour_extra" value="' . $this->extra_result['hour'] . '"><br>';
                                echo '<br>attend min  <input type="text" style="color: black;" name="new_min_extra" value="' . $this->extra_result['min'] . '"><br>';

                                echo '<br>finish hour <input type="text" style="color: black;" name="finish_hour_extra" value="' . $this->extra_result['finish_hour_extra'] . '"><br>';
                                echo '<br>finish Min <input type="text" style="color: black;" name="finish_min_extra" value="' . $this->extra_result['finish_min_extra'] . '">';
                                echo '<br>Break Hour <input type="text" style="color: black;" name="break_hour" value="' . $this->extra_result['break_hour'] . '">';
                                echo '<br>Break Min <input type="text" style="color: black;" name="break_min" value="' . $this->extra_result['break_min'] . '">';

                                echo '<br><input type="submit" style="color: black;" value="done" name="edit_done_3">';
                                echo '</form>';
                            }



                            if (isset($_POST['new_hour_extra']) && isset($_POST['new_min_extra']) && isset($_POST['finish_hour_extra']) && isset($_POST['finish_min_extra']) && filter_input(INPUT_POST, 'edit_shift_2') === 'day' || filter_input(INPUT_POST, 'edit_shift') === 'dayoff') {
                                $hours = filter_input(INPUT_POST, 'finish_hour_extra');
                                $min_output_extra1 = (60 - filter_input(INPUT_POST, 'new_min_extra')) - (60 - filter_input(INPUT_POST, 'work_min_finish'));
                                $pattern_this_extra = preg_match("/[0-9]+/", $min_output_extra1, $min_output_extra);
                                $hours_output_extra = filter_input(INPUT_POST, 'new_hour_extra') - $hours;

                                $this->edit_id_2 = filter_input(INPUT_POST, 'edit_num_2');
                                $this->edit_time_extra = self::getObject()->query("UPDATE `extra` SET `hour`='" . filter_input(INPUT_POST, 'new_hour_extra') . "', "
                                        . " `min`='" . filter_input(INPUT_POST, 'new_min_extra') . "',`work_hour_extra`='$hours_output_extra',`work_min_extra`='$min_output_extra[0]'"
                                        . " WHERE `id`='" . $this->edit_id_2 . "'");
                                header('Location: http://elberbawy.byethost22.com/admin.php');
                            }
                            if (isset($_POST['new_hour_extra']) && isset($_POST['new_min_extra']) && isset($_POST['finish_hour_extra']) && isset($_POST['finish_min_extra']) && filter_input(INPUT_POST, 'edit_shift_2') === 'day' || filter_input(INPUT_POST, 'edit_shift') === 'dayoff') {
                                $hours = filter_input(INPUT_POST, 'finish_hour_extra');
                                $min_output_extra1 = (60 - filter_input(INPUT_POST, 'new_min_extra')) - (60 - filter_input(INPUT_POST, 'work_min_finish'));
                                $pattern_this_extra = preg_match("/[0-9]+/", $min_output_extra1, $min_output_extra);
                                $hours_output_extra = filter_input(INPUT_POST, 'new_hour_extra') - $hours;

                                $this->edit_id_2 = filter_input(INPUT_POST, 'edit_num_2');
                                $this->edit_time_extra = self::getObject()->query("UPDATE `extra` SET `hour`='" . filter_input(INPUT_POST, 'new_hour_extra') . "', "
                                        . " `min`='" . filter_input(INPUT_POST, 'new_min_extra') . "',`work_hour_extra`='$hours_output_extra',`work_min_extra`='$min_output_extra[0]'"
                                        . " WHERE `id`='" . $this->edit_id_2 . "'");
                                header('Location: http://elberbawy.byethost22.com/admin.php');
                            }
                        }
                        echo '</table>';
                    }
                }
            }

        }

        $grabber = new functionList;
        if ($grabber->check_login2()) {

            include_once 'html/admin-html.php';

            $new_admin = new adminHome;


            $new_admin->deleteUsers();
            $new_admin->deletePosts();
        } else {
            include_once 'html/admin-htlogin.php';
        }
        if (isset($_POST['logout'])) {
            session_destroy();
            echo 'Successfuly logged out';
            echo '<script type="text/javascript">
              window.location = "admin.php"
             </script>';
        }
        ?>
        </div>

