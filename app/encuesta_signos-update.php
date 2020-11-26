<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$idAprendiz = "";
$fechaHoraDiligenciamiento = "";
$idSedeIngreso = "";
$idHorario = "";
$aceptacionConsideraciones = "";
$autorizacionTratamientoDatos = "";
$autorizacionIngreso = "";
$observacionAdicional = "";
$aceptacionRespuestaPositiva = "";
$estado = "";
$auditoria = "";

$idAprendiz_err = "";
$fechaHoraDiligenciamiento_err = "";
$idSedeIngreso_err = "";
$idHorario_err = "";
$aceptacionConsideraciones_err = "";
$autorizacionTratamientoDatos_err = "";
$autorizacionIngreso_err = "";
$observacionAdicional_err = "";
$aceptacionRespuestaPositiva_err = "";
$estado_err = "";
$auditoria_err = "";


// Processing form data when form is submitted
if(isset($_POST["idEncuesta"]) && !empty($_POST["idEncuesta"])){
    // Get hidden input value
    $idEncuesta = $_POST["idEncuesta"];

        // Prepare an update statement
        
        $idAprendiz = trim($_POST["idAprendiz"]);
		$fechaHoraDiligenciamiento = trim($_POST["fechaHoraDiligenciamiento"]);
		$idSedeIngreso = trim($_POST["idSedeIngreso"]);
		$idHorario = trim($_POST["idHorario"]);
		$aceptacionConsideraciones = trim($_POST["aceptacionConsideraciones"]);
		$autorizacionTratamientoDatos = trim($_POST["autorizacionTratamientoDatos"]);
		$autorizacionIngreso = trim($_POST["autorizacionIngreso"]);
		$observacionAdicional = trim($_POST["observacionAdicional"]);
		$aceptacionRespuestaPositiva = trim($_POST["aceptacionRespuestaPositiva"]);
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
          exit('Something weird happened');
        }
        $stmt = $pdo->prepare("UPDATE encuesta_signos SET idAprendiz=?,fechaHoraDiligenciamiento=?,idSedeIngreso=?,idHorario=?,aceptacionConsideraciones=?,autorizacionTratamientoDatos=?,autorizacionIngreso=?,observacionAdicional=?,aceptacionRespuestaPositiva=?,estado=?,auditoria=? WHERE idEncuesta=?");

        if(!$stmt->execute([ $idAprendiz,$fechaHoraDiligenciamiento,$idSedeIngreso,$idHorario,$aceptacionConsideraciones,$autorizacionTratamientoDatos,$autorizacionIngreso,$observacionAdicional,$aceptacionRespuestaPositiva,$estado,$auditoria,$idEncuesta  ])) {
                echo "Something went wrong. Please try again later.";
                header("location: error.php");
            } else{
                $stmt = null;
                header("location: encuesta_signos-read.php?id=$id");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idEncuesta"]) && !empty(trim($_GET["idEncuesta"]))){
        // Get URL parameter
        $idEncuesta =  trim($_GET["idEncuesta"]);

        // Prepare a select statement
        $sql = "SELECT * FROM encuesta_signos WHERE idEncuesta = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idEncuesta;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $idAprendiz = $row["idAprendiz"];
					$fechaHoraDiligenciamiento = $row["fechaHoraDiligenciamiento"];
					$idSedeIngreso = $row["idSedeIngreso"];
					$idHorario = $row["idHorario"];
					$aceptacionConsideraciones = $row["aceptacionConsideraciones"];
					$autorizacionTratamientoDatos = $row["autorizacionTratamientoDatos"];
					$autorizacionIngreso = $row["autorizacionIngreso"];
					$observacionAdicional = $row["observacionAdicional"];
					$aceptacionRespuestaPositiva = $row["aceptacionRespuestaPositiva"];
					$estado = $row["estado"];
					$auditoria = $row["auditoria"];
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Id Aprendiz</label>
                            <input type="number" name="idAprendiz" class="form-control" value="<?php echo $idAprendiz; ?>">
                            <span class="form-text"><?php echo $idAprendiz_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Fecha/Hora de diligenciamiento</label>
                            <input type="text" name="fechaHoraDiligenciamiento" class="form-control" value="<?php echo $fechaHoraDiligenciamiento; ?>">
                            <span class="form-text"><?php echo $fechaHoraDiligenciamiento_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Sede de ingreso</label>
                            <input type="number" name="idSedeIngreso" class="form-control" value="<?php echo $idSedeIngreso; ?>">
                            <span class="form-text"><?php echo $idSedeIngreso_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Horario</label>
                            <input type="number" name="idHorario" class="form-control" value="<?php echo $idHorario; ?>">
                            <span class="form-text"><?php echo $idHorario_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Aceptación de consideraciones</label>
                            <input type="text" name="aceptacionConsideraciones" class="form-control" value="<?php echo $aceptacionConsideraciones; ?>">
                            <span class="form-text"><?php echo $aceptacionConsideraciones_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Autorización de tratamiento de datos</label>
                            <select name="autorizacionTratamientoDatos" class="form-control" id="autorizacionTratamientoDatos">
						    <option value="0">0</option>
						    <option value="1">1</option>
						</select>
                            <span class="form-text"><?php echo $autorizacionTratamientoDatos_err; ?></span>
                            </div>
						<div class="form-group">
                            <label>Autorización de ingreso</label>
                            <select name="autorizacionIngreso" class="form-control" id="autorizacionIngreso">
						    <option value="0">0</option>
						    <option value="1">1</option>
						</select>
                            <span class="form-text"><?php echo $autorizacionIngreso_err; ?></span>
                            </div>
						<div class="form-group">
                            <label>Observaciones adicionales</label>
                            <textarea name="observacionAdicional" class="form-control"><?php echo $observacionAdicional ; ?></textarea>
                            <span class="form-text"><?php echo $observacionAdicional_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Aceptación de respuestas positivas</label>
                            <input type="number" name="aceptacionRespuestaPositiva" class="form-control" value="<?php echo $aceptacionRespuestaPositiva; ?>">
                            <span class="form-text"><?php echo $aceptacionRespuestaPositiva_err; ?></span>
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

                        <input type="hidden" name="idEncuesta" value="<?php echo $idEncuesta; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="encuesta_signos-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
