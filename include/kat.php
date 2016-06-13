<?php
include "db_conf.php";

try {
	KonektirajDB();
} catch (Exception $E) {
	die($E->getMessage());
}

$kategorija = explode("^", $_GET["kat"]); 

if($_GET["kat"]){
$glavna_query = "SELECT * FROM pretprijatie WHERE ";

foreach ($kategorija as $vrednost)
	$glavna_query .= " tip_pret='$vrednost' OR ";
	
$glavna_query = substr($glavna_query, 0, strlen($glavna_query)-4);

$result = mysql_query($glavna_query);

	if (!mysql_num_rows($result)) { 
		echo "<h1>Нема објавено фирми од таков тип</h1>";
		exit; 
	}
	else {
		echo "<table border='1'>";
		$new_line=1;
		while ($row = mysql_fetch_assoc($result)) 
		{
			if ($new_line % 3 == 1) echo "<tr>";
			echo "<td style=\"background: url(".$row['slika'].") center no-repeat\" class=\"kako_link\" width=\"310\" ";
			echo "height=\"199\" onclick=\"vcitajFirma('{$row[user]}', 'cont_sodrzina');\">";
			
			$tmp_sql = "SELECT * FROM search WHERE ime='{$row['user']}'";
			$tmp_res = mysql_query($tmp_sql);
			$tmp_row = mysql_fetch_assoc($tmp_res);
			$tmp_info = $tmp_row['info'];
			if (strlen($tmp_info) > 128) {
				$i=120;
				while (substr($tmp_info, $i, 1) != " ") $i++;
				
				 $tmp_info = substr($tmp_info, 0, $i)."...";
			}
			
			echo "<pre class=\"dark-75 tenka-temna-linija kat_info\">".$row['ime_pret']."<br/>".$tmp_info."</pre></td>";
			if ($new_line % 3 == 3) echo "</tr>";
			$new_line++;
		}
		if ($new_line % 3 != 3) echo "</tr>";
		echo "</table>";
	}
}

$produkti=$_GET["input"];
if ($produkti){
$glavna_query = "SELECT * FROM produkti WHERE od_ime_pret='$produkti'";
$result = mysql_query($glavna_query);

	if (!mysql_num_rows($result)) { 
		echo "<h1>$produkti сеуште нема објавено никакви продукти</h1>"; 
		exit;
	}
	else {
		echo "<table border='1'>";
		while ($row = mysql_fetch_assoc($result)) 
		{
			echo "<tr><td background=\"".$row['slika']."\" class=\"kako_link\" width=\"310\" ";
			echo "height=\"199\" onclick=\"vcitajSodrzina('include/kat.php?detali={$row[tip_prod]}', 'cont_sodrzina');\">";
			
			$tmp_sql = "SELECT * FROM search WHERE ime='{$row['ID']}'";
			$tmp_res = mysql_query($tmp_sql);
			$tmp_row = mysql_fetch_assoc($tmp_res);
			$tmp_info = $tmp_row['info'];
			if (strlen($tmp_info) > 128) {
				$i=120;
				while (substr($tmp_info, $i, 1) != " ") $i++;
				
				 $tmp_info = substr($tmp_info, 0, $i)."...";
			}
			
			echo "<p class=\"dark-75 tenka-temna-linija kat_info\"><b>".$row['tip_prod']."</b><br/>".$tmp_info."</p>";
			echo "</td></tr>";
		}
		echo "</table>";
	}
}

$Detali=$_GET["detali"];
if ($Detali){
$glavna_query = "SELECT * FROM prod1 WHERE artikal='$Detali'";
$result = mysql_query($glavna_query);
	while ($row = mysql_fetch_assoc($result)) 
	{
		echo "<p> $row[artikal] od pretpirijatie  $row[od_ime_pret] so cena  $row[cena]  </p>";
	}
}

?>

<script>

$('td').hover(
	function () {
    	$(this).css('background-color', '#CCC');
		$(this).find('pre').slideDown();
  	}, 
 	function () {
    	$(this).css('background-color', 'transparent');
		$(this).find('pre').slideUp();
	}); 

</script>