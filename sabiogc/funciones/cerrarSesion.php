<?php
	ob_start();
	session_start();
	unset($_SESSION);
	session_destroy();
	session_regenerate_id(true);
	header("Location: ../index.php");
	ob_end_flush();
