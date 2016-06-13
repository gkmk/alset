<?php
include "include/konfiguracija.php";

include "render/main.html";

include "include/procesirajGET.php";

//	GENERIRANJE FUTER
echo "<script type='text/javascript'>
//<![CDATA[
document.getElementById(\"footer\").innerHTML='<p>".COPY_RIGHT." ".SAJT_IME." ".VERZIJA.". Сите права задржани. <a href=\"http://validator.w3.org/check?uri=referer\" target=\"_new\">Валиден XHTML 1.0 Transitional<\/a><\/p>';
//]]>
</script>";
?>
</body>
</html>