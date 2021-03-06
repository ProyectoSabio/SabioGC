<?php
if ($_SESSION['perfil'] != "admin")
	header("Location: ./index.php");
if (!isset($_SESSION['modfamilia'])) {
	$_SESSION['modfamilia'] = "";
}
 
require_once "./includes/ModelFamilia.php";
require_once "./funciones/limpiarCadena.php";

echo "<script src=\"./js/searchFam.js\"></script>";

$error = false;
$msgError = "";
$nuevafamilia = "";

if (!isset($_GET['familia'])) {
	$_GET['familia'] = "";
}

if (isset($_SESSION['msgError']) && isset($_GET['accion'])) {
	$msgError = $_SESSION['msgError'];
}

//Comprobamos la acción a realizar en la página de familias
if (isset($_GET['accion'])) {
	if ($_GET['accion'] == "editar") {
		$_SESSION['modfamilia'] = $_GET['familia'];
		$nuevafamilia = $_GET['familia'];
		$accion = "Editar familia";
		$btn = "editar";
	} else if ($_GET['accion'] == "eliminar") {
		$objfamilia = new Modelfamilia();
		$objfamilia->delfamilia($_GET['familia']);
		$objfamilia = null;
		header("Location: ./index.php?page=familias");
	} else if ($_GET['accion'] == "annadir") {
		$accion = "Añadir familia";
		$btn = "annadir";
	}
}

if (isset($_POST['annadir'])) {
	if (empty($_POST['familia'])) {
		$msgError = "Familia no válida.";
		$error = true;
	} else {
		$nuevafamilia = limpiarCadena($_POST['familia']);
		if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/",$nuevafamilia)) {
			$msgError = "Formato de familia no válido, sólo se permiten letras.";
			$error = true;
		}
	}
	if (!$error) {
		$objfamilia = new Modelfamilia();
		$objfamilia->insfamilia(array('familia'=>$nuevafamilia));
		$objfamilia = null;
		header("Location: ./index.php?page=familias");
	} else {
		$_SESSION['msgError'] = $msgError;
		header("Location: ./index.php?page=familias&accion=annadir");
	}
} else if (isset($_POST['editar'])) {
	if (empty($_POST['familia'])) {
		$msgError = "Familia no válida.";
		$error = true;
	} else {
		$nuevafamilia = limpiarCadena($_POST['familia']);
		if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/",$nuevafamilia)) {
			$msgError = "Formato de familia no válido, sólo se permiten letras.";
			$error = true;
		}
	}
	if (!$error) {
		$objfamilia = new Modelfamilia();
		$objfamilia->updfamilias($_SESSION['modfamilia'], array('familia'=>$nuevafamilia));
		$objfamilia = null;
		unset($_SESSION['modfamilia']);
		header("Location: ./index.php?page=familias");
	} else {
		$_SESSION['msgError'] = $msgError;
		header("Location: ./index.php?page=familias&familia=".$_SESSION['modfamilia']."&accion=editar");
	}
} else if (isset($_POST['cancelar'])) {
	unset($_SESSION['msgError']);
	unset($_SESSION['modfamilia']);
}

echo "<div class=\"container\">
		<p><span class=\"glyphicon glyphicon-user\"></span> Conectado al sistema como: ".$_SESSION['usuario'][0]['usuario']."</p>
	</div>";

if (!isset($_GET['accion'])) {
	echo "<div class=\"container\">
			<label>Buscador</label>
			<p><input type=\"text\" class=\"form form-control\" onkeyup=\"showHint(this.value)\" placeholder=\"Búsqueda\">
			<a href=\"./index.php?page=familias&accion=annadir\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-plus\"></span> Añadir familia</a></p>
		</div>";
	echo "<div class=\"container\">";
	echo "<h3>Familias</h3>";
	$familia = new Modelfamilia();
	$resultado = $familia->getfamilias();
	/*if (isset($_POST['buscar'])) {
		$texto = limpiarCadena($_POST['search']);
		$resultado = $familia->buscarfamilias($texto);
	} else {
		$resultado = $familia->getfamilias();
	}*/
	$familia = null;
	echo "<div id=\"txtHint\">";
	echo "<table class=\"table table-hover table-striped\">
			<th class=\"text-center\">Nombre</th><th colspan=\"2\" class=\"text-center\">Opciones</th>";
	foreach ($resultado as $key => $value) {
		echo "<tr>";
		echo "<td>".$value['familia']."</td><td><a href=\"./index.php?page=familias&familia=".$value['familia']."&accion=editar\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-pencil\"></span> Editar</a> <a href=\"./index.php?page=familias&familia=".$value['familia']."&accion=eliminar\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span> Eliminar</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</div>";
}

if (isset($_GET['accion']) && ($_GET['accion'] == "annadir" || $_GET['accion'] == "editar")) {
	echo "<form method=\"post\" action=\"" . htmlspecialchars('./index.php?page=familias') . "\">";
	echo "<div class=\"container\">";
	echo "<h3>" . $accion . "</h3>";
	echo "<p>
			<label>Familia</label>
			<input type=\"text\" class=\"form-control\" name=\"familia\" value=\"".$nuevafamilia."\" placeholder=\"Ej. Deportes\">
			<span class=\"error\">".$msgError."</span>
		  </p>";
	echo "<p>
			<input type=\"submit\" class=\"btn btn-primary\" name=\"".$btn."\" value=\"Aceptar\">
			<input type=\"submit\" class=\"btn btn-danger\" name=\"cancelar\" value=\"Cancelar\">
		</p>";
	echo "</div>";
	echo "</form>";
}
echo "</div>";