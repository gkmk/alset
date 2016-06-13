<?php
session_start();

if ($_POST['captchaSelection'] == $_SESSION['simpleCaptchaAnswer']) $test = "OK";
else $test = "NEOK";

echo json_encode($test);
?>

