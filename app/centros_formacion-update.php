<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nombreCorto = "";
$nombreLargo = "";
$direccion = "";
$idMunicipio = "";
$idDepartamento = "";
$telefono1 = "";
$telefono2 = "";
$emailContacto1 = "";
$emailContacto2 = "";
$estado = "";
$auditoria = "";

$nombreCorto_err = "";
$nombreLargo_err = "";
$direccion_err = "";
$idMunicipio_err = "";
$idDepartamento_err = "";
$telefono1_err = "";
$telefono2_err = "";
$emailContacto1_err = "";
$emailContacto2_err = "";
$estado_err = "";
$auditoria_err = "";


// Processing form data when form is submitted
if(isset($_POST["idCentroFormacion"]) && !empty($_POST["idCentroFormacion"])){
    // Get hidden input value
    $idCentroFormacion = $_POST["idCentroFormacion"];

        // Prepare an update statement
        
        $nombreCorto = trim($_POST["nombreCorto"]);
		$nombreLargo = trim($_POST["nombreLargo"]);
		$direccion = trim($_POST["direccion"]);
		$idMunicipio = trim($_POST["idMunicipio"]);
		$idDepartamento = trim($_POST["idDepartamento"]);
		$telefono1 = trim($_POST["telefono1"]);
		$telefono2 = trim($_POST["telefono2"]);
		$emailContacto1 = trim($_POST["emailContacto1"]);
		$emailContacto2 = trim($_POST["emailContacto2"]);
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
        $stmt = $pdo->prepare("UPDATE centros_formacion SET nombreCorto=?,nombreLargo=?,direccion=?,idMunicipio=?,idDepartamento=?,telefono1=?,telefono2=?,emailContacto1=?,emailContacto2=?,estado=?,auditoria=? WHERE idCentroFormacion=?");

        if(!$stmt->execute([ $nombreCorto,$nombreLargo,$direccion,$idMunicipio,$idDepartamento,$telefono1,$telefono2,$emailContacto1,$emailContacto2,$estado,$auditoria,$idCentroFormacion  ])) {
                echo "Algo falló. Por favor intente de nuevo.";
                header("location: error.php");
            } else{
                $stmt = null;
                header("location: centros_formacion-read.php?idCentroFormacion=$idCentroFormacion");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idCentroFormacion"]) && !empty(trim($_GET["idCentroFormacion"]))){
        // Get URL parameter
        $idCentroFormacion =  trim($_GET["idCentroFormacion"]);

        // Prepare a select statement
        $sql = "SELECT * FROM centros_formacion WHERE idCentroFormacion = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idCentroFormacion;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $nombreCorto = $row["nombreCorto"];
					$nombreLargo = $row["nombreLargo"];
					$direccion = $row["direccion"];
					$idMunicipio = $row["idMunicipio"];
					$idDepartamento = $row["idDepartamento"];
					$telefono1 = $row["telefono1"];
					$telefono2 = $row["telefono2"];
					$emailContacto1 = $row["emailContacto1"];
					$emailContacto2 = $row["emailContacto2"];
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
                            <label>Nombre corto</label>
                            <input type="text" name="nombreCorto" maxlength="50"class="form-control" value="<?php echo $nombreCorto; ?>">
                            <span class="form-text"><?php echo $nombreCorto_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Nombre del Centro</label>
                            <input type="text" name="nombreLargo" maxlength="50"class="form-control" value="<?php echo $nombreLargo; ?>">
                            <span class="form-text"><?php echo $nombreLargo_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Dirección</label>
                            <input type="text" name="direccion" maxlength="50"class="form-control" value="<?php echo $direccion; ?>">
                            <span class="form-text"><?php echo $direccion_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Municipio</label>
                            <input type="number" name="idMunicipio" class="form-control" value="<?php echo $idMunicipio; ?>">
                            <span class="form-text"><?php echo $idMunicipio_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Departamento</label>
                            <input type="number" name="idDepartamento" class="form-control" value="<?php echo $idDepartamento; ?>">
                            <span class="form-text"><?php echo $idDepartamento_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Teléfono #1</label>
                            <input type="text" name="telefono1" maxlength="50"class="form-control" value="<?php echo $telefono1; ?>">
                            <span class="form-text"><?php echo $telefono1_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Teléfono #2</label>
                            <input type="text" name="telefono2" maxlength="50"class="form-control" value="<?php echo $telefono2; ?>">
                            <span class="form-text"><?php echo $telefono2_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Correo electrónico #1</label>
                            <input type="text" name="emailContacto1" maxlength="50"class="form-control" value="<?php echo $emailContacto1; ?>">
                            <span class="form-text"><?php echo $emailContacto1_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Correo electrónico #2</label>
                            <input type="text" name="emailContacto2" maxlength="50"class="form-control" value="<?php echo $emailContacto2; ?>">
                            <span class="form-text"><?php echo $emailContacto2_err; ?></span>
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

                        <input type="hidden" name="idCentroFormacion" value="<?php echo $idCentroFormacion; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="centros_formacion-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
