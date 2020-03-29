<?php
require("sonos.class.php");
include "lib.php";

$chemin = $_GET["chemin"];

$sonos_1 = new SonosPHPController($Sonos_1_ip); 
$lecture = $sonos_1->RemoveAllTracksFromQueue();
$lecture = $sonos_1->AddURIToQueue($chemin,$next=0);
$lecture = $sonos_1->Play();
echo "OK";



?>
