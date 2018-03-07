<?php
ob_start();
require_once "./includes/ModelExperto.php";
require_once "./funciones/limpiarCadena.php";

$expertos = new ModelExperto();

$error = false;
$msgError = "";

$usuario = $password =$mail = $nombre= "";

if (isset($_POST['registro'])) {

	if (empty($_POST['usuario'])) {
		$msgError = "Formulario incompleto";
		$error = true;
	} else {
		$usuario = limpiarCadena($_POST['usuario']);
	}

	if (empty($_POST['password'])) {
		$msgError = "Formulario incompleto";
		$error = true;
	} else {
		$password = limpiarCadena($_POST['password']);
	}
  if (empty($_POST['nombre'])) {
		$msgError = "Formulario incompleto";
		$error = true;
	} else {
		$nombre = limpiarCadena($_POST['nombre']);
	}

	if (empty($_POST['mail'])) {
		$msgError = "Formulario incompleto";
		$error = true;
	} else {
		$mail = limpiarCadena($_POST['mail']);
	}
	if (!$error) {
    if($expertos->insExperto(array('nombre'=>$nombre,'usuario'=>$usuario, 'password'=>$password,'email'=>$mail))){

    };
	}
}

echo "<form method=\"post\" action=\"" . htmlspecialchars('./index.php?page=registroExpertos') . "\">
	  <div class=\"login container\">
		<h3>Registro</h3>";
		if ($error) { echo "<div class=\"alert alert-danger\" role=\"alert\">".$msgError."</div>"; }
echo "	 <div class=\"form-group\">
  			   <label>Usuario</label>
  			   <input type=\"text\" class=\"form-control\" name=\"usuario\" placeholder=\"Usuario\">
		     </div>
         <div class=\"form-group\">
            <label>Nombre</label>
           	<input type=\"text\" class=\"form-control\" name=\"nombre\" placeholder=\"Nombre\">
         </div>
         <div class=\"form-group\">
            <label>Correo</label>
            <input type=\"mail\" class=\"form-control\" name=\"mail\" placeholder=\"Mail\">
          </div>
		<div class=\"form-group\">
			<label>Contraseña</label>
			<input type=\"password\" class=\"form-control\" name=\"password\" placeholder=\"Contraseña\">
		</div>
		<input type=\"submit\" class=\"btn btn-primary\" name=\"registro\" value=\"registrar\"><br />

	</div>
	</form>";

ob_end_flush();
?>
