<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$idEncuesta = "";
$idPreguntaEncuesta = "";
$respuestaSiNo = "";
$estado = "";
$auditoria = "";

$idEncuesta_err = "";
$idPreguntaEncuesta_err = "";
$respuestaSiNo_err = "";
$estado_err = "";
$auditoria_err = "";


// Processing form data when form is submitted
if(isset($_POST["idRespuestaEncuesta"]) && !empty($_POST["idRespuestaEncuesta"])){
    // Get hidden input value
    $idRespuestaEncuesta = $_POST["idRespuestaEncuesta"];

        // Prepare an update statement
        
        $idEncuesta = trim($_POST["idEncuesta"]);
		$idPreguntaEncuesta = trim($_POST["idPreguntaEncuesta"]);
		$respuestaSiNo = trim($_POST["respuestaSiNo"]);
		$estado = trim($_POST["estado"]);
		$auditoria = trim($_POST["auditoria"]);
		

        $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
        $options = [
          PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        ];
        try {
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
        } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Algo extraño sucedió');
        }
        $stmt = $pdo->prepare("UPDATE respuestas_encuesta SET idEncuesta=?,idPreguntaEncuesta=?,respuestaSiNo=?,estado=?,auditoria=? WHERE idRespuestaEncuesta=?");

        if(!$stmt->execute([ $idEncuesta,$idPreguntaEncuesta,$respuestaSiNo,$estado,$auditoria,$idRespuestaEncuesta  ])) {
                echo "Algo falló. Por favor intente de nuevo.";
                header("location: error.php");
            } else{
                $stmt = null;
                header("location: respuestas_encuesta-read.php?idRespuestaEncuesta=$idRespuestaEncuesta");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idRespuestaEncuesta"]) && !empty(trim($_GET["idRespuestaEncuesta"]))){
        // Get URL parameter
        $idRespuestaEncuesta =  trim($_GET["idRespuestaEncuesta"]);

        // Prepare a select statement
        $sql = "SELECT * FROM respuestas_encuesta WHERE idRespuestaEncuesta = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idRespuestaEncuesta;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $idEncuesta = $row["idEncuesta"];
					$idPreguntaEncuesta = $row["idPreguntaEncuesta"];
					$respuestaSiNo = $row["respuestaSiNo"];
					$estado = $row["estado"];
					$auditoria = $row["auditoria"];
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Algo falló. Por favor intente de nuevo.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mx-auto">
                    <div class="page-header">
                        <h2>Actualizar Registro</h2>
                    </div>
                    <p>Por favor ingrese nueva información para actualizar el registro.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Id Encuesta</label>
                            <input type="number" name="idEncuesta" class="form-control" value="<?php echo $idEncuesta; ?>">
                            <span class="form-text"><?php echo $idEncuesta_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Pregunta</label>
                            <input type="number" name="idPreguntaEncuesta" class="form-control" value="<?php echo $idPreguntaEncuesta; ?>">
                            <span class="form-text"><?php echo $idPreguntaEncuesta_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Respuesta</label>
                            <input type="number" name="respuestaSiNo" class="form-control" value="<?php echo $respuestaSiNo; ?>">
                            <span class="form-text"><?php echo $respuestaSiNo_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Estado del registro</label>
                            <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                            <span class="form-text"><?php echo $estado_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Fecha/Hora de auditoría</label>
                            <input type="text" name="auditoria" class="form-control" value="<?php echo $auditoria; ?>">
                            <span class="form-text"><?php echo $auditoria_err; ?></span>
                        </div>

                        <input type="hidden" name="idRespuestaEncuesta" value="<?php echo $idRespuestaEncuesta; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="respuestas_encuesta-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
