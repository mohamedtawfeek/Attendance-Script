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
                <input id="lat" type="hidden" name="lat">
                <input id="long" type="hidden" name="long">
                <input type="submit" value="Login"><br><br>

            </form>
            <div id="links">
                <a href="forget.php">Forget Password?</a>
            </div>
        </div>
    </center>
    <script src="html/js/jquery-1.11.3.min.js"></script>
    <script src="html/js/index.js"></script> 
    <script>
            // Note: This example requires that you consent to location sharing when
            // prompted by your browser. If you see the error "The Geolocation service
            // failed.", it means you probably did not give permission for the browser to
            // locate you.

            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -34.397, lng: 150.644},
                    zoom: 6
                });
                var infoWindow = new google.maps.InfoWindow({map: map});

                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        infoWindow.setPosition(pos);
                        infoWindow.setContent('Location found.');
                        map.setCenter(pos);
                        document.getElementById('lat').innerHTML= position.coords.latitude;
                        document.getElementById('long').innerHTML= position.coords.longitude;

                    }, function () {
                        handleLocationError(true, infoWindow, map.getCenter());
                    });
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            }

            function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                infoWindow.setPosition(pos);
                infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
            }

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoVbHfNkzxMZ-SHfqT2C_XQ72qC-VdyDI&signed_in=true&callback=initMap"
                async defer>
        </script>

</body>
</html>