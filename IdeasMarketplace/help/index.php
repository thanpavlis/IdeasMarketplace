<?php
include '../functions.php';
?>
<html>
    <head>
        <title>Βοήθεια</title>
        <?php styles(); ?>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
        <style>
            td.elipsis {
                max-width: 100px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        </style>
        <style>
            #map-canvas {
                height: 600px;
                width: 1000px;
                margin: 0px;
                padding: 0px
            }
            .star-rating{
                display: inline;
            }
            img.hot_rev{
                height:200px;
                width:300px;
            }
            .gallery{
                height:80px;
                width:130px;
            }
        </style>
        <script>
            var samos = new google.maps.LatLng(37.79605103692387, 26.70501708984375);
            var marker;
            var map;
            function initialize() {
                var mapOptions = {
                    zoom: 15,
                    center: samos};
                map = new google.maps.Map(document.getElementById('map-canvas'),
                        mapOptions);
                marker = new google.maps.Marker({
                    map: map,
                    draggable: false,
                    animation: google.maps.Animation.DROP,
                    position: samos,
                    title: "ΚΕΝΤΡΙΚΑ"
                });
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
    </head>
    <body>
        <?php show_header(); ?>
        <div class="container" style="width: 1300px;">
            <div class="row">
                <div class="box">
                    <center><h2>ΠΛΗΡΟΦΟΡΙΕΣ ΕΠΙΚΟΙΝΩΝΙΑΣ</h2></center>
                    <br>
                    <center><h3><b>E-mail:</b> admin@admin.com</h3></center>
                    <br>
                    <center><h3>Τηλέφωνο: 1234567890</h3></center>
                    <br>
                    <center><h3>Διεύθυνση Κεντρικών: Καρλόβασι Σάμου, Τ.Κ. 83200</h3></center>
                    <br>
                    <center><h3>GOOGLE MAP(θέση κεντρικών)</h3></center>
                    <br>
                    <div id="map" class="form-group">
                        <center><div id="map-canvas"></div></center>
                    </div>
                </div>
            </div>
        </div>
        <?php show_footer(); ?>
    </body>
</html>