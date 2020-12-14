<?php
require_once "../core/config.php";
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../index.php");
    exit();    
}
// foreach ($_SESSION as $key=>$val)
// echo $key." ".$val."<br/>";
// echo $_SESSION['permisosRolSistema'];
?>

<?php
if (!(strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '')) {
    header("Location: ../core/menu.php");
}
?>

<?php

// print_r($_POST);

/*

[idSedeIngreso] => 4 
[idHorario] => 7 
[aceptacionConsideraciones] => on 
[autorizacionTratamientoDatos] => 0 
[autorizacionIngreso] => 1 
[observacionAdicional] => mensaje 
[aceptacionRespuestapositiva] => 1 

[idPregunta_1] => 0 
[idPregunta_2] => 0 
[idPregunta_3] => 0 
[idPregunta_4] => 0 
[idPregunta_5] => 0 
[idPregunta_6] => 0 
[idPregunta_7] => 0 
[idPregunta_8] => 1 
[idPregunta_9] => 0 
[idPregunta_10] => 0 
[idPregunta_11] => 0 
[idPregunta_12] => 1 
[idPregunta_13] => 1 

*/

if($_SERVER["REQUEST_METHOD"] == "POST") {

	// print_r($_POST);

	$idPersona = trim($_SESSION["idPersona"]);
	$fechaHoraDiligenciamiento = date('Y-m-d H:i:s');
	$idSedeIngreso = trim($_POST["idSedeIngreso"]);
	$idHorario = trim($_POST["idHorario"]);
	$aceptacionConsideraciones = ($_POST["aceptacionConsideraciones"] == 'on') ? (1) : (0);
	$autorizacionTratamientoDatos = trim($_POST["autorizacionTratamientoDatos"]);
	$observacionAdicional = trim($_POST["observacionAdicional"]);
	$aceptacionRespuestaPositiva = trim($_POST["aceptacionRespuestaPositiva"]);
	$estado = 1;
	$auditoria = date('Y-m-d H:i:s');
	$autorizacionIngreso = 0;

	if(isset($_POST["aptoIngreso"])) {
		$autorizacionIngreso = trim($_POST["aptoIngreso"]);
	}	

	$dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
	$options = [
	  PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
	  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
	  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
	];
	try {
	  $linkPDO1 = new PDO($dsn, $db_user, $db_password, $options);
	} catch (Exception $e) {
	  error_log($e->getMessage());
	  exit('Algo extraño sucedió'); //something a user can understand
	}
	$stmt1 = $linkPDO1->prepare("INSERT INTO encuesta_signos (idPersona,fechaHoraDiligenciamiento,idSedeIngreso,idHorario,aceptacionConsideraciones,autorizacionTratamientoDatos,autorizacionIngreso,observacionAdicional,aceptacionRespuestaPositiva,estado,auditoria) VALUES (?,?,?,?,?,?,?,?,?,?,?)"); 

	if($stmt1->execute([ $idPersona,$fechaHoraDiligenciamiento,$idSedeIngreso,$idHorario,$aceptacionConsideraciones,$autorizacionTratamientoDatos,$autorizacionIngreso,$observacionAdicional,$aceptacionRespuestaPositiva,$estado,$auditoria  ])) {
			// Conservar el idEncuesta para la inserción de respuestas en la otra tabla.
			$idEncuesta = $linkPDO1->lastInsertId();
		    $stmt1 = null;
	    } else{
	        // URL doesn't contain valid id parameter. Redirect to error page
	        header("location: ../core/error.php");
	        exit();
	    }

	try {
	  $linkPDO2 = new PDO($dsn, $db_user, $db_password, $options);
	} catch (Exception $e) {
	  error_log($e->getMessage());
	  exit('Algo extraño sucedió'); //something a user can understand
	}

	$estado = 1;
	$auditoria = date('Y-m-d H:i:s');

	foreach ($_POST as $clave=>$valor) {

		// [idPregunta_9] => 0
		// 11 es la posición del simbolo _
		// $clave = idPregunta_1
		// $valor = 0
		// $idPreguntaEncuesta = 9

		if ((strstr($clave, "idPregunta_") != ''))
		{

			$idPreguntaEncuesta = substr($clave, 11);
			$respuestaSiNo = $valor;

		    $stmt2 = $linkPDO2->prepare("INSERT INTO respuestas_encuesta (idEncuesta, idPreguntaEncuesta, respuestaSiNo, estado, auditoria) VALUES (?,?,?,?,?)");
			if($stmt2->execute([ $idEncuesta, $idPreguntaEncuesta, $respuestaSiNo, $estado, $auditoria ])) {
		        $stmt2 = null;
		    } else{
		        // URL doesn't contain valid id parameter. Redirect to error page
		        header("location: ../core/error.php");
		        exit();
		    }

		}

	}

}

?>
