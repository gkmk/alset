<?php
require_once '../sess_check.php'; // PROVERKA NA VALIDNA SESIJA
require_once '../funkcii.php';

echo "<h1>Претпријатија</h1><hr />";

$polinja = array ( 'ime_pret' , 'tip_pret', 'validacija', 'odobreno' );
$iminja = array ( 'Име' , 'Тип', 'Емаил статус', 'Одобрено' );
echo "<div id='admin-firmi'>";
echo generirajTabela('admin-firmi', 'pretprijatie', 'ime_pret', $polinja, $iminja);
echo "</div>";

?>