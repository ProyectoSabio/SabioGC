<?php
ob_start();
require_once "./includes/ModelUsuario.php";
require_once "./funciones/limpiarCadena.php";

$autentificacion = ModelUsuario::singleton();

$error = false;
$msgError = "";

$usuario = $password = "";

if (isset($_POST['login'])) {

	if (empty($_POST['usuario'])) {
		$msgError = "Usuario y/o contraseña no válidos.";
		$error = true;
	} else {
		$usuario = limpiarCadena($_POST['usuario']);

	}

	if (empty($_POST['password'])) {
		$msgError = "Usuario y/o contraseña no válidos.";
		$error = true;
	} else {
		$password = limpiarCadena($_POST['password']);

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
		<h3>Iniciar sesión</h3>";
		if ($error) { echo "<div class=\"alert alert-danger\" role=\"alert\">".$msgError."</div>"; }
echo "	<div class=\"form-group\">
			<label>Usuario</label>
			<input type=\"text\" class=\"form-control\" name=\"usuario\" placeholder=\"Usuario\">
		</div>
		<div class=\"form-group\">
			<label>Contraseña</label>
			<input type=\"password\" class=\"form-control\" name=\"password\" placeholder=\"Contraseña\">
		</div>
		<input type=\"submit\" class=\"btn btn-primary\" name=\"login\" value=\"Iniciar sesión\"><br />
<a href='./index.php?page=registroExpertos'>Registro?</a> |
<a href='./index.php?page=recuperaPassword'>¿Olvidaste la contraseña?</a>
	</div>
	</form>";

ob_end_flush();
?>
