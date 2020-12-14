<?php

session_start();

// remove all session variables
session_unset();

// destroy the session
session_destroy();

// Habilitar la página inicial
header("Location: https://www.google.com/");

?>