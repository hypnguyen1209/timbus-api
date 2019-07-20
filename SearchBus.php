<?php
$query = $_GET['q'];
function __autoload($class_name) {
  require __DIR__  . DIRECTORY_SEPARATOR .  'inc'. DIRECTORY_SEPARATOR . $class_name . '.php';
}
$Res = new Timbus();
echo $Res->SearchBus($query);
