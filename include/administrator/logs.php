<?php
require_once '../sess_check.php'; // PROVERKA NA VALIDNA SESIJA
require_once '../funkcii.php';

echo "<h1>Логови</h1><hr />";

$polinja = array ( 'tip' , 'info', 'data' );
$iminja = array ( 'Тип' , 'Информации', 'Дата/Време' );
echo "<div id='admin-logovi'>";
echo generirajTabela('admin-logovi', 'logovi', 'id', $polinja, $iminja, 'data');
echo "</div>";
?>