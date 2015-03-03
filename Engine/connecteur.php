<?php
$host="localhost";
$database="worldmap";
$user="worldmap";
$pass="worldmap";

$link=mysqli_connect($host,$user,$pass);
mysqli_select_db($link,$database);

/*$sql="SELECT * From user";
$req=mysqli_query($link,$sql);
$user=array();
while($data=mysqli_fetch_assoc($req)){
   array_push($user, $data);
   

}*/
?>