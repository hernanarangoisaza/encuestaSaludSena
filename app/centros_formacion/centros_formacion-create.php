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
$nombreCorto = "";
$nombreLargoCentroFormacion = "";
$direccion = "";
$idMunicipio = "";
$idDepartamento = "";
$telefono1 = "";
$telefono2 = "";
$emailContacto1 = "";
$emailContacto2 = "";
$estado = "1";
$auditoria = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nombreCorto = trim($_POST["nombreCorto"]);
	$nombreLargoCentroFormacion = trim($_POST["nombreLargoCentroFormacion"]);
	$direccion = trim($_POST["direccion"]);
	$idMunicipio = trim($_POST["idMunicipio"]);
	$idDepartamento = trim($_POST["idDepartamento"]);
	$telefono1 = trim($_POST["telefono1"]);
	$telefono2 = trim($_POST["telefono2"]);
	$emailContacto1 = trim($_POST["emailContacto1"]);
	$emailContacto2 = trim($_POST["emailContacto2"]);
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
    $stmtPDO = $linkPDO->prepare("INSERT INTO centros_formacion (nombreCorto,nombreLargoCentroFormacion,direccion,idMunicipio,idDepartamento,telefono1,telefono2,emailContacto1,emailContacto2,estado,auditoria) VALUES (?,?,?,?,?,?,?,?,?,?,?)"); 
    
    if($stmtPDO->execute([ $nombreCorto,$nombreLargoCentroFormacion,$direccion,$idMunicipio,$idDepartamento,$telefono1,$telefono2,$emailContacto1,$emailContacto2,$estado,$auditoria  ])) {
           $stmtPDO = null;
            header("location: centros_formacion-index.php");
        } else{
            echo "Algo falló. Por favor intente de nuevo.";
        }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Centro de Formación</title>
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
                        <h2>Centro de Formación - Crear</h2>
                    </div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Nombre corto</label>
                            <input type="text" name="nombreCorto" maxlength="50" class="form-control" value="<?php echo $nombreCorto; ?>">
                            <span class="form-text"><?php echo $nombreCorto_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Nombre del Centro</label>
                            <input type="text" name="nombreLargoCentroFormacion" maxlength="75" class="form-control" value="<?php echo $nombreLargoCentroFormacion; ?>">
                            <span class="form-text"><?php echo $nombreLargoCentroFormacion_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Dirección</label>
                            <input type="text" name="direccion" maxlength="75" class="form-control" value="<?php echo $direccion; ?>">
                            <span class="form-text"><?php echo $direccion_err; ?></span>
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
                            <label>Teléfono #1</label>
                            <input type="text" name="telefono1" maxlength="50" class="form-control" value="<?php echo $telefono1; ?>">
                            <span class="form-text"><?php echo $telefono1_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Teléfono #2</label>
                            <input type="text" name="telefono2" maxlength="50" class="form-control" value="<?php echo $telefono2; ?>">
                            <span class="form-text"><?php echo $telefono2_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Correo electrónico #1</label>
                            <input type="text" name="emailContacto1" maxlength="75" class="form-control" value="<?php echo $emailContacto1; ?>">
                            <span class="form-text"><?php echo $emailContacto1_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Correo electrónico #2</label>
                            <input type="text" name="emailContacto2" maxlength="75" class="form-control" value="<?php echo $emailContacto2; ?>">
                            <span class="form-text"><?php echo $emailContacto2_err; ?></span>
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
                        <a href="centros_formacion-index.php" class="btn btn-secondary">Cancelar</a>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>