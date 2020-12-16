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
$telefonoAcudiente = "";
$fechaNacimiento = "";
$idTipoGenero = "";
$direccionResidencia = "";
$idMunicipio = "";
$idDepartamento = "";
$idCentroFormacion = "";
$idFichaFormacion = "";
$estado = "1";
$auditoria = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

$idTipoVinculacion = trim($_POST["idTipoVinculacion"]);
$nombreCompleto = trim($_POST["nombreCompleto"]);
$idTipoIdentificacion = trim($_POST["idTipoIdentificacion"]);
$identificacion = trim($_POST["identificacion"]);
$email = trim($_POST["email"]);
$telefonoPersonal = trim($_POST["telefonoPersonal"]);
$telefonoAcudiente = trim($_POST["telefonoAcudiente"]);
$fechaNacimiento = trim($_POST["fechaNacimiento"]);
$idTipoGenero = trim($_POST["idTipoGenero"]);
$direccionResidencia = trim($_POST["direccionResidencia"]);
$idMunicipio = trim($_POST["idMunicipio"]);
$idDepartamento = trim($_POST["idDepartamento"]);
$idCentroFormacion = trim($_POST["idCentroFormacion"]);
$idFichaFormacion = trim($_POST["idFichaFormacion"]);
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
  exit('Algo extraño sucedió'); //something a user can understand
}
$stmtPDO = $linkPDO->prepare("INSERT INTO personas (idTipoVinculacion,nombreCompleto,idTipoIdentificacion,identificacion,email,telefonoPersonal,telefonoAcudiente,fechaNacimiento,idTipoGenero,direccionResidencia,idMunicipio,idDepartamento,idCentroFormacion,idFichaFormacion,estado,auditoria) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"); 

if($stmtPDO->execute([ $idTipoVinculacion,$nombreCompleto,$idTipoIdentificacion,$identificacion,$email,$telefonoPersonal,$telefonoAcudiente,$fechaNacimiento,$idTipoGenero,$direccionResidencia,$idMunicipio,$idDepartamento,$idCentroFormacion,$idFichaFormacion,$estado,$auditoria  ])) {
       $stmtPDO = null;
        header("location: personas-index.php");
    } else{
        echo "Algo falló. Por favor intente de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Persona</title>
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
                        <h2>Persona - Crear</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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
                            <label>Teléfono del acudiente</label>
                            <input type="text" name="telefonoAcudiente" maxlength="50" class="form-control" value="<?php echo $telefonoAcudiente; ?>">
                            <span class="form-text"><?php echo $telefonoAcudiente_err; ?></span>
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
                            <label>Ficha de formación</label>
                            <?php
                                $sql_cb7 = "SELECT idFichaFormacion, codigoFichaFormacion FROM fichas_formacion ORDER BY codigoFichaFormacion";
                                $result_cb7 = mysqli_query($linkMYSQLI, $sql_cb7);
                                echo "<select name='idFichaFormacion' id='cb7' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb7)) {
                                    if ($idFichaFormacion != $row['idFichaFormacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idFichaFormacion'] . "'>" . $row['codigoFichaFormacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idFichaFormacion'] . "'>" . $row['codigoFichaFormacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idFichaFormacion_err; ?></span>
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

                        <input type="submit" class="btn btn-primary" value="Grabar">
                        <a href="personas-index.php" class="btn btn-secondary">Cancelar</a>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>