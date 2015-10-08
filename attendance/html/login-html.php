<!DOCTYPE html>
<html>

    <head>

        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="html/css/style.css">
        <link rel="stylesheet" type="text/css" href="html/css/normalize.css">
        <link rel="stylesheet" href="html/css/font-awesome.min.css">

    </head>

    <center>
        <div class='content home'>
            <h1>Zwaar Attendance</h1>
            <form action="login.php" method="POST">
                <p>Email</p><input id="one" type="text" name="email"><br>
                <p>Password</p> <input id="one" type="password" name="password"><br>

                <input type="submit" value="Login"><br><br>

            </form>
            <div id="links">
                <a href="forget.php">Forget Password?</a>
            </div>
        </div>
    </center>
    <script src="html/js/jquery-1.11.3.min.js"></script>
    <script src="html/js/index.js"></script> 
</body>
</html>