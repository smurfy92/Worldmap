<?php
// Change these
define('API_KEY',      '77291yw2hequ2v' );
define('API_SECRET',   '3YFqCY7jNDZicre2' );
define('REDIRECT_URI', 'http://' . $_SERVER['SERVER_NAME'] .':8888'. $_SERVER['SCRIPT_NAME']);
define('SCOPE',        'r_fullprofile r_emailaddress rw_nus r_network r_contactinfo r_basicprofile');


// You'll probably use a database
session_name('linkedin');
session_start();
include("connecteur.php");
include("country.php");

$debutapi =date("H:i:s");
$debut =date("H:i:s");

// OAuth 2 Control Flow
if (isset($_GET['error'])) {
    // LinkedIn returned an error
    print $_GET['error'] . ': ' . $_GET['error_description'];
    exit;
} elseif (isset($_GET['code'])) {
    // User authorized your application
    if ($_SESSION['state'] == $_GET['state']) {
        // Get token so you can make API calls
            if(!isset($_SESSION["access_token"])){
                getAccessToken();
            }         
    } else {
        // CSRF attack? Or did you mix up your states?
        exit;
    }
} else { 
    if ((empty($_SESSION['expires_at'])) || (time() > $_SESSION['expires_at'])) {
        // Token has expired, clear the state
        $_SESSION = array();
    }
    if (empty($_SESSION['access_token'])) {
        // Start authorization process
        getAuthorizationCode();
    }
}

function getAuthorizationCode() {
    $params = array('response_type' => 'code',
                    'client_id' => API_KEY,
                    'scope' => SCOPE,
                    'state' => uniqid('', true), // unique long string
                    'redirect_uri' => REDIRECT_URI,
              );

    // Authentication request
    $url = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params);

    // Needed to identify request when it returns to us
    $_SESSION['state'] = $params['state'];

    // Redirect user to authenticate
    header("Location: $url");
    exit;
}

function getAccessToken() {
    $params = array('grant_type' => 'authorization_code',
                    'client_id' => API_KEY,
                    'client_secret' => API_SECRET,
                    'code' => $_GET['code'],
                    'redirect_uri' => REDIRECT_URI,
              );

    // Access Token request
    $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);

    // Tell streams to make a POST request
    $context = stream_context_create(
                    array('http' => 
                        array('method' => 'POST',
                        )
                    )
                );

    // Retrieve access token information
    $response = file_get_contents($url, false, $context);

    // Native PHP object, please
    $token = json_decode($response);

    // Store access token and expiration time
    $_SESSION['access_token'] = $token->access_token; // guard this! 
    $_SESSION['expires_in']   = $token->expires_in; // relative time (in seconds)
    $_SESSION['expires_at']   = time() + $_SESSION['expires_in']; // absolute time

    return true;
}

function fetch($method, $resource, $body = '') {
    $params = array(
        'format' => 'json',
        'oauth2_access_token' => $_SESSION['access_token']
              );

    // Need to use HTTPS
    //$url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
    $url = $resource . '?' . http_build_query($params);
    // Tell streams to make a (GET, POST, PUT, or DELETE) request
    $context = stream_context_create(
                    array('http' => 
                        array('method' => $method,                                
                        )
                    )
                );
    // Hocus Pocus
    $response = file_get_contents($url, false, $context);

    // Native PHP object, please
    return json_decode($response);
}
function NoAccentTag($str)
{
    $str = htmlentities($str);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}




// We get users and user from linkedin api

$users = fetch('GET', 'https://api.linkedin.com/v1/people/~/connections:(location)');
$userapi = fetch('GET', 'https://api.linkedin.com/v1/people/~');
$username = $userapi->firstName.' '.$userapi->lastName;
$url = $userapi->siteStandardProfileRequest->url;

$connections = $users->values;

$finapi=date("H:i:s");




//We get town and country from database



$finaltab=[];
$tableau=[];
$town=[];
$town2=[];
$countgeo=0;
$towntotal=0;
$town2total=0;

