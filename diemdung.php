<?php
set_time_limit(0);
$Reponse = file_get_contents('http://timbus.vn/');
preg_match('#StopCss">(.+?)</span>#', $Reponse, $ArrayDD);
preg_match('#RouteCss">(.+?)</span>#', $Reponse, $ArrayRo);
file_put_contents('diemdung.json', $ArrayDD[1]);
file_put_contents('tram.json', $ArrayRo[1]);
