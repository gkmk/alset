<?php

//	KONFIGURACIJA NA DATABAZA I FUNKCIJA ZA KONEKTIRANJE DO NEA

define("DB_HOST", "localhost");
define("DB_USER", "");
define("DB_PASS", "");
define("DB_BAZA", "");

function KonektirajDB () {
	
	$link = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
	
	if (!$link) {
    throw new Exception("Проблеми со базата. Обидете се подоцна.");
	}
	if(!mysql_select_db( DB_BAZA ))
		throw new Exception(mysql_error());
	
		mysql_set_charset('utf8');

	return $link;
}

?>