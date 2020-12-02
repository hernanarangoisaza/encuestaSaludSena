<?php
// Check existence of id parameter before processing further
if(isset($_GET["idEncuesta"]) && !empty(trim($_GET["idEncuesta"]))){
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $sql = "SELECT ES.*, 
        AP.nombreCompleto AS 'nombreCompleto', 
        CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
        HO.nombreCorto AS 'nombreCorto'
        FROM encuesta_signos ES
        LEFT JOIN aprendices AP ON AP.idAprendiz = ES.idAprendiz
        LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = ES.idSedeIngreso
        LEFT JOIN horarios HO ON HO.idHorario = ES.idHorario
        WHERE idEncuesta = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idEncuesta"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                /* Retrieve individual field value
                {INDIVIDUAL_FIELDS}
                $name = $row["name"];
                $address = $row["address"];
                $salary = $row["salary"];
                 */
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Encuesta de Signos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="icon" href="imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    
                    <div class="page-header">
                        <h1>Encuesta de Signos - Visualizar</h1>
                    </div>
                        
                     <div class="form-group">
                        <label>Aprendiz</label>
                        <input type="text" name="idAprendiz" class="form-control" value="<?php echo $row['nombreCompleto']; ?>" readonly>
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
                        <input type="text" name="aceptacionConsideraciones" class="form-control" value="<?php echo $row['aceptacionConsideraciones']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Autorización de tratamiento de datos</label>
                        <input type="text" name="autorizacionTratamientoDatos" class="form-control" 
                            value="<?php
                                if ($row['autorizacionTratamientoDatos'] === 0) {
                                    echo 'No';
                                }
                                else if ($row['autorizacionTratamientoDatos'] === 1) {
                                    echo 'Si';
                                }
                                else if (!isset($row['autorizacionTratamientoDatos'])) {
                                    echo 'Sin responder';
                                }
                            ?>
                            " readonly>
                    </div>

                    <div class="form-group">
                        <label>Autorización de ingreso</label>
                        <input type="text" name="autorizacionIngreso" class="form-control" 
                            value="<?php
                                if ($row['autorizacionIngreso'] === 0) {
                                    echo 'No';
                                }
                                else if ($row['autorizacionIngreso'] === 1) {
                                    echo 'Si';
                                }
                                else if (!isset($row['autorizacionIngreso'])) {
                                    echo 'Sin responder';
                                }
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
                                if ($row['aceptacionRespuestaPositiva'] === 0) {
                                    echo 'No';
                                }
                                else if ($row['aceptacionRespuestaPositiva'] === 1) {
                                    echo 'Si';
                                }
                                else if (!isset($row['aceptacionRespuestaPositiva'])) {
                                    echo 'Sin responder';
                                }
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
                    
                    <p><a href="encuesta_signos-index.php" class="btn btn-primary">Regresar</a></p>

                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>