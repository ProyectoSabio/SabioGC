<?php
	include_once("Preguntas.php");

	$preguntas = new Preguntas();
	$esqueletoXML = simplexml_load_file("../config/config.xml");

	$arrayPreguntas = array();
	$listaCategorias = array();

	foreach ($esqueletoXML->config->categorias->categoria as  $value) {
	array_push($listaCategorias,$value);
}


	$result = $preguntas->generarCategoriasPreguntas("labordeta",$listaCategorias,$arrayPreguntas);

	print_r($result);

?>