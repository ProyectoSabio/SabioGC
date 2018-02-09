<?php
/** Desde este script el usuario puede solicitar la recuperación de contraseña*/
ob_start();
require "./includes/ModelExperto.php";
require_once "./funciones/limpiarCadena.php";

$usuarios = new ModelExperto();

$error = false;
$msgError = "";

$usuario = "";

if (isset($_POST['login'])) {

	if (empty($_POST['usuario'])) {
		$msgError = "Usuario no válido.";
		$error = true;
	} else {
		$usuario = limpiarCadena($_POST['usuario']);
	}

	if (!$error) {
		$usuario = $usuarios->getExperto($usuario);
		if (!$usuario) {
			$msgError = "El usuario introducido no existe.";
			$error = true;
		} else {
			if ($usuario[0][usuario] == "admin") {
				$msgError ="Admin, recupera tu clave en la bbdd";
			}else{
				actualizaToken($usuario);
				enviarClave($usuario);
			}
		}

		}
}
function actualizaToken($usuario){
	$experto = new ModelExperto();
	if($experto->updExpertoToken('literatura')){
		
	}else{
		echo 'Se ha producido un error';
	}


}
echo "<form method=\"post\" action=\"" . htmlspecialchars('./index.php?page=recuperaPassword') . "\">
	  <div class=\"login container\">
		<h3>Recuperar Contraseña</h3>";
		if ($error) { echo "<div class=\"alert alert-danger\" role=\"alert\">".$msgError."</div>"; }
echo "	<div class=\"form-group\">
			<label>Usuario</label>
			<input type=\"text\" class=\"form-control\" name=\"usuario\" placeholder=\"Usuario\">
		</div>
		<input type=\"submit\" class=\"btn btn-primary\" name=\"login\" value=\"Recuperar\">
		<a class=\"btn btn-primary\" href='./index.php'>Volver</a>
		<br />

	</div>
	</form>";

ob_end_flush();



?>
