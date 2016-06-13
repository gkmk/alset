<?php
require 'sess_check.php';
require 'funkcii.php';

$korisnik = explode('^', $_SESSION['user']);

if ($korisnik[12] == 1) 
	 generirajTabovi();
else die( "Пробуваш ли да ме хакираш? :D ц ц ц ц! Седи мирен другарче! :>");

function generirajTabovi() {
?>
<div id="admin_tabs">
			<ul>
				<li><a href="#admin_logs">Логови</a></li>
			  	<li><a href="#admin_users">Корисници</a></li>
				<li><a href="#admin_firmi">Претпријатија</a></li>
			  	<li><a href="#admin_produkti">Продукти</a></li>
			</ul>
			<div id="admin_logs"></div>
			<div id="admin_users"></div>
			<div id="admin_firmi"></div>
			<div id="admin_produkti"></div>
</div>

<script>
$('#admin_tabs').tabs();
$('#admin_logs').html("<img src='css/sliki/ajax-loader.gif' />");
$('#admin_tabs').tabs( "url" , 0 , 'include/administrator/logs.php' );
$('#admin_tabs').tabs( "url" , 1 , 'include/administrator/users.php' );
$('#admin_tabs').tabs( "url" , 2 , 'include/administrator/firmi.php' );
$('#admin_tabs').tabs( "url" , 3 , 'include/administrator/prod.php' );
$('#admin_tabs').tabs( "load" , 0 );

$('#admin_tabs').bind('tabsselect', function (event, ui) {
	$(ui.panel).html("<img src='css/sliki/ajax-loader.gif' />");
});

</script>

<?php
}
?>
