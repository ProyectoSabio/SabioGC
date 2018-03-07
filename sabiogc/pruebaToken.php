<?php
include './includes/ModelExperto.php';

$experto = new ModelExperto();
$prueba = $experto->updExpertoToken('literatura');
if(mail("pabloleonpsico@gmail.com","asunto","CONTENIDO")){
    echo 'enviado';
}else{
    echo 'no enviado';
}
?>
