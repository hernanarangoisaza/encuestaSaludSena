<?php
// Check existence of id parameter before processing further
if(isset($_GET["idHorario"]) && !empty(trim($_GET["idHorario"]))){
    // Include config file
    require_once "../core/config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM horarios WHERE idHorario = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idHorario"]);

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
    <title>Ver Horario</title>
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
                        <h1>Horario - Visualizar</h1>
                    </div>
                        
                     <div class="form-group">
                        <label>Hora Inicial</label>
                        <input type="time" name="horaInicial" class="form-control" value="<?php echo $row['horaInicial']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Hora Final</label>
                        <input type="time" name="horaFinal" class="form-control" value="<?php echo $row['horaFinal']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nombre corto</label>
                        <input type="text" name="nombreCorto" class="form-control" value="<?php echo $row['nombreCorto']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Jornada</label>
                        <input type="text" name="jornada" class="form-control" 
                            value="<?php
                            if ($row['jornada'] == 1) { echo 'Mañana'; }
                            else if ($row['jornada'] == 2) { echo 'Tarde'; }
                            else if ($row['jornada'] == 3) { echo 'Noche'; }
                            else if ($row['jornada'] == 4) { echo 'Mixta mañana-tarde'; }
                            else if ($row['jornada'] == 5) { echo 'Mixta tarde-noche'; }
                            else if ($row['jornada'] == 6) { echo 'Completa'; }
                            else if ($row['jornada'] == -1) { echo 'Sin asignar'; }
                            else { echo 'Sin asignar'; }                                    
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
                    
                    <p><a href="horarios-index.php" class="btn btn-primary">Volver al listado</a></p>

                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="../js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="../js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>