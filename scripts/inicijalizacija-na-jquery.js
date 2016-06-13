// Inicijalizacija na JQuery
$(function(){		
	InicijalizirajKopcinja();
		  
  //	Inicijalizacija na interfejsot
  $('#najava-kreiraj').hide();
    
  //	Dodavanje na akcii 
  $('#user_otkazi').bind('click', function() {
	prikaziKorisnickoMeni(false);
  });
  
  $('#top_levo').bind('click', function() {
	vcitajSodrzina('render/kategorii.html', 'cont_sodrzina');
  }); 
  
   $('#baraj').bind('click', function() {
	baraj();
  }); 
  
   $('#baraj_text').bind('keypress', function(event) {
  		if (event.keyCode == '13') {
     		baraj();
  		}
  }); 
  $('#baraj_text').bind('focus', function() {
     	$('#baraj_text').val('');
		$('#baraj_text').addClass('temen_text');
		$('#baraj_text').removeClass('svetol_text');
  }); 
  $('#baraj_text').bind('blur', function() {
		$('#baraj_text').addClass('svetol_text');
		$('#baraj_text').removeClass('temen_text');
  }); 
  
  
  $('#tema_select').change('select', function() {
	  $('#izgled').hide(); 
	  $('#bkg_load').show();
	  slika = "url(css/sliki/pozadina-"+$('#tema_select').val()+".jpg) fixed no-repeat top center";
	  var img = new Image();
	  img.src = "css/sliki/pozadina-"+$('#tema_select').val()+".jpg";
		img.onload = function() {
			$('body').css("background", slika);
			$('#bkg_load').hide();
			$('#izgled').show(); 
		}
  });

});

function InicijalizirajKopcinja() {
  //	Inicijalizijacija na kopcinjata
  $('button').hover(
	function() { $(this).addClass('ui-state-hover'); }, 
	function() { $(this).removeClass('ui-state-hover'); }
	).button();
}