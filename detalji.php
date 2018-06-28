<?php
header('Access-Control-Allow-Origin: *');
include ("funkcije.php");
$ime = $_GET["id"];

$dom = new DOMDocument();
$dom->load('podaci.xml'); 
$xp = new DOMXPath($dom); 
$putanja = '/muzeji/muzej[contains(@id, "'. $ime . '")]';
$rez = $xp->query($putanja);

foreach($rez as $elem){
	if(($ime) == ($elem->getAttribute('id'))) {
		
			echo "Adresa: ";
			if(isset($lokacija)){
				echo $lokacija['details']['location']['street'];
			} else {
				echo $elem->getElementsByTagName('ulica')->item(0)->nodeValue.$elem->getElementsByTagName('ulica')->item(0)->getAttribute('kbroj');
			}
			
			echo "<br/>Telefon: ";
			$tel = $elem->getElementsByTagName('telefon');
			if($tel->length == 0) {echo "N/A";} else {
				for ($i = 0; $i < $tel->length; $i++){
					echo $tel[$i]->nodeValue.' '.$tel[$i]->getAttribute('broj')."<br>";
				}
			}
			
			echo "<br/>E-mail: ";
			$em = $elem->getElementsByTagName('email');
			if($em->length == 0) {echo "N/A";} else {
				for ($i = 0; $i < $em->length; $i++){
					echo $em[$i]->nodeValue."<br>";
				}
			}
			
			echo "<br/>Vrsta: <br/>";
			if(!empty($elem->getElementsByTagName('povijest')->item(0))) {echo 'Povijesni <br>';}
			if(!empty($elem->getElementsByTagName('umjetnost')->item(0))) {echo 'Umjetnosti <br>';}
			if(!empty($elem->getElementsByTagName('vojni')->item(0))) {echo 'Vojni <br>';}
			if(!empty($elem->getElementsByTagName('znanost')->item(0))) {echo 'Znanosti <br>';}
			if(!empty($elem->getElementsByTagName('lokalni')->item(0))) {echo 'Lokalni <br>';}
			echo "<br/>";		
	}
}
sleep(1);


?>