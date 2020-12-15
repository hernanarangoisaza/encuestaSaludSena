<?php

session_start();

if ($_SESSION['modoLogin'] == "normal") {
	$urlLogin = "../index.php";
} elseif ($_SESSION['modoLogin'] == "backend") {
	$urlLogin = "../backend.php";
}

// remove all session variables
session_unset();

// destroy the session
session_destroy();

// Habilitar la página inicial
header("Location: $urlLogin");

?>