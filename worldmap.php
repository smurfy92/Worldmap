<?php 
include_once("Engine/linkedinapi.php");
$fin=date("H:i:s"); 
$tempstotal =gmdate("i:s",strtotime($fin)-strtotime($debutapi));
echo "Temps total : ".$tempstotal."</br>";
?>
test
<html>
    <head>
        <title>Worldmap</title>
        <link rel="stylesheet" type="text/css" href="css/worldmap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script src="js/Chart.js"></script>
        <script type="text/javascript">

        //Geochart

            google.load("visualization", "1", {packages:["geochart"]});
            google.setOnLoadCallback(drawRegionsMap);

            function drawRegionsMap() {

                var data = google.visualization.arrayToDataTable([
                    ['Country', 'Popularity'],
                    <?php 
                        foreach ($pays as $key => $value) {
                            echo "['".$key."',".$value."],";
                        }
                    ?>
                    
                    
                    ]);

                var options = {};

                var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

                chart.draw(data, options);
            }

          
        //On initialise les données pour google map

        var tableau= {};
            <?php

                foreach ($town as $key => $value) {
                    if(!empty($value["Number"])){
                         $temp=str_replace(" ", "", $value["city"]);

                    echo 'tableau["'.$temp.'"] = {
                        center: new google.maps.LatLng('.$value['lat'].', '.$value['lng'].'),
                        Number: '.$value['Number'].',
                        Location : "'.$value['city'].'"
                    };';
                    }
                        

                }

            ?>


            //google map

            var cityCircle;

            function initialize() {
                
                var mapOptions = {
                    center: new google.maps.LatLng(0, 0),
                    zoom: 2,
                    mapTypeId: google.maps.MapTypeId.TERRAIN
                }
                var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

                for (var city in tableau) {

                    var cityOptions = {
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35,
                    map: map,
                    center: tableau[city].center,
                    radius: Math.sqrt(tableau[city].Number) * 10000,

                    };
                // Add the circle for this city to the map.
                cityCircle = new google.maps.Circle(cityOptions);
                }
            }
            google.maps.event.addDomListener(window, 'load', initialize);


            
            console.log(tableau);
        </script>   
    </head>
    <body>
        <div class="block">
            <a href="disconnect.php">Log out</a>
        </div>
        <div class="blockuser">
            <a class="linkuser" href=<?php echo $url?>><h1><?php echo $username ?></h1></a>            
            <a class="linkuser" href=<?php echo $url?>><h4><?php echo $userapi->headline ?></h4></a>
        </div>
        <div id="regions_div" style="width: 900px; height: 500px; margin: auto;"></div>
        
        <div id="map-canvas"></div>

        <div class="canvas1">

            <div class="canvas1title">Top 5 villes</div>

            <canvas id="myChart1" height="300">
                Your web-browser does not support the HTML 5 canvas element.
            </canvas>

        </div>

        <div class="canvas2">

            <div class="canvas2title">Par Zones</div>

            <canvas id="myChart2" height="300">
                Your web-browser does not support the HTML 5 canvas element.
            </canvas>
        </div>
        <script>

        //tableau pour les camemberts

            <?php 
            
            foreach ($zones as $key => $value) {
                echo "var ".$key." = {zone:'".$key."',nb:".$value."};\n";
            }
            echo "var max1 = {nb:".$max1.",town:'".$town1."'};\n";
            echo "var max2 = {nb:".$max2.",town:'".$towndeux."'};\n";
            echo "var max3 = {nb:".$max3.",town:'".$town3."'};\n";
            echo "var max4 = {nb:".$max4.",town:'".$town4."'};\n";
            echo "var max5 = {nb:".$max5.",town:'".$town5."'};\n";
                    
            ?>

            var ctx1 = document.getElementById("myChart1").getContext("2d");
            // For a pie chart
            var ctx2 = document.getElementById("myChart2").getContext("2d");
            ctx1.fillStyle = "blue";
            ctx1.font = "bold 16px Arial";
            ctx1.fillText("Zibri", 0, 0);
            

            //camembert pour les villes

            var data1 = [
                {
                    value: max1.nb,
                    color:"#F7464A",
                    highlight: "#FF5A5E",
                    label: max1.town
                },
                {
                    value: max2.nb,
                    color: "#46BFBD",
                    highlight: "#5AD3D1",
                    label: max2.town
                },
                {
                    value: max3.nb,
                    color: "#FDB45C",
                    highlight: "#FFC870",
                    label: max3.town
                },
                {
                    value: max4.nb,
                    color: "#FDB45C",
                    highlight: "#FFC870",
                    label: max4.town
                },
                {
                    value: max5.nb,
                    color: "#FDB45C",
                    highlight: "#FFC870",
                    label: max5.town
                }
            ]


            // camembert pour les zones


            var data2 = [
                {
                    value: Europe.nb,
                    color:"#F7464A",
                    highlight: "#FF5A5E",
                    label: Europe.zone
                },
                {
                    value: Americas.nb,
                    color: "#46BFBD",
                    highlight: "#5AD3D1",
                    label: Americas.zone
                },
                {
                    value: Africa.nb,
                    color: "#FDB45C",
                    highlight: "#FFC870",
                    label: Africa.zone
                },
                {
                    value: Asia.nb,
                    color: "#FDB45C",
                    highlight: "#FFC870",
                    label: Asia.zone
                },
                {
                    value: Oceania.nb,
                    color: "#FDB45C",
                    highlight: "#FFC870",
                    label: Oceania.zone
                }
            ]
            var options = [
                {
                    legendTemplate : "Top 5 Villes"
                }
            ]
            var myPieChart1 = new Chart(ctx1).Pie(data1,options);
            var myPieChart2 = new Chart(ctx2).Pie(data2);

        </script>

    </body>
</html>




