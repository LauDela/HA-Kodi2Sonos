<?php
require("sonos.class.php");
$IP_sonos_1 = "192.168.10.4"; // cuisine
$chemin = $_GET["chemin"];

$sonos_1 = new SonosPHPController($IP_sonos_1); 
$lecture = $sonos_1->RemoveAllTracksFromQueue();
$lecture = $sonos_1->AddURIToQueue($chemin,$next=0);
$lecture = $sonos_1->Play();
echo "OK";



?>
