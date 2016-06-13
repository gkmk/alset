<?php

//	VERZIJA 1.0

class Korisnik {
	//	BRISENJE NA PROFIL
	public function Brisi($uid) {
		$QUERY = "DELETE FROM `korisnici` WHERE validacija = '{$uid}'";
		KonektirajDB();
		if (!mysql_query($QUERY))throw new Exception("Грешка: " . mysql_real_escape_string(mysql_error()));
		
		if (!mysql_affected_rows())throw new Exception("Таков корисник не постои во нашата база.");	
	}
	//	--------------------------------------------------------------------------------
	
	public function OdjaviMe($uid) {
		session_destroy();

		return 'OK';		
	}
	//	--------------------------------------------------------------------------------
	
	public function LogirajGo($uid, $pass) {

		if (!Korisnik::DaliPostoi($uid)) throw new Exception("Не постои таков корисник");
		
		$QUERY = "SELECT * FROM `korisnici` WHERE email = '{$uid}'";
		KonektirajDB();
		$rezultat =	mysql_query($QUERY);
		
		$row = mysql_fetch_assoc( $rezultat );
		
		if ($row['lozinka'] != $pass) throw new Exception("Погрешна лозинка");
		
		if ($row['validacija'] != "OK") throw new Exception("Вашиот профил е неактивен.");
		
		unset($row['lozinka']);
		unset($row['validacija']);
		//12
		$korisnik = implode('^', $row);
		
		$_SESSION['user'] = "korisnik^".$korisnik;
		
		return $_SESSION['user'];
	}
	//	--------------------------------------------------------------------------------
	public function daliELogiran() {
		if ($_SESSION['user'])	return explode( "^", $_SESSION['user']);
		else return 0;
	}
	//	--------------------------------------------------------------------------------
	public function DaliPostoi($uid) {
		$QUERY = "SELECT * FROM `korisnici` WHERE email = '{$uid}'";
		
		KonektirajDB();
		
		$rezultat =	mysql_query($QUERY);
		
		if (!$rezultat) throw new Exception("Грешка во базата ".mysql_error());
		
		return mysql_num_rows( $rezultat );
	}
	//	--------------------------------------------------------------------------------
	public function KreirajKorisnik($data) {
		if (Korisnik::DaliPostoi($data[1])) throw new Exception("Корисникот веќе постои"); 
		
		KonektirajDB();
		
		$data[12] = md5($data[1].date("F j, Y, g:i a"));
		foreach ($data as $key => $vrednost) $data[$key] = mysql_real_escape_string($vrednost);
		
		$QUERY = "INSERT INTO korisnici (email, ime, prezime, grad, lozinka, validacija, pol ) 
		VALUES ( '{$data[1]}', '{$data[2]}','{$data[3]}','{$data[4]}','{$data[10]}','{$data[12]}','{$data[13]}' )";
		
		mysql_query($QUERY);

		if (!mysql_affected_rows()) {
		    throw new Exception('Непозната грешка во базата');
		}
		
		$vrati['ok'] = 1;
		
		//	MAIL
		$conflen=$_SERVER['PHP_SELF']-16;
		$A=substr( $_SERVER['PHP_SELF'], 0 , $conflen);
		$host='http://'.$_SERVER['SERVER_NAME'].$A;
		
		$poraka = "<body style=\"margin: 10px;\"><div align=\"center\"><a href=\""
		.$host."\"><img src=\"".$host."include/mail/templejti/sliki/logo.jpg\" alt=\"Посетете не!\" /></a>";
		$poraka .= "<h1>Alset</h1></div>";
		
		$poraka .= "<p>Кликнете на линкот 'Активирај' за да го активирате вашиот профил и да ја валидирате емаил адресата.<p>";
		$poraka .= "<a href='".$host."?email=".$data[1]."&id=".$data[12]."'>Активирај</a>";
		
		$poraka .= "<p>Вие не сте се регистрирале на Alset? ";
		$poraka .= "<a href='".$host."?error=1^".$data[12]."'>Кликнете овде</a>";
		$poraka .= " и вашите информации ќе бидат избришани од Alset.</p>";
			
		$poraka .= "<p>If you didn't register at Alset ";
		$poraka .= "<a href='".$host."?error=1^".$data[12]."'>Click Here</a>";
		$poraka .= " and your email will be forgoten.</p></body>";
		
		$mail_res = pratiMail($data[1], "Alset", $poraka);
			
		if ($mail_res == "OK") {
			$vrati['mail'] = "<h2>Меилот за активација беше испратен до вашата пошта: " . $data[1] . "</h2>";
			$vrati['mail'].= "<p>Кликнете на линкот за активација во меилот за да ја завршите регистрацијата ";
			$vrati['mail'].= "на вашиот профил</p>";
		}
		else { 
			$greska_info = "Неможеше да се прати маил до: ".$data[1]."\nсо содржина: \n".$poraka;
			$QUERY = "INSERT INTO logovi ( tip, info ) VALUES ( 'Емаил', '$greska_info' )";
			mysql_query($QUERY);
			
			$vrati['mail']= "<h2>Меилот за активација неможеше да се испрати до вашата пошта: " . $data[1] . "</h2>";
			$vrati['mail'].="<p>Системот ќе проба да го испрати меилот повторно па проверете си го маилот ";
			$vrati['mail'].="подоцна за порака со линк за актвација од Alset.</p>";	
			$vrati['mail'].=$mail_res;
		}
		
		$vrati['mail'].="<p><em>Емаилот може да пристигне и во Junk или Spam папката од вашиот емаил,";
		$vrati['mail'].=" така да проверувајте ги и овие папки.</em></p>";
		$vrati['mail'].="<br /><h4>Благодариме на регистрацијата, </h4><h3><em>тим Alset</em></h3>";
		return $vrati;
	}
	//	--------------------------------------------------------------------------------
	public function ValidirajKorisnik($uid, $vid) {
		$mysql = KonektirajDB();
		
		$uid = mysql_real_escape_string($uid);
		
		if (!Korisnik::DaliPostoi($uid)) throw new Exception("Таков корисник не постои!"); 
			
		$QUERY = "SELECT validacija FROM `korisnici` WHERE email = '{$uid}'";
		$rezultat = mysql_query($QUERY);
		
		$row = mysql_fetch_assoc( $rezultat );
		
		if ($row['validacija'] == "OK")
			throw new Exception("Вашиот профил е веќе активен.");
		if ($row['validacija'] != $vid)
			throw new Exception("Грешка во линкот за валидација.");
		
		$QUERY = "UPDATE korisnici SET validacija='OK' WHERE email = '{$uid}'";
			
		$affected = mysql_query($QUERY);

		// Always check that result is not an error
		if ( !$affected ) {
		    throw new Exception('Непозната грешка во базата. Обидете се повторно подоцна.');
		}
		return true;
	}
	//	--------------------------------------------------------------------------------
}

?>