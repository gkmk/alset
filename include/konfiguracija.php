<?php

include 'db_conf.php';

define("VERZIJA", "v0.4.0");
define("SAJT_IME", "Alset");
define("COPY_RIGHT", "&copy; 2010-2011");

$mail_conf = array (
	'SMTPAuth'   => true,                  // enable SMTP authentication
	'SMTPSecure' => "tls",                 // sets the prefix to the servier
	'Host'       => "smtp.gmail.com",      // sets GMAIL as the SMTP server
	'Port'       => 587,                   // set the SMTP port for the GMAIL server
	'Username'   => "",  // GMAIL username
	'Password'   => "",            // GMAIL password
	'Mailer' 	 => "smtp"	
);

?>