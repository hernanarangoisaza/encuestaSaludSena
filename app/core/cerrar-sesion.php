<?php

// remove all session variables
session_unset();

// destroy the session
session_destroy();

// Habilitar la página inicial
header("Location: ../index.php");

?>