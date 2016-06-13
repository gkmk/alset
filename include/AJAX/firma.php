<?php
/*
---------------- Klasa za menadziranje so firmite ---------------
Fajl: 		firma.php
verijza: 	1.0
-----------------------------------------------------------------
*/

class Firma {
	//	Funkcija za generiranje na slicni firmi vo profilot na gledanoto pretprijatie
	public function zemiSlicniFirmi($firma, $tip) {
		//	promenliva za drzenje na upitot
		$QUERY = "SELECT ime_pret, tip_pret, slika, user FROM pretprijatie WHERE tip_pret = '$tip' AND user <> '$firma'";
		//	konektiranje do MySQL
		KonektirajDB();
		//	Izvrsuvanje na upitot
		$res = @mysql_query($QUERY);
		//	proverka na greski
		if (!$res) throw new Exception(mysql_error());
		$niza = mysql_fetch_row( $res );
		if (!$niza) throw new Exception("Нема пронајдено слични фирми.");
		
		//	polnenje na niza od rezultati
		$out = $niza;
		while ($niza = mysql_fetch_row( $res )) $out = array_merge($out, $niza);
		
		//	vrakanje na rezultatot
		return $out;
	}
	//	--------------------------------------------------------------------------------
	
	//	funkcija za zemanje na produkti objaveni pod nekoa firma
	public function zemiProdukti($firma) {
		//	upit za vrakanje na produktite od posledniot do prviot
		$QUERY = "SELECT cena, tip_prod, slika FROM produkti WHERE od_ime_pret = '$firma' ORDER BY id DESC";
		KonektirajDB();
		$res = @mysql_query($QUERY);
		if (!$res) throw new Exception(mysql_error());
		$niza = mysql_fetch_row( $res );
		if (!$niza) throw new Exception("Сеуште нема објавено продукти.");
		
		$out = $niza;
		while ($niza = mysql_fetch_row( $res )) $out = array_merge($out, $niza);
		
		return $out;
	}
	//	--------------------------------------------------------------------------------
	
	//	funkcija za vrakanje na osnovni podatoci za generiranje na profil strana za firma
	public function Vcitaj($firma) {
		//	upit koj vadi informacii od dve tabeli: pretprijatie i search
		$QUERY = "SELECT pretprijatie.ime_pret, search.info, pretprijatie.slika, pretprijatie.user, pretprijatie.tip_pret ";
		$QUERY .= "FROM pretprijatie, search WHERE pretprijatie.user = '{$firma}' AND search.ime = '{$firma}'";
		KonektirajDB();
		$res = @mysql_query($QUERY);
		if (!$res) throw new Exception(mysql_error());
		$niza = mysql_fetch_row( $res );
		if (!$niza) throw new Exception("Нема такво претпријатие");
		
		return $niza;
	}
	//	--------------------------------------------------------------------------------
	
	//	brisenje na firma dokolku seuste ne e validirana (gresen email)
	public function Brisi($uid) {
		$KID='';
		$QUERY = "SELECT user FROM `pretprijatie` WHERE validacija = '{$uid}'";
		KonektirajDB();
		$res = mysql_query($QUERY);
		
		if (mysql_num_rows($res) == 0) throw new Exception("Не постои таква фирма во нашата база.");	
		
		$KID = mysql_fetch_assoc( $res );
		
		//	BRISI FIRMA
		$QUERY = "DELETE FROM `pretprijatie` WHERE validacija = '{$uid}'";
		if (!mysql_query($QUERY))throw new Exception("Грешка: " . mysql_real_escape_string(mysql_error()));
		
		//	BRISI KONTAKT
		$QUERY = mysql_real_escape_string("DELETE FROM `kontakt` WHERE KID = '{$KID['user']}'");
		if (!mysql_query($QUERY))throw new Exception("Грешка: " . mysql_real_escape_string(mysql_error()));
		
		//	BRISI SEARCH
		$QUERY = mysql_real_escape_string("DELETE FROM `search` WHERE ime = '{$KID['user']}'");
		if (!mysql_query($QUERY))throw new Exception("Грешка: " . mysql_real_escape_string(mysql_error()));
	}
	//	--------------------------------------------------------------------------------
	