$sql1="SELECT * FROM town";
$req1=mysqli_query($link,$sql1);


while($data=mysqli_fetch_assoc($req1)){

   $town[$data["city"]]=$data;

}

$sql2="SELECT * FROM town2";
$req2=mysqli_query($link,$sql2);


while($data2=mysqli_fetch_assoc($req2)){

   $town2[$data2["country"]]=$data2;

}





// now creating array with geocode calls and database insertion
$count=0;
foreach ($connections as $key => $value) {


    if(isset($connections[$key]->location)){

        $name= $connections[$key]->location->name;

        if($name != "Other"){

            $name=str_replace("Area", "", $name);
            $name=str_replace("area", "", $name);
            $name=str_replace("Lesser", "", $name);
            $name=str_replace("Greater", "", $name);
            $name=str_replace("/", "", $name);
            $name=str_replace("Metro", "", $name);
            $name = NoAccentTag($name);
            $name = preg_replace('/\(.*\)/U', '', $name);
            if(array_key_exists($name,$town)){
                if(array_key_exists("Number", $town[$name])){
                    $town[$name]["Number"]+=1;
                }else{
                    $town[$name]["Number"]=1;
                }    
            } 
            elseif(array_key_exists($name,$town2)){
                if(array_key_exists("Number",$town2[$name])){
                    $town2[$name]["Number"]+=1;
                }else{
                    $town2[$name]["Number"]=1;
                } 
            }
            else{


                $countgeo+=1;
                $temp=str_replace(" ", "+", $name);
                
                $results=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$temp."");
                $results=json_decode($results);
                if(!isset($results->results[0]->partial_match)){
                    if(isset($results->error_message)){
                        var_dump($results);
                    }
                    
                    if(is_object($results)){
                        $lng=$results->results[0]->geometry->location->lng;
                        $lat=$results->results[0]->geometry->location->lat;
                        $formatted_address=$results->results[0]->formatted_address;
                        $indicatif=$results->results[0]->address_components;
                        foreach ($indicatif as $key => $value) {
                            if($value->types[0] === "country")
                            $indtemp = $value->short_name;
                        }
                        $zone=$countryList[$indtemp];

                        if(!empty($lat) and !empty($lng)){

                            $virgule=strpos($name, ",");

                            if(!empty($virgule)){

                                $town[$name]=array(
                                    "city"=>$name,
                                    "Number"=>1,
                                    "lat"=>$lat,
                                    "lng"=>$lng,
                                    "zone"=>$zone
                                );
                                $sql="INSERT INTO town VALUES('','".$name."','".$indtemp."','".$zone."','".$lng."','".$lat."')";
                                $req=mysqli_query($link,$sql);

                            }else{

                                $virgule=strpos($name, ",");

                                if(!empty($virgule)){

                                    $town[$name]=array(
                                        "city"=>$name,
                                        "Number"=>1,
                                        "lat"=>$lat,
                                        "lng"=>$lng,
                                        "zone"=>$zone
                                    );
                                    $sql="INSERT INTO town VALUES('','".$name."','".$indtemp."','".$zone."','".$lng."','".$lat."')";
                                    $req=mysqli_query($link,$sql);

                                }else{

                                    $virgule2=strpos($formatted_address, ",");

                                    if(!empty($virgule2)){

                                        $town[$name]=array(
                                        "city"=>$name,
                                        "Number"=>1,
                                        "lat"=>$lat,
                                        "lng"=>$lng,
                                        "zone"=>$zone
                                        );
                                        $sql="INSERT INTO town VALUES('','".$name."','".$indtemp."','".$zone."','".$lng."','".$lat."')";
                                        $req=mysqli_query($link,$sql);

                                    }else{

                                        $town[$name]=array(
                                            "city"=>$name,
                                            "Number"=>1,
                                            "lat"=>$lat,
                                            "lng"=>$lng,
                                            "zone"=>$zone
                                        );
                                        $sql2="INSERT INTO town2 VALUES('','".$name."','".$zone."','".$lng."','".$lat."')";
                                        $req2=mysqli_query($link,$sql2);

                                    }
                                }
                            }                    
                        }
                    }
                }           
            } 
        }             
    }
}
$count=0;
$max1=0;
$max2=0;
$max3=0;
$max4=0;
$max5=0;
$town1="";
$towndeux="";
$town3="";
$town4="";
$town5="";
$zones=array(
"Africa"=>0,
"Americas"=>0,
"Asia"=>0,
"Europe"=>0,
"Oceania"=>0

);
$zonestotal=0;


