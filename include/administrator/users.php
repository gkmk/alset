<?php
require_once '../sess_check.php'; // PROVERKA NA VALIDNA SESIJA
require_once '../funkcii.php';

echo "<h1>Корисници</h1><hr />";

$polinja = array ( 'email' , 'ime', 'prezime', 'validacija', 'pol', 'acc' );
$iminja = array ( 'Емаил' , 'Име', 'Презиме', 'Статус', 'Пол', 'Пристап' );
echo "<div id='admin-useri'>";
echo generirajTabela('admin-useri', 'korisnici', 'email', $polinja, $iminja);
echo "</div>";
?>