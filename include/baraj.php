<?php
//	Prebaruvac
require_once 'kirilica.php';

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
//	-------------------------------------------------------------------------------------------------------

function baraj($str, $vid=0) {
	$start = microtime_float();
	
	KonektirajDB();

  $str = @mysql_real_escape_string($str);
   
  $str .= " ". latinToMak($str);
  
  $str = explode(" ", $str);
  $str = implode("* ", $str);
	  
	$SQL = generirajSql($str, $vid);
   
  $rezultat = @mysql_query($SQL);
  
  if (!$rezultat || mysql_num_rows($rezultat) == 0) throw new Exception("<h1>Барањето не врати никакви резултати.</h1>");
  
/*  $out = "<div id='rezultati'>";
  
  $iminja=array('Име' , 'Информации');
  $out .= generirajTabela('rezultati', 0, 0, $iminja, 0, 0, 10, 'DESC', $rezultat);*/

  $out = "<div id='rezultati'>";
  $out .= "<table><tr><th>#</th><th>Име</th><th>Тип</th><th>Инфо</th></tr>";
  $counter=1; $color=1;
  while ($row = mysql_fetch_assoc($rezultat))
  	{
		if ($color == 1) { $out .=  "<tr bgcolor='#FFFFCC'>"; }
		else {
		if ($color % 2 == 1) 
			$out .=  "<tr bgcolor='#CCCCCC'>";
		else 
			$out .=  "<tr bgcolor='#FFFFFF'>";
		}
		$color++;	
		$out.="<td>{$counter}.</td>";
		if ($row['tabela'] == 'pretprijatie')	// Generiraj link do firmata
			$out.="<td><a href='#' onclick=\"vcitajFirma('{$row[user]}', 'cont_sodrzina');\">".$row['ime']."</a></td>";
		$out.="<td><pre>".$row['tip_pret']."</pre></td>";
		$out.="<td><pre>".$row['info']."</pre></td></tr>";
		$counter++;
	}
	
  $out.="</table></div>";
  
  $kraj = microtime_float();
	$time = $time_end - $time_start;
  $out.="<p>Пребарувањето траеше <em>".round(($kraj-$start), 5)."</em> секунди.";
  
  return $out;
}
//	-------------------------------------------------------------------------------------------------------

function generirajSql($str, $vid) {
	$SQL="";
	switch ($vid) {
		default : 
			//	NEMA IZBRANO STO DA SE PREBARUVA PA VRATI MU GI FIRMITE (DEFAULT SEARCH)
			$SQL = "SELECT pretprijatie.ime_pret AS ime, search.tabela, search.info, pretprijatie.user, pretprijatie.tip_pret,";
			$SQL .= " MATCH(search.keyword) AGAINST('$str' IN BOOLEAN MODE) AS vaznost";
			$SQL .= " FROM pretprijatie, search WHERE pretprijatie.user = search.ime AND ( MATCH(search.keyword) AGAINST('$str' IN BOOLEAN MODE) OR ";
			$SQL .= "MATCH(search.info) AGAINST('$str' IN BOOLEAN MODE)) ORDER BY vaznost DESC";
		break;
	}
	return $SQL;
}
//	-------------------------------------------------------------------------------------------------------

?>