<?php

// configuration
include 'lib.php';

$row = $_POST['row'];
$rowperpage = 6;

// selecting posts
//$query = 'SELECT * FROM posts limit '.$row.','.$rowperpage;
$query ="SELECT idArtist, strArtist, url FROM artist LEFT JOIN art ON artist.idArtist = art.media_id WHERE art.media_type='artist' AND art.`type`='thumb' order by strArtist asc limit ".$row.",".$rowperpage;
            
$result = mysqli_query($conn,$query);

$html = '';

while($row = mysqli_fetch_array($result)){
    
    $url3 = str_replace("smb://MEDIAS/Public/musique","/medias/musique",$row["url"]);
    $id_artist =  $row["idArtist"];
    $artist = $row["strArtist"];
    // Creating HTML structure

    $html .= '<article class="post" id="post_'.$id_artist.'">';
    $html .='<a href="talbumsV2.php?idartist='.$id_artist.'"><img class="article-img" src="'.$url3.'" alt=" " /> </a>';
    $html .='<h1 class="article-title">';
    $html .= $artist;
    $html .='</h1>';
    $html .='</article>';


}

echo $html;