	//	procedura na logiranje na firma vo sistemot
	public function LogirajGo($uid, $pass) {
		//	proverka dali voopsto postoi firma so takvo ime
		if (!Firma::DaliPostoi($uid)) throw new Exception("Не постои таква фирма");
		
		//	zemanje na detalite za firmata
		$QUERY = "SELECT * FROM `pretprijatie` WHERE user = '{$uid}'";
		KonektirajDB();
		$rezultat =	mysql_query($QUERY);
		
		$row = mysql_fetch_assoc( $rezultat );
		
		//	proverka na lozinka
		if ($row['lozinka'] != $pass) throw new Exception("Погрешна лозинка");
		//	proverka dali e validna
		if ($row['validacija'] != "OK") throw new Exception("Вашиот профил е неактивен.");
		//	proverka dali firmata e odobrena od administrator
		if ($row['odobreno'] != "1") throw new Exception("Вашиот профил сеуште не е одобрен.");
				
		//	cistenje na nepotrebni podatoci
		unset($row['odobreno']);
		unset($row['lozinka']);
		unset($row['validacija']);
		unset($row['user']);
		
		//	sozdavanje na niza od potrebni informacii
		$korisnik = implode('^', $row);
		
		//	zemanje na kontakt informaciite
		$QUERY = "SELECT * FROM `kontakt` WHERE KID = '{$uid}'";
		$rezultat =	mysql_query($QUERY);
		
		$row = mysql_fetch_assoc( $rezultat );
		
		unset($row['KID']);
		if (!$row['tel1']) $row['tel1']='Нема';
		
		//	dopolnuvanje na nizata
		$korisnik .= '^'.implode('^', $row);
		
		//	zemanje na info, opisot na firmata
		$QUERY = "SELECT info FROM `search` WHERE ime = '{$uid}'";
		$rezultat =	mysql_query($QUERY);
		
		$row = mysql_fetch_assoc( $rezultat );
		
		//	kreiranje na sesija za ponatamosno cuvanje na podatocite
		$_SESSION['user'] = "firma^".$korisnik."^".$row['info'];
			
		//	firmata e sega logirana vo sistemot
		return "OK";
	}
	//	--------------------------------------------------------------------------------
	
	//	proverka dali postoi nekoja firma spored korisnicko ime
	public function DaliPostoi($uid) {
		$QUERY = "SELECT * FROM `pretprijatie` WHERE user = '{$uid}'";		
		KonektirajDB();

		$rezultat =	mysql_query($QUERY);
		
		if (!$rezultat) throw new Exception("Грешка во базата ".mysql_error());
		
		return mysql_num_rows( $rezultat );
	}
	//	--------------------------------------------------------------------------------
	
	//	proverka dali e iskoristen email (potrebno pri registracija na novi firmi/korisnici
	public function MailExists($uid) {
		$QUERY = "SELECT * FROM `kontakt` WHERE email1 = '{$uid}' OR email2 = '{$uid}' ";		
		KonektirajDB();

		$rezultat =	mysql_query($QUERY);
		
		if (!$rezultat) throw new Exception("Грешка во базата ".mysql_error());
		
		return mysql_num_rows( $rezultat );
	}
	//	--------------------------------------------------------------------------------
	
