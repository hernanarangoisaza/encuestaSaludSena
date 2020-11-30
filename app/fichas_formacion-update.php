<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$codigoFichaFormacion = "";
$idProgramaFormacion = "";
$estado = "";
$auditoria = "";

$codigoFichaFormacion_err = "";
$idProgramaFormacion_err = "";
$estado_err = "";
$auditoria_err = "";


// Processing form data when form is submitted
if(isset($_POST["idFichaFormacion"]) && !empty($_POST["idFichaFormacion"])){
    // Get hidden input value
    $idFichaFormacion = $_POST["idFichaFormacion"];

        // Prepare an update statement
        
        $codigoFichaFormacion = trim($_POST["codigoFichaFormacion"]);
		$idProgramaFormacion = trim($_POST["idProgramaFormacion"]);
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
        $stmt = $pdo->prepare("UPDATE fichas_formacion SET codigoFichaFormacion=?,idProgramaFormacion=?,estado=?,auditoria=? WHERE idFichaFormacion=?");

        if(!$stmt->execute([ $codigoFichaFormacion,$idProgramaFormacion,$estado,$auditoria,$idFichaFormacion  ])) {
                echo "Algo falló. Por favor intente de nuevo.";
                header("location: error.php");
            } else{
                $stmt = null;
                header("location: fichas_formacion-read.php?idFichaFormacion=$idFichaFormacion");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idFichaFormacion"]) && !empty(trim($_GET["idFichaFormacion"]))){
        // Get URL parameter
        $idFichaFormacion =  trim($_GET["idFichaFormacion"]);

        // Prepare a select statement
        $sql = "SELECT * FROM fichas_formacion WHERE idFichaFormacion = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idFichaFormacion;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $codigoFichaFormacion = $row["codigoFichaFormacion"];
					$idProgramaFormacion = $row["idProgramaFormacion"];
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
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Actualizar Registro</h2>
                    </div>
                    <p>Por favor ingrese nueva información para actualizar el registro.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Código</label>
                            <input type="text" name="codigoFichaFormacion" maxlength="50"class="form-control" value="<?php echo $codigoFichaFormacion; ?>">
                            <span class="form-text"><?php echo $codigoFichaFormacion_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Programa de formación</label>
                            <input type="number" name="idProgramaFormacion" class="form-control" value="<?php echo $idProgramaFormacion; ?>">
                            <span class="form-text"><?php echo $idProgramaFormacion_err; ?></span>
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

                        <input type="hidden" name="idFichaFormacion" value="<?php echo $idFichaFormacion; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="fichas_formacion-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
