<?php
require_once "konfiguracija.php";
/*		GLAVEN PHP ZA FUNKCII	*/

function generirajTabela($div, $tabela, $index, $polinja, $iminja=0, $orderby=0, $ofset=0, $limit=10, $sort='DESC') {
	
	KonektirajDB();

	$SQLpolinja = implode(" , " , $polinja);
	
	//	VKUPNO PODATOCI
	$SQL = "SELECT COUNT(*) FROM $tabela";
	$rezultat = mysql_query($SQL);
	if (!$rezultat) return "Грешка: 1000. <br>Контактирајте не: <a href='mailto:admin@ugdphp.x10.mx'>admin@ugdphp.x10.mx</a>";
	$vkupno = mysql_fetch_row( $rezultat );
	$vkupno = $vkupno[0];
	
	//	IZVADI PODATOCI
	$prg_s = "/" . $index . "/"; 
	if (preg_match( $prg_s, $SQLpolinja)) $adder = $SQLpolinja;
	else  $adder = $index . ", " .$SQLpolinja;
	$SQL = "SELECT $adder FROM $tabela";
	if ($orderby) $SQL .= " ORDER BY $orderby $sort";
	$SQL .= " LIMIT $ofset , $limit";
	$rezultat = mysql_query($SQL);
	if (!$rezultat) return "Грешка: 1001. <br>Контактирајте не: <a href='mailto:admin@ugdphp.x10.mx'>admin@ugdphp.x10.mx</a>";
	
	if ($vkupno > 0) {
	//	GENERIRANJE NA JAVASCRIPT NIZI ZA AJAX UCITUVANJE
	$AJAX_REQ = "<script language='JavaScript'>\n";
	$AJAX_REQ .= "var phpPolinja = new Array(".json_encode( $polinja ).");\n";
	$AJAX_REQ .= "var phpIminja = new Array(".json_encode( $iminja ).");\n";
	$AJAX_REQ .= "</script>\n";

		
	//	GENERIRANJE NA TABELATA
	$AJAX_REQ .= "<table class='genTabela'><tr>";
	if (!$iminja) $iminja = $polinja;
	for ($i=0; $i<count($iminja); $i++)
		$AJAX_REQ .= "<th>$iminja[$i]</th>";
	$AJAX_REQ .=  "</tr>";
	
	$color=1;
	//	GENERIRANJE POLINJA VO TABELATA
	while ($row = mysql_fetch_assoc( $rezultat )) {
		if ($color % 2 == 1) 
			$AJAX_REQ .=  "<tr bgcolor='#DDDDDD'>";
		else 
			$AJAX_REQ .=  "<tr bgcolor='#FFFFFF'>";
		
		for ($i=0; $i<count($polinja); $i++)
			$AJAX_REQ .=  "<td>".generatorNaPoleVoTabela( $polinja[$i] ,$row[$polinja[$i]])."</td>";
			
		//	GENERIRANJE NA KOPCINJA (BRISI, IZMENI)
		$brisikod = "'DELETE FROM `{$tabela}` WHERE `{$index}` = ', '$row[$index]'";
		$brisi_link = "brisiOdTabela($brisikod)";
		$AJAX_REQ .=  "<th>".dodajIkona(0, $brisi_link)."</th></tr>";
		
	/*	$izmeni = "'UPDATE $tabela SET  WHERE $index = \'{$row[$index]}\''";
		$izmeni_link = "brisiOdTabela($brisikod)";
		$AJAX_REQ .= "<th>".dodajIkona(1, $brisi_link)."</th></tr>";*/
		
		$color++;
	}
	//	TEKST SO STRANICI
	$AJAX_REQ .=  "<tr><th colspan='3'>страна ".(($ofset/$limit)+1)." од ".ceil($vkupno/$limit)."</th></tr></table>";
	$AJAX_REQ .=  "<div id='navigacija pading15'>";
	//	GENERIRANJE NAVIGACIJA
	if ($ofset)  {
		$gen_naz = "generirajTabela('$div', '$tabela', '$index', phpPolinja, phpIminja, '$orderby', '".($ofset-$limit)."', '$limit', '$sort' )";
		$AJAX_REQ .=  "<div id='nav-nazad' class='namesti-levo'><a href='#' onclick=\"$gen_naz\">&lt;&lt; Назад</a></div>";	
	}
	if ($ofset+$limit < $vkupno) {
		$gen_pon = "generirajTabela('$div', '$tabela', '$index', phpPolinja, phpIminja, '$orderby', '".($ofset+$limit)."', '$limit', '$sort' )";
		$AJAX_REQ .=  "<div id='nav-napred' class='namesti-desno'><a href='#' onclick=\"$gen_pon\">Понатаму &gt;&gt;</a></div>";	
	}
	$AJAX_REQ .=  "<div class='cistac'></div></div>";
	
	$AJAX_REQ .=  "<script>$('th > #brisi').button({
					  text: false,
					  icons: {
					  primary: 'ui-icon-trash'
					  }
				  });
				  $('th > #izmeni').button({
					  text: false,
					  icons: {
					  primary: 'ui-icon-pencil'
					  }
				  });</script>";
	}
	else $AJAX_REQ = "Нема пронајдено резултати.";
	
	return $AJAX_REQ;
}
//	-----------------------------------------------------------------------------

