<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../core/menu.php");
    exit();    
}
?>

<?php
if (!strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
    header("Location: ../core/menu.php");
    exit();
}
?>

<?php
// Include config file
require_once "../core/config.php";

// Define variables and initialize with empty values
$nombreLargoRolSistema = "";
$nombreCorto = "";
$descripcionRolSistema = "";
$permisos = "";
$restricciones = "";
$estado = "";
$auditoria = "";

// Processing form data when form is submitted
if(isset($_POST["idRolSistema"]) && !empty($_POST["idRolSistema"])){
    // Get hidden input value
    $idRolSistema = $_POST["idRolSistema"];

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
      $linkPDO = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
      error_log($e->getMessage());
      exit('Algo extraño sucedió');
    }
   $stmtPDO = $linkPDO->prepare("UPDATE roles_sistema SET nombreLargoRolSistema=?,nombreCorto=?,descripcionRolSistema=?,permisos=?,restricciones=?,estado=?,auditoria=? WHERE idRolSistema=?");

    if(!$stmtPDO->execute([ $nombreLargoRolSistema,$nombreCorto,$descripcionRolSistema,$permisos,$restricciones,$estado,$auditoria,$idRolSistema  ])) {
            echo "Algo falló. Por favor intente de nuevo.";
            header("location: ../core/error.php");
        } else{
           $stmtPDO = null;
            // header("location: roles_sistema-read.php?idRolSistema=$idRolSistema");
            header("location: roles_sistema-index.php");
        }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idRolSistema"]) && !empty(trim($_GET["idRolSistema"]))){
        // Get URL parameter
        $idRolSistema =  trim($_GET["idRolSistema"]);

        // Prepare a select statement
        $sql = "SELECT * FROM roles_sistema WHERE idRolSistema = ?";
        if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

            // Set parameters
            $param_id = $idRolSistema;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmtPDO)){
                $resultPDO = mysqli_stmt_get_result($stmtPDO);

                if(mysqli_num_rows($resultPDO) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

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
                    header("location: ../core/error.php");
                    exit();
                }

            } else{
                echo "Oops! Algo falló. Por favor intente de nuevo.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmtPDO);

        // Close connection
        mysqli_close($linkMYSQLI);

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
    <title>Actualizar Rol del Sistema</title>
    <link rel="stylesheet" href="../css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="page-header">
                        <h2>Rol del Sistema - Actualizar</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombreLargoRolSistema" maxlength="75" class="form-control" value="<?php echo $nombreLargoRolSistema; ?>">
                            <span class="form-text"><?php echo $nombreRol_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Nombre corto</label>
                            <input type="text" name="nombreCorto" maxlength="50" class="form-control" value="<?php echo $nombreCorto; ?>">
                            <span class="form-text"><?php echo $nombreCorto_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcionRolSistema" class="form-control" rows="5"><?php echo $descripcionRolSistema; ?></textarea>
                            <span class="form-text"><?php echo $descripcionRolSistema_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Permisos</label>
                            <textarea name="permisos" class="form-control" rows="5"><?php echo $permisos; ?></textarea>
                            <span class="form-text"><?php echo $permisos_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Restricciones</label>
                            <textarea name="restricciones" class="form-control" rows="5"><?php echo $restricciones; ?></textarea>
                            <span class="form-text"><?php echo $restricciones_err; ?></span>
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

                        <input type="hidden" name="idRolSistema" value="<?php echo $idRolSistema; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="Grabar">
                            <a href="roles_sistema-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha512-iceXjjbmB2rwoX93Ka6HAHP+B76IY1z0o3h+N1PeDtRSsyeetU3/0QKJqGyPJcX63zysNehggFwMC/bi7dvMig==" crossorigin="anonymous"></script>
</body>
</html>
