<?php
try {

require_once 'sess_check.php';
require_once 'konfiguracija.php';
require_once 'mail/class.phpmailer.php';
require_once 'funkcii.php';
require_once 'baraj.php';
require_once 'AJAX/korisnik.php';
require_once 'AJAX/firma.php';


//	Zemi baranje
$do = $_REQUEST['izvrsi'];
//	Iscisti go
$do = strip_tags($do);
$do = explode( "^", $do);

$odgovor = array();
//	Procesiranje na baranje
switch ($do[0]) {
	case 'zemiSlicniFirmi'	 :	$odgovor = Firma::zemiSlicniFirmi($do[1], $do[2]);
	break;
	//	------------------------------------------------------------------------
	case 'zemiProdukti'	 :	$odgovor = Firma::zemiProdukti($do[1]);
	break;
	//	------------------------------------------------------------------------
	case 'vcitajFirma'	 :	$odgovor = Firma::Vcitaj($do[1]);
	break;
	//	------------------------------------------------------------------------
	case 'BrisiOdTabela' :
		$odgovor = BrisiOdTabela($do[1], $do[2]);
	break;
	//	------------------------------------------------------------------------
	case 'GenerirajTabela' :
		$pol = explode(",",$do[1]);
		$imi = explode(",",$do[2]);
		$odgovor = generirajTabela(
		$_POST['div'],
		$_POST['tab'],
		$_POST['ind'],
		$pol,
		$imi,
		$_POST['ord'], 
		$_POST['ofs'],
		$_POST['lim'],
		$_POST['srt']);
	break;
	//	------------------------------------------------------------------------
	case 'LogirajFirma' :
		$odgovor = Firma::LogirajGo($do[1], $do[2]);
	break;
	//	------------------------------------------------------------------------
	case 'KreirajFirma' :
		$odgovor = Firma::Kreiraj($do);
	break;
	//	------------------------------------------------------------------------
	case "FirmaPostoi":
		$odgovor = Firma::DaliPostoi($do[1]);
	break;
	//	------------------------------------------------------------------------
	case "FirmaMail":
		$odgovor = Firma::MailExists($do[1]);
	break;
	//	------------------------------------------------------------------------
	case 'Baraj' :
		$odgovor = baraj($do[1]);
	break;
	//	------------------------------------------------------------------------
	case 'OdjaviMe' :
		$odgovor = Korisnik::OdjaviMe($do[1]);
	break;
	//	------------------------------------------------------------------------
	case 'LogirajGo' :
		$odgovor = Korisnik::LogirajGo($do[1], $do[2]);
	break;
	//	------------------------------------------------------------------------
	case 'daliELogiran' :
		$odgovor = Korisnik::daliELogiran();
	break;
	//	------------------------------------------------------------------------
	case md5("DaliPostoi"):
		$odgovor = Korisnik::DaliPostoi($do[1]);
	break;
	//	------------------------------------------------------------------------
	case md5("KreirajKorisnik"):
		$odgovor = Korisnik::KreirajKorisnik($do);
	break;
	//	------------------------------------------------------------------------
	default : throw new Exception('Грешно барање');
	}
	
//if (!$odgovor) $odgovor = "Немаше одговор али ваљда е OK";
echo json_encode($odgovor);

}
catch(Exception $e){
	echo json_encode(array('error' => $e->getMessage()));
}
?>