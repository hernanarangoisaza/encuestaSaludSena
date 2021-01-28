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
if(isset($_GET["idPreguntaEncuesta"]) && !empty(trim($_GET["idPreguntaEncuesta"]))){
    // Include config file
    require_once "../core/config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM preguntas_encuesta WHERE idPreguntaEncuesta = ?";

    if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idPreguntaEncuesta"]);

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
    <title>Ver Pregunta Encuesta</title>
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
                        <h1>Pregunta Encuesta - Visualizar</h1>
                    </div>
                        
                    <div class="form-group">
                        <label>Pregunta</label>
                        <textarea name="textoPregunta" class="form-control" rows="5" readonly><?php echo $row['textoPregunta']; ?>
                        </textarea>
                    </div>

                    <div class="form-group ocultar-columna">
                        <label>Estado del registro</label>
                        <input type="number" name="estado" class="form-control" value="<?php echo $row['estado']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Fecha/Hora de auditoría</label>
                        <input type="text" name="auditoria" class="form-control" value="<?php echo $row['auditoria']; ?>" readonly>
                    </div>                    
                    
                    <p><a href="preguntas_encuesta-index.php" class="btn btn-primary">Cerrar</a></p>

                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha512-iceXjjbmB2rwoX93Ka6HAHP+B76IY1z0o3h+N1PeDtRSsyeetU3/0QKJqGyPJcX63zysNehggFwMC/bi7dvMig==" crossorigin="anonymous"></script>
</body>
</html>