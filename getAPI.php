<?php 
	require('GoogleMapAPIv3.class.php');
	/** On recupere le JSON de l'API**/
	$url = "http://api.openweathermap.org/data/2.5/weather?q=" . $_POST['city'];
	//  Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL,$url);
	// Execute
	$result = curl_exec($ch);
	// Closing
	curl_close($ch);

	$json = file_get_contents('http://api.deezer.com/search/track?q='.$_POST['city'].'&title='.$_POST['city'].'&index=0&nb_items=1&output=json');
	$deezer = json_decode($json);

	$track_id = $deezer->data[0]->id;

	// Will dump a beauty json :3
	$obj = json_decode($result, true);

	if ($obj !== null) 
	{
		if ($obj['cod'] !== '404') 
		{
			$gmap = new GoogleMapAPI('AIzaSyCgcmC_VPnhBfM7zFCWotlT0rTyVPBjz5M');
			$gmap->setDivId('map');
			$gmap->setCenter($obj['name']);
			$gmap->setDisplayDirectionFields(true);
			$gmap->setClusterer(true);
			$gmap->setSize(1900,800);
			$gmap->setZoom(13);
			$coordtab = array();
			$coordtab []= array($obj['name']." ".$obj['sys']['country'],$obj['name'],'<strong>City : '.$obj['name'].'<div>Weather : '.$obj['weather'][0]['description'].'</div></strong>');
			$gmap->setIconSize(20,34);
			$gmap->addArrayMarkerByAddress($coordtab,'http://maps.gstatic.com/mapfiles/markers2/marker_sprite.png');
			$gmap->generate();
			echo $gmap->getGoogleMap();	
		}
		else
		{
			echo "Your city doesn't exist !";
		}
	}
	else
	{
		echo "Your city doesn't exist !";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta>
			<title>Find your movie easily !</title>
		</meta>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	</head>

	<body>
		<div class="form">
			<form action="getAPI.php" method="post">
			  Your city: <input type="text" name="city"><br>
			  <input type="submit" value="Submit" class='btn-info btn'>
			</form>
			<iframe scrolling="no" frameborder="0" allowTransparency="true" src="http://www.deezer.com/plugins/player?autoplay=true&playlist=false&width=700&height=80&cover=true&type=tracks&id=<?php echo $track_id;?>&title=&app_id=undefined" width="700" height="80"></iframe>
		</div>
	</body>
</html>

