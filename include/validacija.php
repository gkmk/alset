<?php 
$error = strip_tags($_GET['error']);
if (!$error) {
$email = strip_tags($_GET['email']);
$reged = strip_tags($_GET['reged']);
$id = strip_tags($_GET['id']);
}

require_once 'konfiguracija.php';
require_once 'AJAX/korisnik.php';
require_once 'AJAX/firma.php';

try {
	if ($error) {
		$parametri = explode("^", $error);
		if (strtoupper($parametri[1]) == "OK" || strlen($parametri[1]) != 32) {
			$greska_info = "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
			$greska_info .= "Сакаше да го хакира системот за деактивација на корисник.";
			KonektirajDB();
			$QUERY = "INSERT INTO logovi ( tip, info ) VALUES ( 'Хакирање', '$greska_info' )";
			mysql_query($QUERY);
			
			throw new Exception("Благодариме што пробавте да не хакирате, но неуспеавте :). <br /> Ајде, обидете се повторно, МОЖЕТЕ! ;)");
		}
		
		
		if ($parametri[0] == 0) Firma::Brisi($parametri[1]);
		else Korisnik::Brisi($parametri[1]);
		
		echo "<script type='text/javascript'>
		//<![CDATA[
		novaNotifikacija('<h1>Вашиот профил е избришан од нашата база.</h1>');
		//]]>
		</script>";
	}
	else {
	if ($email) {
		Korisnik::ValidirajKorisnik($email, $id);
		echo "<script type='text/javascript'>
		//<![CDATA[
		novaNotifikacija('<h1>Вашиот емаил е потврден и профилот е сега активен.<br />Благодариме.</h1>');
		//]]>
		</script>";
	}
	else {
		Firma::ValidirajFirma($reged, $id);
		echo "<script type='text/javascript'>
		//<![CDATA[
		novaNotifikacija('<h1>Вашиот емаил е потврден. Нашите администратори ќе го разгледаат и ќе ви одговорат на вашето барање најдоцна до 24часа од работните денови.<br />Благодариме.</h1>');
		//]]>
		</script>";
	}
	}
	
	
}catch(Exception $e){
	echo "<script type='text/javascript'>
	//<![CDATA[
	novaNotifikacija('<h1>".$e->getMessage()."</h1>');
	//]]>
	</script>";
}

?>

