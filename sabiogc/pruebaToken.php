<?php
include './includes/ModelExperto.php';

$experto = new ModelExperto();
$prueba = $experto->updExpertoToken('literatura');
if(mail("pabloleonpsico@gmail.com","de clase modify virtualhost","he cambiado el webadmin")){
    echo 'enviado';
}else{
    echo 'no enviado';
}
?>
