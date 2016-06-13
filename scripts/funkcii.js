//	VERZIJA 1.3

//	KLASATA ZA KORISNIK ----------------------------------------
var UserProfil = {
	data : {
		email : '',
		ime : '',
		prezime : '',
		grad : '',
		telefon : '',
		dnr : '',
		smetka : '',
		karticka : '',
		adresa : '',
		slika : '',
		pol : '',
		acc : '',
		rezolucija  : true
	}
}
//	KLASA ZA PRETPRIJATIE
var FirmaProfil = {
	user	: '',
	ime 	: '',
	danocen : '',
	tip 	: '',
	slika	: '',
	tel1	: '',
	tel2	: '',
	mob1	: '',
	mob2	: '',
	mob3	: '',
	email	: '',
	email1	: '',
	adresa	: '',
	adresa1	: '',
	grad	: '',
	info	: ""
}

var FirmaView = {
		ime 	:	'',
		info 	:	'',
		slika 	: 	'',
		danocen :	'',
		tip		:	''
}

window.onresize = novaRezolicija;

window.onload = function() {
	novaRezolicija();
	
	$('#cont_sodrzina').load('render/kategorii.html');
}



//	FUNKCII ZA INTERFEJSOT ---------------------------------------------------
function novaNotifikacija(poraka, div) {
	if (!div) div="informacii";
	$('#'+div).html("<div class='ui-widget'><div class='ui-state-highlight ui-corner-all' style='margin: 5px 0; padding: 0 .7em;'><p><span class='ui-icon ui-icon-info' style='float: left; margin-right: .3em;'></span>"+poraka+"</p></div></div>").fadeIn(500).delay(poraka.length*100).fadeOut(1000);
}

function novaRezolicija()
{
	var dolzina = window.innerWidth;
	
	if (dolzina < 980) {
		if(UserProfil.data.rezolucija)
		$('#cont_sodrzina').html("<h1>Неподржана резолуција!</h1><h3>За да го гледате овој веб сајт користете резолуција од 1024x768 или поголема.</h3>");
		UserProfil.data.rezolucija=false;
	}
	else {
		if (!UserProfil.data.rezolucija) $('#cont_sodrzina').html("<h1>Резолуцијата е сега ОК!</h1><h3>Можете да ја гледате веб страната.</h3>");
		UserProfil.data.rezolucija=true;
	}
	
	if (dolzina < 1020) dolzina = "980px";
	else dolzina = "980px";
	
	$('#sredina').css('width', dolzina);
	$('#top_kontejner').css('width', dolzina);
	
	//snowStorm.flakeBottom = window.innerHeight-59; 
}

function prikaziKorisnickoMeni(funk) {
	if (funk)	{
		$.post("include/AJAX.php", { izvrsi: "daliELogiran" }, function(data) {
			if(data != 0) { 
				if (data.error) {
					$('#login').load('render/login.html', function() {	// ne e logiran pokazi forma za logiranje
						InicijalizirajKopcinja();
					});
				}
				else {
				if (data[0] == "korisnik")	{	// vcitaj podatoci za korisnik
				 //email 	ime 	prezime 	grad 	telefon 	data_na_raganje 	smetka 	karticka 	adresa 	lozinka 	avatar 	validacija 	pol 	acc
					UserProfil.data.email = data[1];
					UserProfil.data.ime = data[2];
					UserProfil.data.prezime = data[3];
					UserProfil.data.grad = data[4];
					UserProfil.data.telefon = data[5];
					UserProfil.data.dnr = data[6];
					UserProfil.data.smetka = data[7];
					UserProfil.data.karticka = data[8];
					UserProfil.data.adresa = data[9];
					UserProfil.data.slika = data[10];
					UserProfil.data.pol = data[11];
					UserProfil.data.acc = data[12];
					
					InicijalizirajKopcinja();
															
					if (data[12] == 1) {
						$('#login').load('render/admin_meni.html', function() {
							$('#user_id').text(' Добредојде '+data[2]+' ');
							InicijalizirajKopcinja();
						});
					}
					else {
						$('#login').load('render/user_meni.html', function() {
							$('#user_id').text(' Добредојде '+data[2]+' ');
							InicijalizirajKopcinja();
						});
					}
				}
				else {	// vcitaj podatoci za firma
					$('#login').load('render/firma_meni.html', function() {
					$('#firma_id').text(' Добредојде '+data[2]+' ');
					FirmaProfil.danocen = data[1];
					FirmaProfil.ime = data[2];
					FirmaProfil.tip = data[3];
					FirmaProfil.slika = data[4];
					FirmaProfil.tel1 = data[5];
					FirmaProfil.tel2 = data[6];
					FirmaProfil.mob1 = data[7];
					FirmaProfil.mob2 = data[8];
					FirmaProfil.mob3 = data[9];
					FirmaProfil.email = data[10];
					FirmaProfil.email1 = data[11];
					FirmaProfil.adresa = data[12];
					FirmaProfil.adresa1 = data[13];
					FirmaProfil.grad = data[14];

					InicijalizirajKopcinja();
				});
				}
				}
			}
			else $('#login').load('render/login.html', function() {	// ne e logiran pokazi forma za logiranje
				InicijalizirajKopcinja();
			});
			
			$('#korisnik').animate({  width: "972px"  }, 1500, "easeInOutCirc", function() {
				$('#najava-kreiraj').slideDown(function() {
					$('#najava-kreiraj').css("height", "30px");
					$('#user_meni').slideUp();
				});
			});
		},'json');	
	} else {
		$('#najava-kreiraj').slideUp( function() {
			$('#user_meni').slideDown();
			 $('#korisnik').animate({  width: "180px"  }, 1500);
		});
	}
}


