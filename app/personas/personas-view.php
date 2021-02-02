<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../core/menu.php");
    exit();    
}
?>

<?php
// Check existence of id parameter before processing further
if(isset($_GET["idPersona"]) && !empty(trim($_GET["idPersona"]))){

    // Include config file
    require_once "../core/config.php";

    // Prepare a select statement
    $sql = "SELECT PE.*, 
        TV.nombreLargoVinculacion AS 'nombreLargoVinculacion', 
        TI.nombreLargoIdentificacion AS 'nombreLargoIdentificacion',
        TG.nombreLargoGenero AS 'nombreLargoGenero',
        MN.municipio AS 'nombreMunicipio',
        DP.departamento AS 'nombreDepartamento',
        CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
        FF.codigoFichaFormacion AS 'codigoFichaFormacion'
        FROM personas PE
        LEFT JOIN tipos_vinculaciones_sena TV ON TV.idTipoVinculacion = PE.idTipoVinculacion
        LEFT JOIN tipos_identificacion TI ON TI.idTipoIdentificacion = PE.idTipoIdentificacion
        LEFT JOIN tipos_generos TG ON TG.idTipoGenero = PE.idTipoGenero
        LEFT JOIN municipios MN ON MN.idMunicipio = PE.idMunicipio
        LEFT JOIN departamentos DP ON DP.idDepartamento = PE.idDepartamento
        LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = PE.idCentroFormacion
        LEFT JOIN fichas_formacion FF ON FF.idFichaFormacion = PE.idFichaFormacion
        WHERE idPersona = ?";

    if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["idPersona"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmtPDO)){
            $resultPDO = mysqli_stmt_get_result($stmtPDO);

            if(mysqli_num_rows($resultPDO) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

                /* Retrieve individual field value
                {INDIVIDUAL_FIELDS}
                $name = $row['name"];
                $address = $row['address"];
                $salary = $row['salary"];
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

<div class="row w-100">

    <div class="col-md-12 mx-auto">

        <div class="page-header">
            <h1>Persona - Visualizar</h1>
        </div>

        <div class="form-group">
            <label>Tipo de vinculación</label>
            <input type="text" name="idTipoVinculacion" class="form-control" value="<?php echo $row['nombreLargoVinculacion']; ?>" readonly>
        </div>
        
        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" name="nombreCompleto" class="form-control" value="<?php echo $row['nombreCompleto']; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Tipo de identificación</label>
            <input type="text" name="idTipoIdentificacion" class="form-control" value="<?php echo $row['nombreLargoIdentificacion']; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Identificación</label>
            <input type="text" name="identificacion" class="form-control" value="<?php echo $row['identificacion']; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="text" name="email" class="form-control" value="<?php echo $row['email']; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Teléfono personal</label>
            <input type="text" name="telefonoPersonal" class="form-control" value="<?php echo $row['telefonoPersonal']; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Teléfono del acudiente</label>
            <input type="text" name="telefonoAcudiente" class="form-control" value="<?php echo $row['telefonoAcudiente']; ?>" readonly>
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
            <input type="text" name="direccionResidencia" class="form-control" value="<?php echo $row['direccionResidencia']; ?>" readonly>
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
            <label>Ficha de formación</label>
            <input type="text" name="idFichaFormacion" class="form-control" value="<?php echo $row['codigoFichaFormacion']; ?>" readonly>
        </div>

        <div class="form-group ocultar-columna">
            <label>Estado del registro</label>
            <input type="number" name="estado" class="form-control" value="<?php echo $row['estado']; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Fecha/Hora de auditoría</label>
            <input type="text" name="auditoria" class="form-control" value="<?php echo $row['auditoria']; ?>" readonly>
        </div>

        <?php
            $rutaRegresarA = $_SESSION["rutaRegresarA"];
            echo "<p><a href='$rutaRegresarA' class='btn btn-primary'>Cerrar</a></p>";
        ?>

    </div>
    
</div>

