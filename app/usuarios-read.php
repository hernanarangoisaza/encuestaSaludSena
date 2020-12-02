<?php
// Check existence of id parameter before processing further
if(isset($_GET["idUsuario"]) && !empty(trim($_GET["idUsuario"]))){
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $sql = "SELECT US.*, 
        VS.nombreLargoVinculacion AS 'nombreLargoVinculacion', 
        TI.nombreLargoIdentificacion AS 'nombreLargoIdentificacion',
        TG.nombreLargoGenero AS 'nombreLargoGenero',
        MN.municipio AS 'nombreMunicipio',
        DP.departamento AS 'nombreDepartamento',
        CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
        RS.nombreLargoRolSistema AS 'nombreLargoRolSistema'
        FROM usuarios US
        LEFT JOIN tipos_vinculaciones_sena VS ON VS.idTipoVinculacion = US.idTipoVinculacion
        LEFT JOIN tipos_identificacion TI ON TI.idTipoIdentificacion = US.idTipoIdentificacion
        LEFT JOIN tipos_generos TG ON TG.idTipoGenero = US.idTipoGenero
        LEFT JOIN municipios MN ON MN.idMunicipio = US.idMunicipio
        LEFT JOIN departamentos DP ON DP.idDepartamento = US.idDepartamento
        LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = US.idCentroFormacion
        LEFT JOIN roles_sistema RS ON RS.idRolSistema = US.idRolSistema
        WHERE idUsuario = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idUsuario"]);

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
    <title>Ver Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="icon" href="imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">

                    <div class="page-header">
                        <h1>Usuario - Visualizar</h1>
                    </div>
                        
                    <div class="form-group">
                        <label>Tipo de vinculación</label>
                        <input type="text" name="idTipoVinculacion" class="form-control" value="<?php echo $row['nombreLargoVinculacion']; ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Nombre completo</label>
                        <input type="text" name="nombreCompleto" maxlength="50" class="form-control" value="<?php echo $row['nombreCompleto']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Tipo de identificación</label>
                        <input type="text" name="idTipoIdentificacion" class="form-control" value="<?php echo $row['nombreLargoIdentificacion']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Identificación</label>
                        <input type="text" name="identificacion" maxlength="50" class="form-control" value="<?php echo $row['identificacion']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input type="text" name="email" maxlength="50" class="form-control" value="<?php echo $row['email']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Teléfono personal</label>
                        <input type="text" name="telefonoPersonal" maxlength="50" class="form-control" value="<?php echo $row['telefonoPersonal']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fechaNacimiento" class="form-control" value="<?php echo $row['fechaNacimiento']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Género</label>
                        <input type="text" name="idTipoGenero" class="form-control" value="<?php echo $row['nombreLargoGenero']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Dirección de la residencia</label>
                        <input type="text" name="direccionResidencia" maxlength="50" class="form-control" value="<?php echo $row['direccionResidencia']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Departamento</label>
                        <input type="text" name="idDepartamento" class="form-control" value="<?php echo $row['nombreDepartamento']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Municipio</label>
                        <input type="text" name="idMunicipio" class="form-control" value="<?php echo $row['nombreMunicipio']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Centro de formación</label>
                        <input type="text" name="idCentroFormacion" class="form-control" value="<?php echo $row['nombreLargoCentroFormacion']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Rol del sistema</label>
                        <input type="text" name="idRolSistema" class="form-control" value="<?php echo $row['nombreLargoRolSistema']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Contraseña del sistema</label>
                        <input type="password" name="passwordSistema" class="form-control" value="<?php echo $row['passwordSistema']; ?>" readonly>
                    </div>

                    <div class="form-group ocultar-columna">
                        <label>Estado del registro</label>
                        <input type="number" name="estado" class="form-control" value="<?php echo $row['estado']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Fecha/Hora de auditoría</label>
                        <input type="text" name="auditoria" class="form-control" value="<?php echo $row['auditoria']; ?>" readonly>
                    </div>

                    <p><a href="usuarios-index.php" class="btn btn-primary">Regresar</a></p>

                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>