//	AJAX FUNKCII -----------------------------------------------------

function LogirajGo() {
	var uid = $('#login_email').val();
	var login_pass = $('#login_pass').val();	
	var login = "LogirajGo";
	$('#login_form_error').html("<img src='css/sliki/ajax-loader-bel.gif' />");
	
	if (uid == '') { $('#login_form_error').text("Внесете корисничко име/email"); return false; }
	if (!isValidEmailAddress(uid)) { login = "LogirajFirma"; }
	
	if (login_pass.length < 5) { $('#login_form_error').text("Внесете валидна лозинка"); return false; }
	
	var poster = login+"^"+uid+"^"+$.md5(login_pass);
	$.post("include/AJAX.php", { izvrsi: poster }, function(data) {
		if (data.error)	$('#login_form_error').text(data.error);
		else {
			if (login == "LogirajFirma") 	FirmaProfil.user=uid;
			
			$('#najava-kreiraj').slideUp( function() {
			 $('#korisnik').animate({  width: "180px"  }, 1500, function() {
				 prikaziKorisnickoMeni(true);
				});
			});
		}
	},'json');	
}

function OdjaviMe() {
	var poster = "OdjaviMe^"+UserProfil.data.email;
	
	$.post("include/AJAX.php", { izvrsi: poster }, function(data) {
		if (data.error)	$('#user_profil_errors').text(data.error);
		else {
			UserProfil.data.email='';
			UserProfil.data.acc = '';
			FirmaProfil.user='';
			$('#user_profil_errors').text('Вие сте одлогирани! Ќе бидете префрлени на почетната страница.');
			setTimeout("window.location='./'",4000);
		}
	},'json');	
}

function baraj() {
	if ($('#baraj_text').val() == "Барајте..." || $('#baraj_text').val() == "") { 
		alert("Внесете текст за пребарување.");
		return 0;
	}
	$('#baraj_icon').show();
	var poster = "Baraj^"+$('#baraj_text').val();
	
	$.post("include/AJAX.php", { izvrsi: poster }, function(data) {
		if (data.error)	$('#cont_sodrzina').html(data.error);
		else {
			$('#cont_sodrzina').html('<h1>Резултати од вашето барање</h1><hr />'+data);
		}
		$('#baraj_icon').hide();
	},'json');	
}


//	AJAX ADMIN --------------------------------------------------------------
function generirajTabela( id, tabela, index, polinja, iminja, orderby, ofset, limit, Asort ) {
	
	$('#'+id).html("<img src='css/sliki/ajax-loader.gif' />");
	var poster = "GenerirajTabela^" + polinja + "^" + iminja;
	$.post("include/AJAX.php", { 
		izvrsi: poster,
		div: id,
		tab: tabela,
		ind: index,
		ord: orderby, 
		ofs: ofset,
		lim: limit,
		srt: Asort
	 }, function(data) {
		if (data.error)	novaNotifikacija(data.error);
		else {
			$('#'+id).html(data);
		}
	},'json');	
}

function brisiOdTabela(SQL, Parm) {
	confirmed = window.confirm("Дали навистина сакате да го избришете тој запис?");

	if (confirmed)
	{
		var poster = "BrisiOdTabela^" + SQL + "^" + Parm ;
		$.post("include/AJAX.php", { izvrsi: poster }, function(dataB) {
			if (dataB.error) novaNotifikacija(dataB.error);
			else {
				var Tab=$("#admin_tabs").tabs('option', 'selected');
				$('#admin_tabs').tabs( "load" , Tab );
				novaNotifikacija(dataB);
			}
		},'json');	
	}
}

function vcitajSodrzina(sodrzina, target, nazad) {
	$('#'+target).slideUp().html("<img src='css/sliki/ajax-loader.gif' />").slideDown();
	$('#'+target).load(sodrzina, function(response, status, xhr) {
			if (nazad) {
				var link_nazad = "<a href='#' onclick=\"vcitajSodrzina('"+nazad+"', '"+target+"')\">&lt; Назад </a>";
				$('#'+target).append(link_nazad);
			}
		});
}

function vcitajFirma(firma, target, nazad) {
	$('#'+target).slideUp().html("<img src='css/sliki/ajax-loader.gif' />").slideDown();
	
	$.post('include/AJAX.php', { izvrsi : "vcitajFirma^"+firma }, function (data) {
		if (data.error) { $('#'+target).text(data.error); }
		else {
		FirmaView.ime = data[0];
		FirmaView.info = data[1];
		FirmaView.slika = data[2];
		FirmaView.danocen = data[3];
		FirmaView.tip = data[4];
		$('#'+target).load("render/firma_profil.html", function(response, status, xhr) {
				if (nazad) {
					var link_nazad = "<a href='#' onclick=\"vcitajSodrzina('"+nazad+"', '"+target+"')\">&lt; Назад </a>";
					$('#'+target).append(link_nazad);
				}
			});
		}
	},"json");
}

//	FUNKCII ZA VALIDACII -----------------------------------------------------

function isValidEmailAddress(emailAddress) {
var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
return pattern.test(emailAddress);
}

function samoBukvi(text) {
var pattern = new RegExp("^([a-zA-Z\\u00A1-\\uFFFF])+$");
return pattern.test(text);
}