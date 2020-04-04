<?php
require("sonos.class.php");
include "lib.php";

$sonos_1 = new SonosPHPController($Sonos_1_ip); 
$lecture = $sonos_1->Stop();
?>
