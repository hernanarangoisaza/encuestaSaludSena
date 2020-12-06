<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nombreLargoRolSistema = "";
$nombreCorto = "";
$descripcionRolSistema = "";
$permisos = "";
$restricciones = "";
$estado = "";
$auditoria = "";

$nombreRol_err = "";
$nombreCorto_err = "";
$descripcionRolSistema_err = "";
$permisos_err = "";
$restricciones_err = "";
$estado_err = "";
$auditoria_err = "";


// Processing form data when form is submitted
if(isset($_POST["idRolSistema"]) && !empty($_POST["idRolSistema"])){
    // Get hidden input value
    $idRol = $_POST["idRolSistema"];

        // Prepare an update statement
        
        $nombreLargoRolSistema = trim($_POST["nombreLargoRolSistema"]);
		$nombreCorto = trim($_POST["nombreCorto"]);
		$descripcionRolSistema = trim($_POST["descripcionRolSistema"]);
		$permisos = trim($_POST["permisos"]);
		$restricciones = trim($_POST["restricciones"]);
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
        $stmt = $pdo->prepare("UPDATE roles_sistema SET nombreLargoRolSistema=?,nombreCorto=?,descripcionRolSistema=?,permisos=?,restricciones=?,estado=?,auditoria=? WHERE idRolSistema=?");

        if(!$stmt->execute([ $nombreLargoRolSistema,$nombreCorto,$descripcionRolSistema,$permisos,$restricciones,$estado,$auditoria,$idRol  ])) {
                echo "Algo falló. Por favor intente de nuevo.";
                header("location: error.php");
            } else{
                $stmt = null;
                header("location: roles_sistema-read.php?idRol=$idRol");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idRolSistema"]) && !empty(trim($_GET["idRolSistema"]))){
        // Get URL parameter
        $idRol =  trim($_GET["idRolSistema"]);

        // Prepare a select statement
        $sql = "SELECT * FROM roles_sistema WHERE idRolSistema = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idRol;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $nombreLargoRolSistema = $row["nombreLargoRolSistema"];
					$nombreCorto = $row["nombreCorto"];
					$descripcionRolSistema = $row["descripcionRolSistema"];
					$permisos = $row["permisos"];
					$restricciones = $row["restricciones"];
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="page-header">
                        <h2>Actualizar Registro</h2>
                    </div>
                    <p>Por favor ingrese nueva información para actualizar el registro.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombreLargoRolSistema" maxlength="50" class="form-control" value="<?php echo $nombreLargoRolSistema; ?>">
                            <span class="form-text"><?php echo $nombreRol_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Nombre corto</label>
                            <input type="text" name="nombreCorto" maxlength="50" class="form-control" value="<?php echo $nombreCorto; ?>">
                            <span class="form-text"><?php echo $nombreCorto_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcionRolSistema" class="form-control"><?php echo $descripcionRolSistema ; ?></textarea>
                            <span class="form-text"><?php echo $descripcionRolSistema_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Permisos</label>
                            <input type="text" name="permisos" maxlength="500" class="form-control" value="<?php echo $permisos; ?>">
                            <span class="form-text"><?php echo $permisos_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Restricciones</label>
                            <input type="text" name="restricciones" maxlength="500" class="form-control" value="<?php echo $restricciones; ?>">
                            <span class="form-text"><?php echo $restricciones_err; ?></span>
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

                        <input type="hidden" name="idRolSistema" value="<?php echo $idRol; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Grabar">
                        <a href="roles_sistema-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
