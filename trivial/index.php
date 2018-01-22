<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400' rel='stylesheet' type='text/css'>
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">-->
	<link rel=stylesheet href="css/portada.css" type="text/css"/>
	<title>Configuracion</title>
</head>
<body>
	<?php
		require_once './class/Preguntas.php';
		$preguntas = new Preguntas();
		$familias = $preguntas->getFamilias();
	?>
	<div id="formJuegos" class="card">
		<h2>Juegos</h2>
			<?php
			foreach($familias as $familia){
				echo '<a class="juegoEnlace" href="./configuracionJuego.php?juego='.$familia['familia'].'" ><div class="games">'.$familia['familia'].'</div></a>';
			}
			?>			
					</div>