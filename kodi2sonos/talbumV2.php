<!DOCTYPE html>
<html>
  <head>
    <title>Kodi2Sonos - Album Details</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/stylepopup.css">
	<link rel="stylesheet" href="css/styletoggle.css">
    <script src='js/jquery-3.3.1.js' type='text/javascript'></script>
    <script type='text/javascript'>
		function goBack() {
			window.history.back();
		}
	
      $(document).ready(function(){
      $('#shuffle').click(function(e){
        var DATA = "btn-click";	
				$.ajax({
					type: "POST",
					url:"sonos_shuffle.php",
					data: DATA,
					cache: false,
					success: function(data){
					}
				});    
				return false;
			});
    
      $('#prev').click(function(e){
        var DATA = "btn-click";	
				$.ajax({
					type: "POST",
					url:"sonos_prev.php",
					data: DATA,
					cache: false,
					success: function(data){
					}
				});    
				return false;
			});
    
      $('#play').click(function(e){
        var DATA = "btn-click";	
				$.ajax({
					type: "POST",
					url:"sonos_play.php",
					data: DATA,
					cache: false,
					success: function(data){
					}
				});    
				return false;
			});
    
      $('#pause').click(function(e){
        var DATA = "btn-click";	
				$.ajax({
					type: "POST",
					url:"sonos_pause.php",
					data: DATA,
					cache: false,
					success: function(data){
					}
				});    
				return false;
			});
    
      $('#stop').click(function(e){
        var DATA = "btn-click";	
				$.ajax({
					type: "POST",
					url:"sonos_stop.php",
					data: DATA,
					cache: false,
					success: function(data){
					}
				});    
				return false;
			});
    
      $('#next').click(function(e){
        var DATA = "btn-click";	
				$.ajax({
					type: "POST",
					url:"sonos_next.php",
					data: DATA,
					cache: false,
					success: function(data){
					}
				});    
				return false;
			});
      
      $('#pistes tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {	
				    $.ajax({
					     type: "GET",
					     url:"sonos_lire_piste.php",
					     data: 'chemin=' + href,
					     cache: false,
					     success: function(data){
					}
				});
        return false;
        }
    });
    
    $('#pistes th').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {	
				    $.ajax({
					     type: "GET",
					     url:"sonos_lire_album.php",
					     data: 'idAlbum=' + href,
					     cache: false,
					     success: function(data){
					}
				});
        return false;
        }
    });
      
    function notif() {
          $.ajax({         
            url: "sonos_infos.php",                    
              ifModified:true,
              success: function(content){
                  $('#info_media').html(content); //id de la <div> à refresh
              }
          });
          setTimeout(notif, 1000); //refresh toutes secondes (1 minute = 60000)
      }
      notif();  
       });

    </script>
    
    
        
  </head>
  
  
  <body>
  <!-- LE CONTENU DE LA POPUP -->
 
        <div class="modal blur-effect" id="popup">
            <div class="popup-content">
                <h3>Diffusion</h3>
                <div>
                    <p class="para">Sélectionne l'enceinte ou le système sur lequel tu souhaites écouter la musique :</p>
                    <div class="page-back">
						<form class="form-sec">
							<div class="custom-check">
								<input id="kodi1" name="q" type="radio" />
								<label for="kodi1">Kodi Salon</label>
							</div>

							<div class="custom-check">
								<input id="kodi2" name="q" type="radio" />
								<label for="kodi2">Kodi Bureau</label>
							</div>
							
							<div class="custom-check">
								<input id="sonos1" name="q" type="radio" checked/>
								<label for="sonos1">Sonos Cuisine</label>
							</div>
  
						</form>
					</div>
                     
                    <div class="close"></div>
                </div>
            </div>
        </div>
 
        <!-- FIN DE LA POPUP -->
		<div class="container">

			<div class="content">
   <!-- Navigation -->
  <div id="header">
  
  
		    <div id="navigation">
			    <ul>
			      <li><a class="active" href="tartistsV2.php">ARTISTES</a></li>
			      <li><a href="#">ALBUMS</a></li>
			      <li><a href="#">DERNIERS AJOUTS</a></li>
			      <li><a href="#">STYLE</a></li>
			      <li><a href="#">PLAYLISTS</a></li>
				  <li><a href="#"><div class="popup-button" data-modal="popup"><img src="http://hassio.local:8099/web_images/speaker_to.png" width="50px"></div></a></li>
			    </ul>
		    </div>
    </div>
    <!-- end Navigation -->
	</div></div>   
    <main>
    <div id="info_media"></div>
        <div class="controls">
          <table><tr><td><div id="prev"><img src="http://hassio.local:8099/web_images/skip-to-start-50.png"></div></td><td><div id="play"><img src="http://hassio.local:8099/web_images/play-50.png"></div></td><td><div id="pause"><img src="http://hassio.local:8099/web_images/pause-50.png"></div></td><td><div id="stop"><img src="http://hassio.local:8099/web_images/stop-50.png"></td><td><div id="next"><img src="http://hassio.local:8099/web_images/end-50.png"></div></td><td class="shuffle"><div id="shuffle"><a href="#"><img src="http://hassio.local:8099/web_images/shuffle-50.png"></a></div></td><td><img src="http://hassio.local:8099/web_images/repeat-50.png"></td></tr></table>
     </div>
	 
           <?php
  
            $idalbum =(int) $_GET['idalbum'];
            include "lib.php";
            $rowperpage = 1;    
            // counting total number of posts
            $allcount_query = "SELECT count(*) as allcount FROM song where idAlbum=$idalbum";
            $allcount_result = mysqli_query($conn,$allcount_query);
            $allcount_fetch = mysqli_fetch_array($allcount_result);
            $allcount = $allcount_fetch['allcount'];
            echo  '<p class="leading-bigtext" align="right">'.$allcount.' titre(s)</p>' ;
        ?>                
      <section class="cards2">
          
              <table id="pistes">
                <thead>
                  <tr>
                    <th><a href="<?php echo $idalbum; ?>"><img src="http://hassio.local:8099/web_images/play_all.png" alt="Play All" width="50px"></a></th>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Durée</th>
                  </tr>
                  </thead>  
              <?php
                  $sql = "SELECT idSong, strTitle, iTrack, iDuration, concat(path.strPath,strFileName) AS chemin from song INNER JOIN path ON song.idPath = path.idPath where idAlbum=$idalbum order by iTrack;";
                  $result = mysqli_query($conn,$sql);
                  if ($result) {
                      while ($row = mysqli_fetch_assoc($result)) {
                          $titre =  $row["strTitle"];
                          $piste = $row["iTrack"];
                          $duree = $row["iDuration"];
                          $idSong = $row["idSong"];
                          $chemin = $row["chemin"]; 
                          $chemin1 = str_replace($uri_search_song,$uri_replace_song,$chemin);
              ?>
              <tr> 
                  <td><a href="<?php echo $chemin1; ?>"><img src="http://hassio.local:8099/web_images/play-50.png" width="35px"></a></td>
                  <td><?php echo $piste; ?></td>
                  <td><?php echo $titre; ?></td>
                  <td><?php echo $duree; ?></td>
              </tr>
           <?php  
             }
            } else{echo "Aucun titre pour cet album.<br>";
            mysqli_error($conn);         
            }
           
            ?>   
       
    </section>
  </main> 
    
<script>
/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
  this.classList.toggle("active");
  var dropdownContent = this.nextElementSibling;
  if (dropdownContent.style.display === "block") {
  dropdownContent.style.display = "none";
  } else {
  dropdownContent.style.display = "block";
  }
  });
}
</script>  
<!-- FIN DU COTENU DE LA PAGE -->

		<div class="overlay"></div><!-- La div overlay -->
		
		<!-- Le script qui crée la popup -->
		<script src="js/popup.js"></script>

		<!-- Pour l'effet blur -->
		<!-- by @derSchepp https://github.com/Schepp/CSS-Filters-Polyfill -->
		<script>
			// this is important for IEs
			var polyfilter_scriptpath = '/js/';
		</script>
		<script src="js/cssParser.js"></script>
		<script src="js/css-filters-polyfill.js"></script>             
</body>
</html>