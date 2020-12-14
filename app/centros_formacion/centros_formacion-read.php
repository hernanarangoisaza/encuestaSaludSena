<?php
// Check existence of id parameter before processing further
if(isset($_GET["idCentroFormacion"]) && !empty(trim($_GET["idCentroFormacion"]))){
    // Include config file
    require_once "../core/config.php";

    // Prepare a select statement
    $sql = "SELECT CF.*, 
        MN.municipio AS 'nombreMunicipio',
        DP.departamento AS 'nombreDepartamento'
        FROM centros_formacion CF
        LEFT JOIN municipios MN ON MN.idMunicipio = CF.idMunicipio
        LEFT JOIN departamentos DP ON DP.idDepartamento = CF.idDepartamento
        WHERE idCentroFormacion = ?";

    if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idCentroFormacion"]);

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
    <title>Ver Centro de Formación</title>
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
                        <h1>Centro de Formación - Visualizar</h1>
                    </div>
                        
                    <div class="form-group">
                        <label>Nombre corto</label>
                        <input type="text" name="nombreCorto" class="form-control" value="<?php echo $row['nombreCorto']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nombre del Centro</label>
                        <input type="text" name="nombreLargoCentroFormacion" class="form-control" value="<?php echo $row['nombreLargoCentroFormacion']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?php echo $row['direccion']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Id Municipio</label>
                        <input type="text" name="idMunicipio" class="form-control" value="<?php echo $row['nombreMunicipio']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Id Departamento</label>
                        <input type="text" name="idDepartamento" class="form-control" value="<?php echo $row['nombreDepartamento']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Teléfono #1</label>
                        <input type="text" name="telefono1" class="form-control" value="<?php echo $row['telefono1']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Teléfono #2</label>
                        <input type="text" name="telefono2" class="form-control" value="<?php echo $row['telefono2']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Correo electrónico #1</label>
                        <input type="text" name="emailContacto1" class="form-control" value="<?php echo $row['emailContacto1']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Correo electrónico #2</label>
                        <input type="text" name="emailContacto2" class="form-control" value="<?php echo $row['emailContacto2']; ?>" readonly>
                    </div>

                    <div class="form-group ocultar-columna">
                        <label>Estado del registro</label>
                        <input type="number" name="estado" class="form-control" value="<?php echo $row['estado']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Fecha/Hora de auditoría</label>
                        <input type="text" name="auditoria" class="form-control" value="<?php echo $row['auditoria']; ?>" readonly>
                    </div>
                    
                    <p><a href="centros_formacion-index.php" class="btn btn-primary">Volver al listado</a></p>

                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>