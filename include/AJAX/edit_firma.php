<?php
// verzija 0.2
session_start();

require_once "../db_conf.php";
require_once "firma.php";

$logmail = explode('^', $_SESSION['user']);

if ($_POST['user_mail'] != $logmail[10])	die("ХАК 100");

$SQL = "SELECT * FROM pretprijatie WHERE user ='".$_POST['user']."'";

KonektirajDB();
$res = mysql_query($SQL);
if (!$res) die("ХАК: 102 - Непостои таквa фирма".$_POST['ime']);

$out = mysql_fetch_array($res);

if (md5($_POST['staral']) != $out['lozinka']) die("Погрешна лозинка.");

if ($_POST['lozinka'] != $_POST['lozinka2']) die("Внесете ја новата лозинка во двете полинња.");
if ($_POST['lozinka']) $nova_lozinka=md5($_POST['lozinka']);


$bio = mysql_real_escape_string($_POST['firma_info']);

//	dodavanje na informacii vo prebaruvacot- -----------------------------------------
//	pripremanje na SQL UPIT za search
$keywords = $_POST['tip'].' '.$_POST['ime'].' '.$_POST['user_mail'].' firma pretprijatie';
	$SQL = "UPDATE search SET info='{$bio}', tabela='pretprijatie', keyword='{$keywords}' ";
	$SQL .= "WHERE ime='{$_POST['user']}'";
//	pripremanje na SQL UPIT za search

$res = mysql_query($SQL);
if (!$res) die("Деси се некоја страшна грешка во упито: ".$SQL);
if (mysql_affected_rows() == -1) echo "Промената беше неуспешна. Ве молиме обидете се повторно.";
//	dodavanje na informacii vo prebaruvacot- -----------------------------------------



//	pripremanje na SQL UPIT za PRETRPIJATIE -----------------------------
	$SQL = "UPDATE pretprijatie SET danocen_br='{$_POST['danocen']}', ime_pret='{$_POST['ime']}', tip_pret='{$_POST['tip']}' ";
if ($_POST['slika_pat']) 	$SQL .= ", slika='{$_POST['slika_pat']}' ";
if ($nova_lozinka) 	$SQL .= ", lozinka='{$nova_lozinka}' ";
	$SQL .= "WHERE user='{$_POST['user']}'";
//	pripremanje na SQL UPIT za PRETRPIJATIE-------------------------

$res = mysql_query($SQL);
if (!$res) die("Деси се некоја страшна грешка во упито: ".$SQL);
if (mysql_affected_rows() == -1) echo "Промената беше неуспешна. Ве молиме обидете се повторно.";


//	pripremanje na SQL UPIT za KONTAKT-------------------------------
	$SQL = "UPDATE kontakt SET tel1='{$_POST['telefon']}', tel2='{$_POST['telefon2']}', ";
	$SQL .= "mob1='{$_POST['mobilen']}', mob2='{$_POST['mobilen2']}', mob3='{$_POST['mobilen3']}', ";
	$SQL .= "email2='{$_POST['mail2']}', grad='{$_POST['grad']}', ";
	$SQL .= "adresa1='{$_POST['adresa']}', adresa2='{$_POST['adresa2']}'";
	$SQL .= "WHERE KID='{$_POST['user']}'";
//	pripremanje na SQL UPIT za KONTAKT-----------------------

$res = mysql_query($SQL);
if (!$res) die("Деси се некоја страшна грешка во упито: ".$SQL);

if (mysql_affected_rows() != -1) {
	if (!$_POST['lozinka'])  $nova_lozinka=md5($_POST['staral']);

	Firma::LogirajGo($_POST['user'], $nova_lozinka);
	 echo "Вашите измени се сочувани.";
}
else echo "Имаше грешка при сочувуањето на податоците. ".mysql_error();
?>