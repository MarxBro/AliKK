<?php

// Generar un token unico por encuesta a rellenar
session_start();
session_set_cookie_params(1800,'tmp','hipermegared.com.ar');

// ID de la cookie...
$length = 25;
$randomString = substr(str_shuffle(md5(time())),0,$length);

// Exportar los valores globales, usados en index, submit (validacion), gracias y error.
$_SESSION['secreto'] = $randomString;
global $_SESSION;

?>
