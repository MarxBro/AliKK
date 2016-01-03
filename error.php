<!DOCTYPE html>
<?php
include("verif.inc");
session_unset();
session_destroy();
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Ups!</title>
	<style>
		body{
			background: #FFF;
			color:#F00;
			margin: 0px;
			width: 810px;
			height: 700px;
			font-family: "Arial", sans-serif;
		}
		div{
			height: 100%;
			width: 100%;
		}
		p{
			line-height: 700px;
			text-align: center;
		}
	</style>
</head>
<body>
	<div>
		<p>Ocurrió un error en el envío del formulario.</p>
	</div>
</body>
</html>
