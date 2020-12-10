<?php
// Include config file
require_once "../core/config.php";

// Define variables and initialize with empty values
$nombreLargoVinculacion = "";
$nombreCorto = "";
$estado = "";
$auditoria = "";

$nombreLargoVinculacion_err = "";
$nombreCorto_err = "";
$estado_err = "";
$auditoria_err = "";

// Processing form data when form is submitted
if(isset($_POST["idTipoVinculacion"]) && !empty($_POST["idTipoVinculacion"])){
    // Get hidden input value
    $idTipoVinculacion = $_POST["idTipoVinculacion"];

        // Prepare an update statement
        
        $nombreLargoVinculacion = trim($_POST["nombreLargoVinculacion"]);
		$nombreCorto = trim($_POST["nombreCorto"]);
		$estado = trim($_POST["estado"]);
		$auditoria = date('Y-m-d H:i:s');

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
        $stmt = $pdo->prepare("UPDATE tipos_vinculaciones_sena SET nombreLargoVinculacion=?,nombreCorto=?,estado=?,auditoria=? WHERE idTipoVinculacion=?");

        if(!$stmt->execute([ $nombreLargoVinculacion,$nombreCorto,$estado,$auditoria,$idTipoVinculacion  ])) {
                echo "Algo falló. Por favor intente de nuevo.";
                header("location: ../core/error.php");
            } else{
                $stmt = null;
                // header("location: vinculaciones_sena-read.php?idTipoVinculacion=$idTipoVinculacion");
                header("location: tipos_tipos_vinculaciones_sena-index.php");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idTipoVinculacion"]) && !empty(trim($_GET["idTipoVinculacion"]))){
        // Get URL parameter
        $idTipoVinculacion =  trim($_GET["idTipoVinculacion"]);

        // Prepare a select statement
        $sql = "SELECT * FROM tipos_vinculaciones_sena WHERE idTipoVinculacion = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idTipoVinculacion;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $nombreLargoVinculacion = $row["nombreLargoVinculacion"];
					$nombreCorto = $row["nombreCorto"];
					$estado = $row["estado"];
					$auditoria = $row["auditoria"];
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: ../core/error.php");
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
        header("location: ../core/error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Tipo de Vinculación al SENA</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">

                    <div class="page-header">
                        <h2>Tipo de Vinculación al SENA - Actualizar</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombreLargoVinculacion" maxlength="75" class="form-control" value="<?php echo $nombreLargoVinculacion; ?>">
                            <span class="form-text"><?php echo $nombreLargoVinculacion_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Nombre corto</label>
                            <input type="text" name="nombreCorto" maxlength="50" class="form-control" value="<?php echo $nombreCorto; ?>">
                            <span class="form-text"><?php echo $nombreCorto_err; ?></span>
                        </div>

						<div class="form-group ocultar-columna">
                            <label>Estado del registro</label>
                            <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                            <span class="form-text"><?php echo $estado_err; ?></span>
                        </div>

						<div class="form-group ocultar-columna">
                            <label>Fecha/Hora de auditoría</label>
                            <input type="text" name="auditoria" class="form-control" value="<?php echo $auditoria; ?>">
                            <span class="form-text"><?php echo $auditoria_err; ?></span>
                        </div>

                        <input type="hidden" name="idTipoVinculacion" value="<?php echo $idTipoVinculacion; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="Grabar">
                            <a href="tipos_vinculaciones_sena-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="../js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="../js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>