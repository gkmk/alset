<?php
// verzija 0.1
require_once "../db_conf.php";

$firma= $_POST['firma'];
$cena = $_POST['cena'];
$tip  = $_POST['tip'];
$slika= $_POST['slika_pat'];

$SQL = "INSERT INTO produkti (od_ime_pret, cena, tip_prod, slika) VALUES ";
$SQL .= "( '{$firma}', '{$cena}', '{$tip}', '{$slika}' ) ";

KonektirajDB();

if (mysql_query($SQL))
	echo "Продуктот е успешно додаден.";
else echo "Имаше грешка. Продуктот не е додаден. Обидете се повторно.";

?>