<?php
require("sonos.class.php");
include "config.php";

$sonos_1 = new SonosPHPController($IP_sonos_1); 
$lecture = $sonos_1->SetPlayMode("SHUFFLE");
?>
