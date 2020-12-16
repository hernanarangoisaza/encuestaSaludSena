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
// Check existence of id parameter before processing further
if(isset($_GET["idRolSistema"]) && !empty(trim($_GET["idRolSistema"]))){
    // Include config file
    require_once "../core/config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM roles_sistema WHERE idRolSistema = ?";

    if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idRolSistema"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmtPDO)){
            $resultPDO = mysqli_stmt_get_result($stmtPDO);

            if(mysqli_num_rows($resultPDO) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

                /* Retrieve individual field value
                {INDIVIDUAL_FIELDS}
                $name = $row["name"];
                $address = $row["address"];
                $salary = $row["salary"];
                 */
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: ../core/error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Rol del Sistema</title>
    <link rel="stylesheet" href="../css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">

                    <div class="page-header">
                        <h1>Rol del Sistema - Visualizar</h1>
                    </div>
                        
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombreLargoRolSistema" class="form-control" value="<?php echo $row['nombreLargoRolSistema']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nombre corto</label>
                        <input type="text" name="nombreCorto" class="form-control" value="<?php echo $row['nombreCorto']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcionRolSistema" class="form-control" rows="5" readonly><?php echo $row['descripcionRolSistema']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Permisos</label>
                        <textarea name="permisos" class="form-control" rows="5" readonly><?php echo $row['permisos']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Restricciones</label>
                        <textarea name="restricciones" class="form-control" rows="5" readonly><?php echo $row['restricciones']; ?></textarea>
                    </div>

                    <div class="form-group ocultar-columna">
                        <label>Estado del registro</label>
                         <input type="number" name="estado" class="form-control" value="<?php echo $row['estado']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Fecha/Hora de auditoría</label>
                        <input type="text" name="auditoria" class="form-control" value="<?php echo $row['auditoria']; ?>" readonly>
                    </div>                    
                    
                    <p><a href="roles_sistema-index.php" class="btn btn-primary">Volver al listado</a></p>

                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>