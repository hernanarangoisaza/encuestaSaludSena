<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$idEncuesta = "";
$fechaHoraTomaEntrada = "";
$temperaturaEntrada = "";
$fechaHoraTomaSalida = "";
$temperaturaSalida = "";
$estado = "";
$auditoria = "";

$idEncuesta_err = "";
$fechaHoraTomaEntrada_err = "";
$temperaturaEntrada_err = "";
$fechaHoraTomaSalida_err = "";
$temperaturaSalida_err = "";
$estado_err = "";
$auditoria_err = "";


// Processing form data when form is submitted
if(isset($_POST["idToma"]) && !empty($_POST["idToma"])){
    // Get hidden input value
    $idToma = $_POST["idToma"];

        // Prepare an update statement
        
        $idEncuesta = trim($_POST["idEncuesta"]);
		$fechaHoraTomaEntrada = trim($_POST["fechaHoraTomaEntrada"]);
		$temperaturaEntrada = trim($_POST["temperaturaEntrada"]);
		$fechaHoraTomaSalida = trim($_POST["fechaHoraTomaSalida"]);
		$temperaturaSalida = trim($_POST["temperaturaSalida"]);
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
        $stmt = $pdo->prepare("UPDATE tomas_temperatura SET idEncuesta=?,fechaHoraTomaEntrada=?,temperaturaEntrada=?,fechaHoraTomaSalida=?,temperaturaSalida=?,estado=?,auditoria=? WHERE idToma=?");

        if(!$stmt->execute([ $idEncuesta,$fechaHoraTomaEntrada,$temperaturaEntrada,$fechaHoraTomaSalida,$temperaturaSalida,$estado,$auditoria,$idToma  ])) {
                echo "Something went wrong. Please try again later.";
                header("location: error.php");
            } else{
                $stmt = null;
                header("location: tomas_temperatura-read.php?id=$id");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idToma"]) && !empty(trim($_GET["idToma"]))){
        // Get URL parameter
        $idToma =  trim($_GET["idToma"]);

        // Prepare a select statement
        $sql = "SELECT * FROM tomas_temperatura WHERE idToma = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idToma;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $idEncuesta = $row["idEncuesta"];
					$fechaHoraTomaEntrada = $row["fechaHoraTomaEntrada"];
					$temperaturaEntrada = $row["temperaturaEntrada"];
					$fechaHoraTomaSalida = $row["fechaHoraTomaSalida"];
					$temperaturaSalida = $row["temperaturaSalida"];
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
                            <label>Id Encuesta</label>
                            <input type="number" name="idEncuesta" class="form-control" value="<?php echo $idEncuesta; ?>">
                            <span class="form-text"><?php echo $idEncuesta_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Fecha/hora toma de entrada</label>
                            <input type="text" name="fechaHoraTomaEntrada" class="form-control" value="<?php echo $fechaHoraTomaEntrada; ?>">
                            <span class="form-text"><?php echo $fechaHoraTomaEntrada_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Temperatura a la entrada</label>
                            <input type="text" name="temperaturaEntrada" class="form-control" value="<?php echo $temperaturaEntrada; ?>">
                            <span class="form-text"><?php echo $temperaturaEntrada_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Fecha/hora toma de salida</label>
                            <input type="text" name="fechaHoraTomaSalida" class="form-control" value="<?php echo $fechaHoraTomaSalida; ?>">
                            <span class="form-text"><?php echo $fechaHoraTomaSalida_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Temperatura a la salida</label>
                            <input type="text" name="temperaturaSalida" class="form-control" value="<?php echo $temperaturaSalida; ?>">
                            <span class="form-text"><?php echo $temperaturaSalida_err; ?></span>
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

                        <input type="hidden" name="idToma" value="<?php echo $idToma; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="tomas_temperatura-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
