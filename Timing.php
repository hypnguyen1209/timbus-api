<?php
$stateID = $_GET['state'];
function __autoload($class_name) {
  require __DIR__  . DIRECTORY_SEPARATOR .  'inc'. DIRECTORY_SEPARATOR . $class_name . '.php';
}
$Res = new Timbus();
echo $Res->Timing($stateID);

