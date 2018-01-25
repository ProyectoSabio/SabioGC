<?php
ob_start();
require_once "./includes/ModelUsuario.php";
require_once "./funciones/limpiarCadena.php";

$autentificacion = ModelUsuario::singleton();

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
		$resultado = $autentificacion->login($usuario, $password);
		if (!$resultado) {
			$msgError = "El usuario introducido no existe.";
			$error = true;
		} else {
			if ($resultado[0]['usuario'] == "admin") {
				$_SESSION['perfil'] = "admin";
			} else {
				$_SESSION['perfil'] = "experto";
			}
			$_SESSION['usuario'] = $resultado;
			header("Location: ./index.php?page=preguntas");
		}
	}
}

echo "<form method=\"post\" action=\"" . htmlspecialchars('./index.php?page=homepage') . "\">
	  <div class=\"login container\">
		<h3>Recuperar Contraseña</h3>";
		if ($error) { echo "<div class=\"alert alert-danger\" role=\"alert\">".$msgError."</div>"; }
echo "	<div class=\"form-group\">
			<label>Usuario</label>
			<input type=\"text\" class=\"form-control\" name=\"usuario\" placeholder=\"Usuario\">
		</div>
		<input type=\"submit\" class=\"btn btn-primary\" name=\"login\" value=\"Iniciar sesión\">
		<a class=\"btn btn-primary\" href='./index.php'>Volver</a>
		<br />

	</div>
	</form>";

ob_end_flush();
?>