foreach ($town as $key => $value) {

    if(!empty($value["Number"])){

        $zones[$value["zone"]]+=$value["Number"];
        $zonestotal+=$value["Number"];


        $virgule=strpos($key, ",");

        if(!empty($virgule)){

            $array=explode(",",$key);
            $key=$array[0];
        }

        //$count+=$value["Number"];

        if($value["Number"]>$max1){
            $max5=$max4;
            $max4=$max3;
            $max3=$max2;
            $max2=$max1;
            $max1=$value["Number"];
            $town5=$town4;
            $town4=$town3;
            $town3=$towndeux;
            $towndeux=$town1;
            $town1=$key;
        }else{
            if($value["Number"]>$max2){
                $max5=$max4;
                $max4=$max3;
                $max3=$max2;
                $max2=$value["Number"];
                $town5=$town4;
                $town4=$town3;
                $town3=$towndeux;
                $towndeux=$key;
            }else{
                if($value["Number"]>$max3){
                    $max5=$max4;
                    $max4=$max3;
                    $max3=$value["Number"];
                    $town5=$town4;
                    $town4=$town3;
                    $town3=$key;
                }else{
                    if($value["Number"]>$max4){
                        $max5=$max4;
                        $max4=$value["Number"];
                        $town5=$town4;
                        $town4=$key;
                    }else{
                        if($value["Number"]>$max5){
                        $max5=$value["Number"];
                        $town5=$key;
                        }
                    }
                }
            }
        }  
    }       
}
foreach ($town2 as $key => $value) {

    if(!empty($value["Number"])){

        $zones[$value["zone"]]+=$value["Number"];
        $zonestotal+=$value["Number"];

        $virgule=strpos($key, ",");

        if(!empty($virgule)){

            $array=explode(",",$key);
            $key=$array[0];
            $key=str_replace(" ", "", $key);
        }


        if($value["Number"]>$max1){
            $max5=$max4;
            $max4=$max3;
            $max3=$max2;
            $max2=$max1;
            $max1=$value["Number"];
            $town5=$town4;
            $town4=$town3;
            $town3=$towndeux;
            $towndeux=$town1;
            $town1=$key;
        }else{
            if($value["Number"]>$max2){
                $max5=$max4;
                $max4=$max3;
                $max3=$max2;
                $max2=$value["Number"];
                $town5=$town4;
                $town4=$town3;
                $town3=$towndeux;
                $towndeux=$key;
            }else{
                if($value["Number"]>$max3){
                    $max5=$max4;
                    $max4=$max3;
                    $max3=$value["Number"];
                    $town5=$town4;
                    $town4=$town3;
                    $town3=$key;
                }else{
                    if($value["Number"]>$max4){
                        $max5=$max4;
                        $max4=$value["Number"];
                        $town5=$town4;
                        $town4=$key;
                    }else{
                        if($value["Number"]>$max5){
                        $max5=$value["Number"];
                        $town5=$key;
                        }
                    }
                }
            }
        }
    }
}
$tempsbdd =gmdate("i:s",strtotime(date("H:i:s"))-strtotime($finapi));
$tempsapi =gmdate("i:s",strtotime($finapi)-strtotime($debutapi));
echo "Temps d'appel api linkedin : ".$tempsapi."</br>";
echo "Temps d'appel bdd : ".$tempsbdd."</br>";
echo "Nombre d'appels geocode :".$countgeo."</br>";




?>