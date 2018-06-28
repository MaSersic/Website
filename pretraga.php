<?php
	header('Access-Control-Allow-Origin: *');
	error_reporting (E_ALL);
	include_once ('funkcije.php');
	$dom = new DOMDocument();
  	$dom->load('podaci.xml');
  	$xp = new DOMXPath($dom);
	$upit = upit();
	$queryResult = $xp->query($upit);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="Laboratorijske vježbe iz OR"/>
		<meta name="author" content="Marin Seršić"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="dizajn.css" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
		<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
		<title>Muzeji u Londonu</title>
	</head>
	
	<body>
		<header>
			Muzeji u Londonu
		</header>
		
		<div class="row">
			<nav>
				<ul>
					<li><a href="index.html" >Početna</a>		</li>
					<li><a href="obrazac.html" >Pretraga</a>	</li>
					<li><a href="podaci.xml" >Muzeji</a> 	</li>
				</ul>
				<div id="detaljiZag">Detalji</div>
				<div id="detalji"></div>
				<div id="twitter"> 
				</div>
			</nav>
				
			<main>
				<table class="outputTable">
					<thead>
						<tr class="OTrow">
							<th>Ime</th>
							<th>Cijena</th>
							<th>Radno vrijeme</th>
							<th>Web stranica</th>
							<th>Akcija</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($queryResult as $elem) {
								$rvr = $elem->getElementsByTagName('radnovrijeme');
								$tel = $elem->getElementsByTagName('telefon');
								$em = $elem->getElementsByTagName('email');
								$ws = $elem->getElementsByTagName('webstranica');
									
								
								if(empty($elem->getElementsByTagName('duljina')->item(0)->nodeValue) || empty($elem->getElementsByTagName('sirina')->item(0)->nodeValue)){
									$lokacija = fb_adresa($elem->getElementsByTagName('fid')->item(0)->nodeValue);							
									$koord = koord ($lokacija);									
									if(isset ($koord)){
										$opts = array('http'=>array('header'=>"User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0\r\n"));
										$context = stream_context_create($opts);
										$xml = file_get_contents($koord, false, $context);
										sleep(2);
									}
									if(!isset ($koord)){
										$url = "https://nominatim.openstreetmap.org/search?q=";
										$adresa=$elem->getElementsByTagName('ulica')->item(0)->nodeValue.$elem->getElementsByTagName('ulica')->item(0)->getAttribute('kbroj');
										$ulaz = $adresa.',London,United Kingdom';
										$upit = urlencode($ulaz);
										$cijeliupit = $url.$upit.'&format=xml&limit=1';
										$opts = array('http'=>array('header'=>"User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0\r\n"));
										$context = stream_context_create($opts);
										$xml = file_get_contents($cijeliupit, false, $context);
										sleep(2);
									}						

									
									$text = simplexml_load_string($xml);
									if(empty($text)){ print_r ('nema'); }								
									
									$duljina = $text->place[0]['lon'];
									$sirina = $text->place[0]['lat'];
								} else {
									$duljina = $elem->getElementsByTagName('duljina')->item(0)->nodeValue;
									$sirina = $elem->getElementsByTagName('sirina')->item(0)->nodeValue;
								}
								
								//$website = fb_web($elem->getElementsByTagName('fid')->item(0)->nodeValue);
						?>
							<tr>
								<td class="cell"> <?php echo $elem->getAttribute('ime'); ?> </td>

								<td class="cell"> <?php echo $elem->getElementsByTagName('cijena')->item(0)->nodeValue; ?> </td>
								<td class="cell"> <?php 	for ($i = 0; $i < $rvr->length; $i++){
												echo $rvr[$i]->getAttribute('dan').' '.$rvr[$i]->nodeValue."<br>";
											} ?> </td>
								<td class="cell"> <?php if($ws->length == 0) {echo "N/A";}
												for ($i = 0; $i < $ws->length; $i++){
												echo $ws[$i]->nodeValue."<br>";
											} ?> 
								</td>
								<td>
									<?php $url = "http://localhost/or/detalji.php?id=".$elem->getAttribute("id")."&show=simple";  
											if(!empty($elem->getElementsByTagName('webstranica')->item(0))){
												$webstranica = $elem->getElementsByTagName('webstranica')->item(0)->nodeValue;
											} else {
												$webstranica = "N/A";
											}?>
									<a href="#" onclick="prikaziDetalje('<?php echo $url  ?>', '<?php echo $elem->getAttribute('ime'); ?>'); mapMe('<?php echo $sirina ?>', '<?php echo $duljina ?>', '<?php echo $elem->getAttribute('ime') ?>', '<?php echo $webstranica ?>');">Detalji</a>
									<br />
								</td>	
							</tr>
						<?php 
							}
						?>
					</tbody>
				</table>
				<div id="mapid">
					<style> #mapid { min-height: 600px; visibility: hidden;} </style>
					<script> 
					var mymap = L.map('mapid').setView([51.505, -0.09], 13);
					L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
						maxZoom: 19,
						attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
					}).addTo(mymap);
					</script>
					<script type="text/javascript" src="detalji.js"></script>
				</div>
				<div>
				<a href="#" onclick="<?php
									foreach ($queryResult as $elem) {
										$duljina = $elem->getElementsByTagName('duljina')->item(0)->nodeValue;
										$sirina = $elem->getElementsByTagName('sirina')->item(0)->nodeValue;
										$ime = $elem->getAttribute('ime');
										if(!empty($elem->getElementsByTagName('webstranica')->item(0))){
											$webstranica = $elem->getElementsByTagName('webstranica')->item(0)->nodeValue;
										} else {
											$webstranica = "N/A";
										}
										echo "mapAll("."'".$sirina."'".", "."'".$duljina."'".", "."'".$elem->getAttribute('ime')."'".", "."'".$webstranica."'".");"
									;}?>">Prikaži sve</a>
				</div>
			</main>
		</div>

	</body>
		
	<footer>
		Autor: Marin Seršić
	</footer>
</html>
		
	
	
