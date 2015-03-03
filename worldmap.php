<?php 
include("linkedinapi.php");
?>

<html>
<?php 
$fin=date("H:i:s"); 
$tempstotal =gmdate("i:s",strtotime($fin)-strtotime($debutapi));
echo "Temps total : ".$tempstotal."</br>";
?>

    <head>
        <title>Worldmap</title>
        <link rel="stylesheet" type="text/css" href="worldmap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js"></script>
        <script type="application/javascript" src="awesomechart.js"></script>
        <script>

            
            var cercle={};

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
            
            function drawMyChart(){
            if(!!document.createElement('canvas').getContext){ //check that the canvas
                                                               // element is supported
                var mychart = new AwesomeChart('canvas1');
                mychart.title = "Top 5 villes";
                mychart.data = [max1.nb , max2.nb , max3.nb , max4.nb, max5.nb];
                mychart.labels = [max1.town, max2.town, max3.town, max4.town, max5.town];
                mychart.chartType = 'pie';
                mychart.draw();
                var mychart = new AwesomeChart('canvas2');
                mychart.title = "Par Zones";
                mychart.data = [Africa.nb , Americas.nb , Asia.nb , Europe.nb , Oceania.nb];
                mychart.labels = [Africa.zone , Americas.zone , Asia.zone , Europe.zone , Oceania.zone];
                mychart.chartType = 'pie';
                mychart.draw();
                }
            }
          
            window.onload = drawMyChart;
          

        </script>
        <script>
        var tableau= {};
            <?php

                foreach ($town as $key => $value) {
                    if(!empty($value["Number"])){
                         $temp=str_replace(" ", "", $value["city"]);

                    echo "tableau['".$temp."'] = {
                        center: new google.maps.LatLng(".$value["lat"].", ".$value["lng"]."),
                        Number: ".$value["Number"].",
                        Location : '".$value["city"]."'
                    };\n";
                    }
                        

                }

            ?>
            console.log(tableau);

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
        
        <div id="map-canvas"></div>
        <?php 
         ?>

        <canvas id="canvas1" width="300" height="300">
            Your web-browser does not support the HTML 5 canvas element.
        </canvas>
        <canvas id="canvas2" width="300" height="300">
            Your web-browser does not support the HTML 5 canvas element.
        </canvas>


        

    </body>
</html>




