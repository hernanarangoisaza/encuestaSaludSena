<?php
$db_server = 'localhost';
$db_user = 'root'; // ticcom_saludsena
$db_password = ''; // Sena100%
$db_name = 'sena'; // ticcom_senabd
$link = mysqli_connect($db_server, $db_user, $db_password, $db_name);
$link -> set_charset("utf8");
$no_of_records_per_page = 20;
?>