	//	procedura za kreiranje na nova firma 
	public function Kreiraj($data) {
		if (Firma::DaliPostoi($data[2])) throw new Exception("Фирмата веќе постои"); 
		
		KonektirajDB();
		
		//	kreiranja na string za validacija preku email
		$data[5] = md5($data[1].date("F j, Y, g:i a"));
		
		//	cistenje na podatocite od formata
		foreach ($data as $key => $vrednost) $data[$key] = mysql_real_escape_string($vrednost);
		
		//1: email , 2: ime , 3: lozinka , 4: grad
		
		//	vnesuvanje na osnovni podatoci za firmata vo PRETPRIJATIE
		$QUERY = "INSERT INTO pretprijatie ( user, validacija, lozinka ) 
		VALUES ('{$data[2]}', {$data[5]}', '{$data[3]}' )";
		
		mysql_query($QUERY);

		if (!mysql_affected_rows()) {
		    throw new Exception('Непозната грешка во базата');
		}
		
		// vnesuvanje na nov zapis so osnovnite informacii vo KONTAKT
		$QUERY = "INSERT INTO kontakt ( email1, grad, KID ) VALUES ( '{$data[1]}', '{$data[4]}', '{$data[2]}' )";
		
		mysql_query($QUERY);

		if (!mysql_affected_rows()) {
		    throw new Exception('Непозната грешка во базата');
		}
		
		//	vnesuvanje na osnovni informacii potrebni za prebaruvacot
		$QUERY = "INSERT INTO search ( ime, tabela ) VALUES ( '{$data[2]}', 'pretprijatie' )";
		mysql_query($QUERY);
		if (!mysql_affected_rows()) {
		    throw new Exception('Непозната грешка во базата');
		}
		
		$vrati['ok'] = 1;
		
		//	MAIL
		//	zemanje na patot do glavnata skripta (potrebno za kreiranje na URL vo mail za validacija
		$conflen=$_SERVER['PHP_SELF']-16;
		$A=substr( $_SERVER['PHP_SELF'], 0 , $conflen);
		$host='http://'.$_SERVER['SERVER_NAME'].$A;
		
		//	kreiranje na poraka
		$poraka = "<div align=\"center\"><a href=\"".$host."\"><img src=\"".$host."include/mail/templejti/sliki/logo.jpg\" alt=\"Посетете не!\" /></a>";
		$poraka .= "<h1>Alset</h1></div>";
		
		$poraka .= "<p>Кликнете на линкот 'Активирај' за да го активирате вашиот профил и да ја валидирате емаил адресата.<p>";
		$poraka .= "<a href='".$host."?reged=".$data[2]."&id=".$data[5]."'>Активирај</a>";
		
		$poraka .= "<p>Вие не сте се регистрирале на Alset? ";
		$poraka .= "<a href='".$host."?error=0^".$data[5]."'>Кликнете овде</a>";
		$poraka .= " и вашите информации ќе бидат избришани од Alset.</p>";
			
		$poraka .= "<p>If you didn't register at Alset ";
		$poraka .= "<a href='".$host."?error=0^".$data[5]."'>Click Here</a>";
		$poraka .= " and your email will be forgoten.</p></body>";
		
		//	isprakanje na mail preku PHPMAILER (gotova open source klasa)
		$mail_res = pratiMail($data[1], "Alset", $poraka);
		
		//	generiranje na forma so informacii za toa dali bese se zavrseno uspesno
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
		
		$vrati['mail'] .= "<br /><p>Откако ќе ja потврдите вашата емаил адреса ние ќе го разгледаме вашето барање ";
		$vrati['mail'] .= "за регистрација на претпријатие и доколку вашите информации се точни ќе добиете ";
		$vrati['mail'] .= "повратен емаил од нас за целосна активација на вашата онлајн продавница.</p>";
		$vrati['mail'].="<p><em>Емаилот може да пристигне и во Junk или Spam папката од вашиот емаил,";
		$vrati['mail'].=" така да проверувајте ги и овие папки.</em></p>";
		$vrati['mail'] .= "<br /><h4>Благодариме на регистрацијата, </h4><h3><em>тим Alset</em></h3>";
		
		//	pecatenje na povratna informacija na korisnikot
		return $vrati;
	}
	//	--------------------------------------------------------------------------------
	
	//	procedura na validiranje na firmi
	public function ValidirajFirma($uid, $vid) {
		//	konektiranje do Mysql i zemanje na LINK
		$mysql = KonektirajDB();
		
		//	cistenje na informaciite
		$uid = mysql_real_escape_string($uid);
		
		if (!Firma::DaliPostoi($uid)) throw new Exception("Немаме регистрирано таква фирма!"); 
			
		//	zemanje na informacii za firmata
		$QUERY = "SELECT validacija FROM `pretprijatie` WHERE user = '{$uid}'";
		$rezultat = mysql_query($QUERY);
		
		$row = mysql_fetch_assoc( $rezultat );
		
		if ($row['validacija'] == "OK")
			throw new Exception("Емаилот е веќе потврден.");
		if ($row['validacija'] != $vid)
			throw new Exception("Грешка во линкот за валидација.");
		
		//	momentalno e napraveno da se odobruvat site pretprijatia 
		//	izbrisi odobreno='1' za da mozat da se odobruvat racno samo od administrator 
		$QUERY = "UPDATE pretprijatie SET validacija='OK', odobreno='1' WHERE user = '{$uid}'";
			
		$affected = mysql_query($QUERY);
		if ( !$affected ) throw new Exception('Непозната грешка во базата. Обидете се повторно подоцна.');
		
		return true;
	}
	//	--------------------------------------------------------------------------------
}

?>