<?php


$dbname = "MyMusic72"; /* Database name */
$servername = '192.168.10.89';
$username = 'kodi';
$password = 'UiD$19R7610';

$uri_search = "smb://MEDIAS/Public/musique";
$uri_replace = "/medias/musique";

$uri_search_song = "smb";
$uri_replace_song = "x-file-cifs";

$uri_search_cover = "//MEDIAS/Public/musique";
$uri_replace_cover = "medias/musique";

$IP_sonos_1 = "192.168.10.4";

$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
}
