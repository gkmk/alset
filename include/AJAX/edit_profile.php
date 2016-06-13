<?php
// verzija 0.1
session_start();

require_once "../db_conf.php";
include_once "korisnik.php";

$logmail = explode('^', $_SESSION['user']);

if ($_POST['user_mail'] != $logmail[1])	die("ХАК 100");

$SQL = "SELECT * FROM korisnici WHERE email='".$_POST['user_mail']."'";

KonektirajDB();
$res = mysql_query($SQL);
if (!$res) die("ХАК: 102 - Непостои таков корисник");

$out = mysql_fetch_array($res);

if (md5($_POST['staral']) != $out['lozinka']) die("Погрешна лозинка.");

if ($_POST['lozinka'] != $_POST['lozinka2']) die("Внесете ја новата лозинка во двете полинња.");
if ($_POST['lozinka']) $nova_lozinka=md5($_POST['lozinka']);


$dnr = $_POST['godina']."-".$_POST['mesec']."-".$_POST['den'];

//	pripremanje na SQL UPIT
	$SQL = "UPDATE korisnici SET ime='{$_POST['ime']}', prezime='{$_POST['prezime']}', grad='{$_POST['grad']}',  ";
	$SQL .= "telefon='{$_POST['telefon']}', data_na_raganje='{$dnr}', smetka='{$_POST['smetka']}',  ";
	$SQL .= "karticka='{$_POST['karticka']}', adresa='{$_POST['adresa']}', pol='{$_POST['pol']}' ";
if ($_POST['slika_pat']) {
	$SQL .= ", avatar='{$_POST['slika_pat']}' ";
	}
if ($nova_lozinka) {
	$SQL .= ", lozinka='{$nova_lozinka}' ";
	}
	$SQL .= "WHERE email='{$_POST['user_mail']}'";
	
$res = mysql_query($SQL);
if (!$res) die("Деси се некоја страшна грешка во упито: ".$SQL);

if (mysql_affected_rows() != -1) { 
	if (!$_POST['lozinka'])  $nova_lozinka=md5($_POST['staral']);

	Korisnik::LogirajGo($_POST['user_mail'], $nova_lozinka);
	echo "Вашите измени се сочувани.";
	
}
else echo "Имаше грешка при сочувуањето на податоците. ".mysql_error();

?>