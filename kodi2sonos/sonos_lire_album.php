<?php
require("sonos.class.php");
include "lib.php";

$idAlbum = $_GET["idAlbum"];

$sonos_1 = new SonosPHPController($Sonos_1_ip); 
$lecture = $sonos_1->RemoveAllTracksFromQueue();
$lecture = $sonos_1->SetPlayMode("NORMAL");

include "config.php";
$sql = "SELECT idSong, strTitle, iTrack, iDuration, concat(path.strPath,strFileName) AS chemin from song INNER JOIN path ON song.idPath = path.idPath where idAlbum=$idAlbum order by iTrack;";
echo $sql;
$result = mysqli_query($conn,$sql);          

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $chemin = $row["chemin"]; 
        $chemin = str_replace("&","%26",$chemin);
        $chemin1 = str_replace($uri_search_song,$uri_replace_song,$chemin);
        $lecture = $sonos_1->AddURIToQueue($chemin1,$next=0);
    }    
$lecture = $sonos_1->Play();
echo "OK";
 }


?>
