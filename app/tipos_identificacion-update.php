<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nombreIdentificacion = "";
$nombreCorto = "";
$estado = "";
$auditoria = "";

$nombreIdentificacion_err = "";
$nombreCorto_err = "";
$estado_err = "";
$auditoria_err = "";


// Processing form data when form is submitted
if(isset($_POST["idTipoIdentificacion"]) && !empty($_POST["idTipoIdentificacion"])){
    // Get hidden input value
    $idTipoIdentificacion = $_POST["idTipoIdentificacion"];

        // Prepare an update statement
        
        $nombreIdentificacion = trim($_POST["nombreIdentificacion"]);
		$nombreCorto = trim($_POST["nombreCorto"]);
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
        $stmt = $pdo->prepare("UPDATE tipos_identificacion SET nombreIdentificacion=?,nombreCorto=?,estado=?,auditoria=? WHERE idTipoIdentificacion=?");

        if(!$stmt->execute([ $nombreIdentificacion,$nombreCorto,$estado,$auditoria,$idTipoIdentificacion  ])) {
                echo "Something went wrong. Please try again later.";
                header("location: error.php");
            } else{
                $stmt = null;
                header("location: tipos_identificacion-read.php?id=$id");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idTipoIdentificacion"]) && !empty(trim($_GET["idTipoIdentificacion"]))){
        // Get URL parameter
        $idTipoIdentificacion =  trim($_GET["idTipoIdentificacion"]);

        // Prepare a select statement
        $sql = "SELECT * FROM tipos_identificacion WHERE idTipoIdentificacion = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idTipoIdentificacion;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $nombreIdentificacion = $row["nombreIdentificacion"];
					$nombreCorto = $row["nombreCorto"];
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
                            <label>Nombre</label>
                            <input type="text" name="nombreIdentificacion" maxlength="50"class="form-control" value="<?php echo $nombreIdentificacion; ?>">
                            <span class="form-text"><?php echo $nombreIdentificacion_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Nombre corto</label>
                            <input type="text" name="nombreCorto" maxlength="50"class="form-control" value="<?php echo $nombreCorto; ?>">
                            <span class="form-text"><?php echo $nombreCorto_err; ?></span>
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

                        <input type="hidden" name="idTipoIdentificacion" value="<?php echo $idTipoIdentificacion; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="tipos_identificacion-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
