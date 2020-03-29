<?php

// configuration
include 'config.php';

$row = $_POST['row'];
$rowperpage = 3;
$idArtist =  $_POST['idart'];
// selecting posts
//$query = 'SELECT * FROM posts limit '.$row.','.$rowperpage;
//$query ="SELECT album_artist.idArtist, album_artist.strArtist, album_artist.idAlbum, album.strAlbum, album.iYear,  replace(mid(url,5,LENGTH(url)-4),'/','\\') AS chemin, url FROM album_artist LEFT JOIN album on album_artist.idAlbum = album.idAlbum LEFT JOIN art ON album.idAlbum = art.media_id WHERE art.media_type='album' AND art.`type`='thumb' AND album_artist.idArtist=$idArtist limit ".$row.",".$rowperpage;
$query="SELECT album_artist.idArtist, album_artist.strArtist, album_artist.idAlbum, album.strAlbum, album.iYear, art.url FROM album_artist INNER JOIN album on album_artist.idAlbum = album.idAlbum INNER JOIN art ON album.idAlbum = art.media_id WHERE art.media_type='album' AND art.`type`='thumb' and album_artist.idArtist=$idArtist limit ".$row.",".$rowperpage;                        
$result0 = mysqli_query($conn,$query);
$html = '';

while($row = mysqli_fetch_array($result0)){
    
    $url3 = str_replace("smb://MEDIAS/Public/musique","/medias/musique",$row["url"]);
    $id_album =  $row["idAlbum"];
    $album = $row["strAlbum"];
    // Creating HTML structure

    $html .= '<article class="post" id="post_'.$id_album.'">';
    $html .='<a href="talbumV2.php?idalbum='.$id_album.'"><img class="article-img" src="'.$url3.'" alt=" " /> </a>';
    $html .='<h1 class="article-title">';
    $html .= $album;
    $html .='</h1>';
    $html .='</article>';


}

echo $html;
