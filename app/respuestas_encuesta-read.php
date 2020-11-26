<?php
// Check existence of id parameter before processing further
if(isset($_GET["idRespuestaEncuesta"]) && !empty(trim($_GET["idRespuestaEncuesta"]))){
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM respuestas_encuesta WHERE idRespuestaEncuesta = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idRespuestaEncuesta"]);

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
            echo "Oops! Something went wrong. Please try again later.";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>
                        
                     <div class="form-group">
                        <label>Id Encuesta</label>
                        <p class="form-control-static"><?php echo $row["idEncuesta"]; ?></p>
                    </div><div class="form-group">
                        <label>Id Pregunta</label>
                        <p class="form-control-static"><?php echo $row["idPreguntaEncuesta"]; ?></p>
                    </div><div class="form-group">
                        <label>Respuesta</label>
                        <p class="form-control-static"><?php echo $row["respuestaSiNo"]; ?></p>
                    </div><div class="form-group">
                        <label>Estado del registro</label>
                        <p class="form-control-static"><?php echo $row["estado"]; ?></p>
                    </div><div class="form-group">
                        <label>Fecha/Hora de auditoría</label>
                        <p class="form-control-static"><?php echo $row["auditoria"]; ?></p>
                    </div>                    
                    
                    <p><a href="respuestas_encuesta-index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>