function BrisiOdTabela($SQL, $Parm) {
	KonektirajDB();
	
	$SQL .= "'".$Parm."'";
	
	$res = mysql_query($SQL);
	
	if (mysql_affected_rows() != -1)
		return "Информацијата е избришана.";
	else throw new Exception("Информацијата не беше избришана.");
}
//	-----------------------------------------------------------------------------

function dodajIkona($ikona, $kod) {
	switch ($ikona) {
	case 0: // DODAJ BRISI IKONA
		$dodaj = "<button id='brisi' title='Избриши' onclick=\"$kod\">&nbsp;</button>";
		break;
	case 1: // DODAJ IZMENI IKONA
		$dodaj = "<button id='izmeni' title='Измени' onclick=\"$kod\">&nbsp;</button>";
		break;
	default: $dodaj = "";
	}

	
	return $dodaj;
}
//	-----------------------------------------------------------------------------

function generatorNaPoleVoTabela($pole, $data) {
	if ($pole == 'validacija') {
		if ($data != "OK") return 'Неактивен';
		else return 'Активен';
	}
	if ($pole == 'pol') {
		if ($data == 1) return 'Машко';
		else return 'Женско';
	}
	if ($pole == 'acc') {
		if ($data == 1) return 'Администратор';
		else return 'Обичен корисник';
	}
	if ($pole == 'email') {
		return "<a href='mailto:".$data."'>$data</a>";
	}
	if ($pole == 'odobreno') {
		if (!$data)	return "Не";
		else return "Да";
	}
	return $data;
}
//	-----------------------------------------------------------------------------

function pratiMail($do, $tema, $poraka, $od='no-reply@Alset.com') {
		
global $mail_conf;

	$mail             = new PHPMailer(); // defaults to using php "mail()"
	
	$mail->SMTPAuth   = $mail_conf['SMTPAuth'];         // enable SMTP authentication
	$mail->SMTPSecure = $mail_conf['SMTPSecure'];       // sets the prefix to the servier
	$mail->Host       = $mail_conf['Host'];      		// sets GMAIL as the SMTP server
	$mail->Port       = $mail_conf['Port'];             // set the SMTP port for the GMAIL server
	$mail->Username   = $mail_conf['Username'];  		// username
	$mail->Password   = $mail_conf['Password'];         // password
	$mail->Mailer 	  = $mail_conf['Mailer'];
	
	//$body             = file_get_contents('mail/templejti/main.html'); //ako se koriste HTML TEMPLATE

	$body             = $poraka;
	$body             = eregi_replace("[\]",'',$body);
	
	$mail->CharSet = 'utf-8';
	
	$mail->AddReplyTo("admin@ugdphp.x10.mx", "Администратор");
	
	$mail->SetFrom("admin@ugdphp.x10.mx", "Администратор");
	
	$address = $do;
	$mail->AddAddress($address, "");
	
	$mail->Subject    = $tema;
	
	$mail->AltBody    = "За да ја гледате поракава ви треба емаил клиент кој што подржува HTML пораки!"; // optional, comment out and test
	
	$mail->MsgHTML($body);
	
	//$mail->AddAttachment("mail/templejti/sliki/logo.jpg");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
	
	if(!@$mail->Send()) {
	  return @$mail->ErrorInfo;
	} else {
	  return 'OK';
	}
}
//	-----------------------------------------------------------------------------

?>