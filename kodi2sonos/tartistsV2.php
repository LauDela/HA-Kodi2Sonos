<!DOCTYPE html>
<html>
  <head>
    <title>Kodi2Sonos</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/stylepopup.css">
	<link rel="stylesheet" href="css/styletoggle.css">
	<script src="js/modernizr.custom.js"></script>
     <script src='js/jquery-3.3.1.js' type='text/javascript'></script>
     <script>
        $(document).ready(function(){
			
            $(window).scroll(function(){
                var position = $(window).scrollTop();
                var bottom = $(document).height() - $(window).height();
                if( position == bottom ){
                    var row = Number($('#row').val());
                    var allcount = Number($('#all').val());
                    var rowperpage = 6;
                    row = row + rowperpage;

                    if(row <= allcount){
                        $('#row').val(row);
                        $.ajax({
                            url: 'fetch_data.php',
                            type: 'post',
                            data: {row:row},
                            success: function(response){
                                $(".post:last").after(response).show().fadeIn("slow");
                            }
                        });
                    }
                }
            });
        
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
				  <li><a href="#"><div class="popup-button" data-modal="popup"><img src="web_images/speaker_to.png" width="50px"></div></a></li>
			    </ul>
		    </div>
    </div>
    <!-- end Navigation -->
	</div></div>
 
    <main>
    <div id="info_media"></div>
     <div class="controls">
          <table><tr><td><button onclick="goBack()"><img src="web_images/history_back.png" width="50px"></button></td><td><div id="prev"><img src="web_images/skip-to-start-50.png"></div></td><td><div id="play"><img src="web_images/play-50.png"></div></td><td><div id="pause"><img src="web_images/pause-50.png"></div></td><td><div id="stop"><img src="web_images/stop-50.png"></td><td><div id="next"><img src="web_images/end-50.png"></div></td><td class="shuffle"><div id="shuffle"><a href="#"><img src="web_images/shuffle-50.png"></a></div></td><td><img src="web_images/repeat-50.png"></td></tr></table>
     </div>
     <section class="cards">
 <?php
            include "lib.php";
            $rowperpage = 18;    
            // counting total number of posts
            $allcount_query = "SELECT count(*) as allcount FROM artist";
            $allcount_result = mysqli_query($conn,$allcount_query);
            $allcount_fetch = mysqli_fetch_array($allcount_result);
            $allcount = $allcount_fetch['allcount'];
            $sql ="SELECT idArtist, strArtist, url FROM artist LEFT JOIN art ON artist.idArtist = art.media_id WHERE art.media_type='artist' AND art.`type`='thumb' order by strArtist asc limit 0,$rowperpage ";
            
            if ($result = mysqli_query($conn, $sql)) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $url3 = str_replace($uri_search,$uri_replace,$row["url"]);
                  $id_artist =  $row["idArtist"];
                  $artist = $row["strArtist"];
             ?>
             <article class="post" id="post_<?php echo $id_artist; ?>">
                  <a href="talbumsV2.php?idartist=<?php echo $id_artist;?>"><img class="article-img" src="<?php echo $url3;?>" alt=" " /> </a>
                  <h1 class="article-title">
                  <?php echo $artist;?>
                  </h1>
             </article>
           <?php  
             }
            
            $result->close();
            }            
  ?>   
<input type="hidden" id="row" value="18">
<input type="hidden" id="all" value="<?php echo $allcount; ?>">
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