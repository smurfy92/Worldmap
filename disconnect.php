<?php 
session_name('linkedin');
session_start();
session_destroy();

header("Location: worldmap.php");

 ?>