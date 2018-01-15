<?php
ob_start();
session_start();
if (!isset($_SESSION['perfil'])) {
	$_SESSION['perfil'] = "invitado";
}

include('includes/top_page.php');
include('includes/nav.php');
include('pages.php');
include('includes/footer.php');
ob_end_flush()
?>
