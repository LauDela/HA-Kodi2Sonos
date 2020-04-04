<?php
require("sonos.class.php");
include "lib.php";
require_once('id3/getid3/getid3.php');

$sonos_1 = new SonosPHPController($Sonos_1_ip); 
$lecture = $sonos_1->GetTransportInfo();
if($lecture=="PLAYING"){
    $lecture2 = $sonos_1->GetPositionInfo();
    $lecture_titre = $lecture2["Title"];
    $lecture_AlbumArtist = $lecture2["AlbumArtist"];
    $lecture_Album = $lecture2["Album"];
    $lecture_TitleArtist = $lecture2["TitleArtist"];
    $lecture_cover = substr($lecture2["TrackURI"],12);
    $lecture_cover = str_replace($uri_search_cover,$uri_replace_cover,$lecture_cover);
    
    echo "COVER=".$lecture_cover;
    //COVER ART
    $getID3 = new getID3;
    $ThisFileInfo = $getID3->analyze($lecture_cover);
    $getID3->CopyTagsToComments($ThisFileInfo); 
    $image_mime = $ThisFileInfo['comments']['picture'][0]['image_mime'];   
    $cover = '<img src="data:'.$image_mime.';base64,'.base64_encode($ThisFileInfo['comments']['picture'][0]['data']).'" height="60px" width="60px">';
    echo "<table><tr><td width='61px'>".$cover."</td><td>".$lecture_TitleArtist."<br>".$lecture_titre."<br>".$lecture_Album."</td></tr></table>";
} else{
  $cover="";
  }
?>
