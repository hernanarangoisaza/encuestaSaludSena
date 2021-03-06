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
$idTipoVinculacion = "";
$nombreCompleto = "";
$idTipoIdentificacion = "";
$identificacion = "";
$email = "";
$telefonoPersonal = "";
$fechaNacimiento = "";
$idTipoGenero = "";
$direccionResidencia = "";
$idMunicipio = "";
$idDepartamento = "";
$idCentroFormacion = "";
$idRolSistema = "";
$passwordSistema = "";
$estado = "";
$auditoria = "";

// Processing form data when form is submitted
if(isset($_POST["idUsuario"]) && !empty($_POST["idUsuario"])){
    // Get hidden input value
    $idUsuario = $_POST["idUsuario"];

    // Prepare an update statement
    
    $idTipoVinculacion = trim($_POST["idTipoVinculacion"]);
	$nombreCompleto = trim($_POST["nombreCompleto"]);
	$idTipoIdentificacion = trim($_POST["idTipoIdentificacion"]);
	$identificacion = trim($_POST["identificacion"]);
	$email = trim($_POST["email"]);
	$telefonoPersonal = trim($_POST["telefonoPersonal"]);
	$fechaNacimiento = trim($_POST["fechaNacimiento"]);
	$idTipoGenero = trim($_POST["idTipoGenero"]);
	$direccionResidencia = trim($_POST["direccionResidencia"]);
	$idMunicipio = trim($_POST["idMunicipio"]);
	$idDepartamento = trim($_POST["idDepartamento"]);
	$idCentroFormacion = trim($_POST["idCentroFormacion"]);
	$idRolSistema = trim($_POST["idRolSistema"]);
    // $passwordSistema = trim($_POST["passwordSistema"]);
    $passwordSistema=password_hash($_POST["passwordSistema"], PASSWORD_DEFAULT);
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
   $stmtPDO = $linkPDO->prepare("UPDATE usuarios SET idTipoVinculacion=?,nombreCompleto=?,idTipoIdentificacion=?,identificacion=?,email=?,telefonoPersonal=?,fechaNacimiento=?,idTipoGenero=?,direccionResidencia=?,idMunicipio=?,idDepartamento=?,idCentroFormacion=?,idRolSistema=?,passwordSistema=?,estado=?,auditoria=? WHERE idUsuario=?");

    if(!$stmtPDO->execute([ $idTipoVinculacion,$nombreCompleto,$idTipoIdentificacion,$identificacion,$email,$telefonoPersonal,$fechaNacimiento,$idTipoGenero,$direccionResidencia,$idMunicipio,$idDepartamento,$idCentroFormacion,$idRolSistema,$passwordSistema,$estado,$auditoria,$idUsuario  ])) {
            echo "Algo falló. Por favor intente de nuevo.";
            header("location: ../core/error.php");
        } else{
           $stmtPDO = null;
            // header("location: usuarios-read.php?idUsuario=$idUsuario");
            header("location: usuarios-index.php");
        }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idUsuario"]) && !empty(trim($_GET["idUsuario"]))){
        // Get URL parameter
        $idUsuario =  trim($_GET["idUsuario"]);

        // Prepare a select statement
        $sql = "SELECT * FROM usuarios WHERE idUsuario = ?";
        if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

            // Set parameters
            $param_id = $idUsuario;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmtPDO)){
                $resultPDO = mysqli_stmt_get_result($stmtPDO);

                if(mysqli_num_rows($resultPDO) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $idTipoVinculacion = $row["idTipoVinculacion"];
					$nombreCompleto = $row["nombreCompleto"];
					$idTipoIdentificacion = $row["idTipoIdentificacion"];
					$identificacion = $row["identificacion"];
					$email = $row["email"];
					$telefonoPersonal = $row["telefonoPersonal"];
					$fechaNacimiento = $row["fechaNacimiento"];
					$idTipoGenero = $row["idTipoGenero"];
					$direccionResidencia = $row["direccionResidencia"];
					$idMunicipio = $row["idMunicipio"];
					$idDepartamento = $row["idDepartamento"];
					$idCentroFormacion = $row["idCentroFormacion"];
					$idRolSistema = $row["idRolSistema"];
					$passwordSistema = $row["passwordSistema"];
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
        // mysqli_close($linkMYSQLI);

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
    <title>Actualizar Usuario del Sistema</title>
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
                        <h2>Usuario del Sistema - Actualizar</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                       <div class="form-group">
                            <label>Tipo de vinculación</label>
                            <?php
                                $sql_cb1 = "SELECT idTipoVinculacion, nombreLargoVinculacion, nombreCorto FROM tipos_vinculaciones_sena ORDER BY nombreLargoVinculacion";
                                $result_cb1 = mysqli_query($linkMYSQLI, $sql_cb1);
                                echo "<select name='idTipoVinculacion' id='cb1' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb1)) {
                                    if ($idTipoVinculacion != $row['idTipoVinculacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idTipoVinculacion'] . "'>" . $row['nombreLargoVinculacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idTipoVinculacion'] . "'>" . $row['nombreLargoVinculacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idTipoVinculacion_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Nombre completo</label>
                            <input type="text" name="nombreCompleto" maxlength="75" class="form-control" value="<?php echo $nombreCompleto; ?>">
                            <span class="form-text"><?php echo $nombreCompleto_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Tipo de identificación</label>
                            <?php
                                $sql_cb2 = "SELECT idTipoIdentificacion, nombreLargoIdentificacion, nombreCorto FROM tipos_identificacion";
                                $result_cb2 = mysqli_query($linkMYSQLI, $sql_cb2);
                                echo "<select name='idTipoIdentificacion' id='cb2' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb2)) {
                                    if ($idTipoIdentificacion != $row['idTipoIdentificacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idTipoIdentificacion'] . "'>" . $row['nombreLargoIdentificacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idTipoIdentificacion'] . "'>" . $row['nombreLargoIdentificacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idTipoIdentificacion_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Identificación</label>
                            <input type="text" name="identificacion" maxlength="50" class="form-control" value="<?php echo $identificacion; ?>">
                            <span class="form-text"><?php echo $identificacion_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Correo electrónico</label>
                            <input type="text" name="email" maxlength="75" class="form-control" value="<?php echo $email; ?>">
                            <span class="form-text"><?php echo $email_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Teléfono personal</label>
                            <input type="text" name="telefonoPersonal" maxlength="50" class="form-control" value="<?php echo $telefonoPersonal; ?>">
                            <span class="form-text"><?php echo $telefonoPersonal_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <input type="date" name="fechaNacimiento" class="form-control" value="<?php echo $fechaNacimiento; ?>">
                            <span class="form-text"><?php echo $fechaNacimiento_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Género</label>
                            <?php
                                $sql_cb3 = "SELECT idTipoGenero, nombreLargoGenero, nombreCorto FROM tipos_generos";
                                $result_cb3 = mysqli_query($linkMYSQLI, $sql_cb3);
                                echo "<select name='idTipoGenero' id='cb3' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb3)) {
                                    if ($idTipoGenero != $row['idTipoGenero'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idTipoGenero'] . "'>" . $row['nombreLargoGenero'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idTipoGenero'] . "'>" . $row['nombreLargoGenero'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idTipoGenero_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Dirección de la residencia</label>
                            <input type="text" name="direccionResidencia" maxlength="75" class="form-control" value="<?php echo $direccionResidencia; ?>">
                            <span class="form-text"><?php echo $direccionResidencia_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Departamento</label>
                            <?php
                                $sql_cb5 = "SELECT idDepartamento, departamento FROM departamentos ORDER BY departamento";
                                $result_cb5 = mysqli_query($linkMYSQLI, $sql_cb5);
                                echo "<select name='idDepartamento' id='cb5' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb5)) {
                                    if ($idDepartamento != $row['idDepartamento'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idDepartamento'] . "'>" . $row['departamento'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idDepartamento'] . "'>" . $row['departamento'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idDepartamento_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Municipio</label>
                            <?php
                                $sql_cb4 = "SELECT idMunicipio, municipio FROM municipios ORDER BY municipio";
                                $result_cb4 = mysqli_query($linkMYSQLI, $sql_cb4);
                                echo "<select name='idMunicipio' id='cb4' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb4)) {
                                    if ($idMunicipio != $row['idMunicipio'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idMunicipio'] . "'>" . $row['municipio'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idMunicipio'] . "'>" . $row['municipio'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idMunicipio_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Centro de formación</label>
                            <?php
                                $sql_cb6 = "SELECT idCentroFormacion, nombreLargoCentroFormacion, nombreCorto FROM centros_formacion ORDER BY nombreLargoCentroFormacion";
                                $result_cb6 = mysqli_query($linkMYSQLI, $sql_cb6);
                                echo "<select name='idCentroFormacion' id='cb6' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb6)) {
                                    if ($idCentroFormacion != $row['idCentroFormacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idCentroFormacion'] . "'>" . $row['nombreLargoCentroFormacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idCentroFormacion'] . "'>" . $row['nombreLargoCentroFormacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idCentroFormacion_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Rol del sistema</label>
                            <?php
                                $sql_cb6 = "SELECT idRolSistema, nombreLargoRolSistema, nombreCorto FROM roles_sistema ORDER BY nombreLargoRolSistema";
                                $result_cb6 = mysqli_query($linkMYSQLI, $sql_cb6);
                                echo "<select name='idRolSistema' id='cb6' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb6)) {
                                    if ($idRolSistema != $row['idRolSistema'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idRolSistema'] . "'>" . $row['nombreLargoRolSistema'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idRolSistema'] . "'>" . $row['nombreLargoRolSistema'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idRolSistema_err; ?></span>
                        </div>

						<div class="form-group ocultar-columna">
                            <label>Contraseña del sistema</label>
                            <input type="password" name="passwordSistema" maxlength="255" class="form-control" value="<?php echo $passwordSistema; ?>">
                            <span class="form-text"><?php echo $passwordSistema_err; ?></span>
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

                        <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="Grabar">
                            <a href="usuarios-index.php" class="btn btn-secondary">Cancelar</a>
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
