<?php
// Check existence of id parameter before processing further
if(isset($_GET["idEncuesta"]) && !empty(trim($_GET["idEncuesta"]))){
    // Include config file
    require_once "../core/config.php";

    // Prepare a select statement
    $sql = "SELECT ES.*, 
        PE.nombreCompleto AS 'nombreCompleto', 
        CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
        HO.nombreCorto AS 'nombreCorto'
        FROM encuesta_signos ES
        LEFT JOIN personas PE ON PE.idPersona = ES.idPersona
        LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = ES.idSedeIngreso
        LEFT JOIN horarios HO ON HO.idHorario = ES.idHorario
        WHERE idEncuesta = ?";

    if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idEncuesta"]);

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
    <title>Ver Encuesta de Signos</title>
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
                        <h1>Encuesta de Signos - Visualizar</h1>
                    </div>
                        
                    <div class="form-group">
                        <label>Persona</label>
                        <input type="text" name="idPersona" class="form-control" value="<?php echo $row['nombreCompleto']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Fecha/Hora de diligenciamiento</label>
                        <input type="text" name="fechaHoraDiligenciamiento" class="form-control" value="<?php echo $row['fechaHoraDiligenciamiento']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Sede de ingreso</label>
                        <input type="text" name="idSedeIngreso" class="form-control" value="<?php echo $row['nombreLargoCentroFormacion']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Horario</label>
                        <input type="text" name="idHorario" class="form-control" value="<?php echo $row['nombreCorto']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Aceptación de consideraciones</label>
                        <input type="text" name="aceptacionConsideraciones" class="form-control" 
                            value="<?php
                                if ($row['aceptacionConsideraciones'] == 0) { echo 'No'; }
                                else if ($row['aceptacionConsideraciones'] == 1) { echo 'Si'; }
                                else if ($row['aceptacionConsideraciones'] == -1) { echo 'Sin asignar'; }
                            ?>
                            " readonly>
                    </div>

                    <div class="form-group">
                        <label>Autorización de tratamiento de datos</label>
                        <input type="text" name="autorizacionTratamientoDatos" class="form-control" 
                            value="<?php
                                if ($row['autorizacionTratamientoDatos'] == 0) { echo 'No'; }
                                else if ($row['autorizacionTratamientoDatos'] == 1) { echo 'Si'; }
                                else if ($row['autorizacionTratamientoDatos'] == -1) { echo 'Sin asignar'; }
                          ?>
                            " readonly>
                    </div>

                    <div class="form-group">
                        <label>Autorización de ingreso</label>
                        <input type="text" name="autorizacionIngreso" class="form-control" 
                            value="<?php
                                if ($row['autorizacionIngreso'] == 0) { echo 'No'; }
                                else if ($row['autorizacionIngreso'] == 1) { echo 'Si'; }
                                else if ($row['autorizacionIngreso'] == -1) { echo 'Sin asignar'; }
                            ?>
                            " readonly>
                    </div>

                    <div class="form-group">
                        <label>Observaciones adicionales</label>
                        <textarea name="observacionAdicional" class="form-control" rows="5" readonly><?php echo $row['observacionAdicional']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Aceptación de respuestas positivas</label>
                        <input type="text" name="aceptacionRespuestaPositiva" class="form-control" 
                            value="<?php
                                if ($row['aceptacionRespuestaPositiva'] == 0) { echo 'No'; }
                                else if ($row['aceptacionRespuestaPositiva'] == 1) { echo 'Si'; }
                                else if ($row['aceptacionRespuestaPositiva'] == -1) { echo 'Sin asignar'; }
                             ?>
                            " readonly>
                    </div>

                    <div class="form-group ocultar-columna">
                        <label>Estado del registro</label>
                        <input type="number" name="estado" class="form-control" value="<?php echo $row['estado']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Fecha/Hora de auditoría</label>
                        <input type="text" name="auditoria" class="form-control" value="<?php echo $row['auditoria']; ?>" readonly>
                    </div>                    
                    
                    <p><a href="encuesta_signos-index.php" class="btn btn-primary">Volver al listado</a></p>

                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>