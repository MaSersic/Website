<?php

//print_r($_REQUEST);
	function upit() {
		$array = array();
		
		if(!empty($_REQUEST['ime'])) $array[] = 'contains('.upperCase('@ime').', "'.mb_strtoupper($_REQUEST['ime'], "UTF-8").'")';

		$vrsta = array();
		if(!empty($_REQUEST['umjetnost']))	$vrsta[] = "umjetnost";
		if(!empty($_REQUEST['povijest']))	$vrsta[] = "povijest";
		if(!empty($_REQUEST['vojni']))		$vrsta[] = "vojni";
		if(!empty($_REQUEST['lokalni']))	$vrsta[] = "lokalni";
		if(!empty($_REQUEST['znanost']))	$vrsta[] = "znanost";
		
		if(!empty($vrsta)) $array[] =  "(" . implode( " or ", $vrsta) . ")";
		
		if(!empty($_REQUEST['cijena'])) $array[] = 'cijena<'.$_REQUEST['cijena'].'';
		
		if(!empty($_REQUEST['kvart'])) $array[] = 'contains('.upperCase('adresa/kvart').', "'.mb_strtoupper($_REQUEST['kvart'], "UTF-8").'")';
		if(!empty($_REQUEST['ulica'])) $array[] = 'contains('.upperCase('adresa/ulica').', "'.mb_strtoupper($_REQUEST['ulica'], "UTF-8").'")';
		
		if(!empty($_REQUEST['radnovrijeme'])){
			foreach ($_REQUEST['dan'] as $elem) {
				$array[] = "radnovrijeme/@dan='".$elem."'";
			}
			$array[] = '((number("'.$_REQUEST['radnovrijeme'].'") > number(substring-before(radnovrijeme,"-"))) and (number("'.$_REQUEST['radnovrijeme'].'") < number(substring-after(radnovrijeme,"-"))))';
		}
		
		if(!empty($_REQUEST['telefon']) and ($_REQUEST['telefon']=="da"))	$array[] = "telefon";
		if(!empty($_REQUEST['webstranica']) and ($_REQUEST['webstranica']=="da"))	$array[] = "webstranica";
		if(!empty($_REQUEST['email']) and ($_REQUEST['email']=="da"))	$array[] = "email";						
		if(!empty($_REQUEST['vodic']) and ($_REQUEST['vodic']=="da"))	$array[] = "vodic";
		if(!empty($_REQUEST['parking']) and ($_REQUEST['parking']=="da"))	$array[] = "parking";
		if(!empty($_REQUEST['grupa']) and ($_REQUEST['grupa']=="da"))	$array[] = "grupa";
		
		$query = implode(" and ", $array);
		
		if(!empty($query)){
			return "/muzeji/muzej[" . $query . "]";
		} else {
			return "/muzeji/muzej";
		}
	}
	
	function upperCase($string) {
		return	"translate(" . $string . ",  'abcdefghijklljmnnjopqrstuvwxyzšđčćž', 'ABCDEFGHIJKLLJMNNJOPQRSTUVWXYZŠĐČĆŽ')";
	}
	
	function fb_slika($argument) {
			$link = "http://161.53.67.210:1234/fakebook/" . $argument;

			if(!($fts = @file_get_contents($link))){
				return "X.jpg";
			}
			
			$encod = mb_convert_encoding($fts, "UTF-8", mb_detect_encoding($fts, 'UTF-8, ISO-8859-1', true));
			$dekodirano = json_decode($encod, true);
			if (isset ($dekodirano)){
				return $dekodirano['details']["picture"];
			}
	} 
	
	
	function fb_adresa($argument) {
			$link = "http://161.53.67.210:1234/fakebook/" . $argument;
			$fts = file_get_contents($link);
			$encod = mb_convert_encoding($fts, "UTF-8", mb_detect_encoding($fts, 'UTF-8, ISO-8859-1', true));
			$dekodirano = json_decode($encod, true);
			if (isset ($dekodirano))
			{return $dekodirano;}		
		
	}
	
	function fb_web($argument) {
			$link = "http://161.53.67.210:1234/fakebook/" . $argument;
			$fts = file_get_contents($link);
			$encod = mb_convert_encoding($fts, "UTF-8", mb_detect_encoding($fts, 'UTF-8, ISO-8859-1', true));
			$dekodirano = json_decode($encod, true);
			if (isset ($dekodirano['details']["website"]))
			{return $dekodirano['details']["website"];}		
		
	}
		
	function koord($arg) {
		$url = "https://nominatim.openstreetmap.org/search?q=";
		$adresa=$arg['details']['location']['street'];
		$grad=$arg['details']['location']['city'];
		$drzava=$arg['details']['location']['country'];
		if(empty($adresa) || empty($grad) || empty($drzava)){
			return;
		}
		$ulaz=$adresa.', '.$grad.', '.$drzava;
		$upit = urlencode($ulaz);
		$cijeliupit = $url.$upit.'&format=xml&limit=1';
		return $cijeliupit;
	}

?>