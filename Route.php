<?php
function __autoload($class_name) {
  require __DIR__  . DIRECTORY_SEPARATOR .  'inc'. DIRECTORY_SEPARATOR . $class_name . '.php';
}
$Lng = $_GET['Lng'];
$Lat = $_GET['Lat'];
$Res = new Timbus();
echo $Res->Route($Lng, $Lat);