<?php
require("sonos.class.php");
$IP_sonos_1 = "192.168.10.4"; // cuisine
$idAlbum = $_GET["idAlbum"];

$sonos_1 = new SonosPHPController($IP_sonos_1); 
$lecture = $sonos_1->RemoveAllTracksFromQueue();
$lecture = $sonos_1->SetPlayMode("NORMAL");

include "config.php";
$sql = "SELECT idSong, strTitle, iTrack, iDuration, concat(path.strPath,strFileName) AS chemin from song INNER JOIN path ON song.idPath = path.idPath where idAlbum=$idAlbum order by iTrack;";
echo $sql;
$result = mysqli_query($conn,$sql);          

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $chemin = $row["chemin"]; 
        $chemin1 = str_replace("smb","x-file-cifs",$chemin);
        $lecture = $sonos_1->AddURIToQueue($chemin1,$next=0);
    }    
$lecture = $sonos_1->Play();
echo "OK";
 }


?>
