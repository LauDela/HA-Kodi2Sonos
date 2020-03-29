<?php
// Exemple d'application de la classe PHP Sonos permettant de baisser le niveau sonore s'il est supérieur à 50%
require("sonos.class.php");
$IP_sonos_1 = "192.168.10.4"; // cuisine
$artist = $_GET["artiste"];
$album = $_GET["album"];
$kodi_url = "http://kodi:kodi76@192.168.10.88:8080/jsonrpc";

$sonos_1 = new SonosPHPController($IP_sonos_1); 
$lecture = $sonos_1->RemoveAllTracksFromQueue();

//--------------------------On vide la playliste
$data = '{"id":"160","jsonrpc":"2.0","method":"Playlist.Clear","params":{"playlistid":1}}';

$ch = curl_init($kodi_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data))                                                                       
); 

$result = curl_exec($ch);
//echo $result;
curl_close($ch);

if($artist){
//--------------------------On ajoute l'artiste à la playliste
$data='{"jsonrpc":"2.0","id":1,"method":"Playlist.Add","params":{"playlistid":1,"item":{"artistid":'.$artist.'}}}';
//echo "<br>".$data."<br>";
$ch = curl_init($kodi_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data))
);

$result = curl_exec($ch);
curl_close($ch);
}

if($album){
//--------------------------On ajoute l'album à la playliste
$data='{"jsonrpc": "2.0", "id": 1, "method": "Playlist.Add", "params": {"playlistid": 1, "item": { "albumid":'.$album.'}}}';
echo "<br>".$data."<br>";
$ch = curl_init($kodi_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data))
);

$result = curl_exec($ch);
curl_close($ch);
}

//--------------------------On lit la playliste
$data='{"jsonrpc": "2.0", "id": 1, "method": "Playlist.GetItems", "params": {"playlistid": 1}}';
$ch = curl_init($kodi_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data))                                                                       
); 

$result = curl_exec($ch);
curl_close($ch);

$json = json_decode($result,true);
$items = $json['result']['items'];
$i=0;
foreach($items as $item)if ($i < 50){
   $song = $item['id'];
   $data0='{"jsonrpc": "2.0", "id": 1, "method": "AudioLibrary.GetSongDetails", "params": {"songid": '.$song.', "properties": ["title", "album", "artist","file"]}}';
   $ch0 = curl_init($kodi_url);
    curl_setopt($ch0, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch0, CURLOPT_POSTFIELDS,$data0);
    curl_setopt($ch0, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch0, CURLOPT_HTTPHEADER, array(                                                                          
      'Content-Type: application/json',                                                                                
      'Content-Length: ' . strlen($data0))                                                                       
    ); 

    $result0 = curl_exec($ch0);
    //echo $result0."<br>";
    $json0 = json_decode($result0,true);
    //echo $json0['result']['songdetails']['file']."<br><br>";
    $chemin = $json0['result']['songdetails']['file'];
    $chemin1 = str_replace("smb","x-file-cifs",$chemin);
    //echo $chemin1."<br>";
    $retour= $sonos_1->AddURIToQueue($chemin1,1);
    curl_close($ch0);
    $i +=1;
}


//Instanciation de la classe
//$sonos_1 = new SonosPHPController($IP_sonos_1); 
//$volume = $sonos_1->GetVolume();
//echo "Volume de ".$volume."<br>";
//if ($volume > 30)
//     $sonos_1 = $sonos_1->SetVolume(30);
//echo "ceci est un test <br>";     
//$mp3="x-file-cifs://medias/public/musique/A-ha/Headlines%20and%20deadlines%20The%20hits%20of%20A-ha/03%20-%20Touchy.mp3";
//$retour= $sonos_1->AddURIToQueue($mp3,1);

//$retour= $sonos_1->SetQueue($mp3);
//$retour= $sonos_1->SetQueue("top classement");
#echo "Retour ".$retour."<br>";
//echo 'Bonjour ' . htmlspecialchars($_GET["artistid"]) . '!';

$lecture = $sonos_1->Play();
return "ok";

?>