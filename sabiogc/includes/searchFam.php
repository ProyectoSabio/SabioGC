<?php
require_once "ModelFamilia.php";

$familia = new ModelFamilia();
$q = $_REQUEST['q'];
if ($q == "") {
	$resultado = $familia->getFamilias();
} else {
	$resultado = $familia->buscarFamilias($q);
}
$familia = null;
if (!empty($resultado)) {
	echo "<table class=\"table table-hover table-striped\">
			<th class=\"text-center\">Nombre</th><th colspan=\"2\" class=\"text-center\">Opciones</th>";
	foreach ($resultado as $key => $value) {
		echo "<tr>";
		echo "<td>".$value['familia']."</td><td><a href=\"./index.php?page=familia&familia=".$value['familia']."&accion=editar\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-pencil\"></span> Editar</a> <a href=\"./index.php?page=familia&familia=".$value['familia']."&accion=eliminar\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span> Eliminar</a></td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "<br /><p>No existen familias que contengan la palabra <b>".$q."</b>.</p><br />";
}