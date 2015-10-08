<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Zwaar Attendance</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="html/css/normalize.css">
        <link rel="stylesheet" href="html/css/style.css">
    </head>

    <body>
        <?php
        if (isset($_COOKIE['email'])) {
            $email_cookie_show = $_COOKIE['email'];
        }
        ?>
        <div class='button'>
            <i class="fa fa-bars"></i>
        </div>
        <div class='menu'>
            <nav>
                <ul>
                    <li>
                        <?php
                        if (isset($_COOKIE['email'])) {
                            echo '<a href="#">' . $email_cookie_show;
                        }
                        ?>

                    </li>
                    <li>
                        <a href='home.php'>Home</a>
                    </li>
                    <li>
                    </li>
                    <li>
                        <form method="GET">
                            <input type="submit" value="logout" name="logout">
                        </form>
                    </li>


                </ul>
            </nav>
        </div>
        <div class='content home'>

            <h1>Zwaar Attendance</h1>

            <form method="POST" onsubmit="accept_delete()">
                Break Hour <select name="break_hours">
                    <option value="-1">No Break</option>

                    <option value="0">1</option>
                    <option value="1">2</option>
                    <option value="2">3</option>
                </select>
                Break Min <select name="break_min">
                    <option value="00">00</option>
                    <option value="30">30</option>
                </select>
                <input type="submit" value="done" name="Set">
            </form>
            <script src="html/js/jquery-1.11.3.min.js"></script>

            <script src="html/js/index.js"></script>

       

    </body>
</html>