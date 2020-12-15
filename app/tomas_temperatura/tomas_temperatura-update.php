<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../index.php");
    exit();    
}
// foreach ($_SESSION as $key=>$val)
// echo $key." ".$val."<br/>";
// echo $_SESSION['permisosRolSistema'];
?>

<?php
// Include config file
require_once "../core/config.php";

// Define variables and initialize with empty values
$idEncuesta = "";
$fechaHoraTomaEntrada = "";
$temperaturaEntrada = "";
$fechaHoraTomaSalida = "";
$temperaturaSalida = "";
$idUsuario = "";
$estado = "1";
$auditoria = "";

if (isset($_GET['idEncuesta'])) {
    $idEncuesta = isset($_GET['idEncuesta']) ? $_GET['idEncuesta'] : '';
    $disabled = "xdisabled";
    $readonly1 = "solo-lectura";
} else {
    $idEncuesta = isset($_POST['idEncuesta']) ? $_POST['idEncuesta'] : '';
    $disabled = "";
    $readonly1 = "";
}

// Processing form data when form is submitted
if(isset($_POST["idToma"]) && !empty($_POST["idToma"])){

    // Get hidden input value
    $idToma = $_POST["idToma"];

    // Prepare an update statement
    
    $idEncuesta = trim($_POST["idEncuesta"]);
	$fechaHoraTomaEntrada = trim($_POST["fechaHoraTomaEntrada"]);
	$temperaturaEntrada = trim($_POST["temperaturaEntrada"]);
	$fechaHoraTomaSalida = trim($_POST["fechaHoraTomaSalida"]);
	$temperaturaSalida = trim($_POST["temperaturaSalida"]);
    $idUsuario = trim($_POST["idUsuario"]);
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

    $stmtPDO = $linkPDO->prepare("UPDATE tomas_temperatura SET idEncuesta=?,fechaHoraTomaEntrada=?,temperaturaEntrada=?,fechaHoraTomaSalida=?,temperaturaSalida=?,estado=?,idUsuario=?,auditoria=? WHERE idToma=?");

    if(!$stmtPDO->execute([ $idEncuesta,$fechaHoraTomaEntrada,$temperaturaEntrada,$fechaHoraTomaSalida,$temperaturaSalida,$idUsuario,$estado,$auditoria,$idToma  ])) {
            echo "Algo falló. Por favor intente de nuevo.";
            header("location: ../core/error.php");
        } else{
           $stmtPDO = null;
            // header("location: tomas_temperatura-read.php?idToma=$idToma");
            header("location: tomas_temperatura-index.php");
        }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idToma"]) && !empty(trim($_GET["idToma"]))){
        // Get URL parameter
        $idToma =  trim($_GET["idToma"]);

        // Prepare a select statement
        $sql = "SELECT * FROM tomas_temperatura WHERE idToma = ?";
        if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

            // Set parameters
            $param_id = $idToma;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmtPDO)){
                $resultPDO = mysqli_stmt_get_result($stmtPDO);

                if(mysqli_num_rows($resultPDO) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $idEncuesta = $row["idEncuesta"];
					$fechaHoraTomaEntrada = $row["fechaHoraTomaEntrada"];
					$temperaturaEntrada = $row["temperaturaEntrada"];
					$fechaHoraTomaSalida = $row["fechaHoraTomaSalida"];
					$temperaturaSalida = $row["temperaturaSalida"];
                    $idUsuario = $row["idUsuario"];
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
    <title>Actualizar Toma de Temperatura</title>
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
                        <h2>Toma de Temperatura - Actualizar</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Encuesta</label>
                            <?php
                                if(isset($_GET["idEncuesta"]) && !empty($_GET["idEncuesta"])){
                                    $where = "ES.idEncuesta = $idEncuesta";
                                }
                                else {
                                    $where = "TRUE";
                                }
                                $sql_cb5 = "SELECT ES.idEncuesta,
                                                   ES.idPersona,
                                                   ES.fechaHoraDiligenciamiento,
                                                   PE.nombreCompleto
                                                   FROM encuesta_signos ES
                                                   LEFT JOIN personas PE ON PE.idPersona = ES.idPersona
                                                   WHERE $where
                                                   ORDER BY idEncuesta DESC";
                                $result_cb5 = mysqli_query($linkMYSQLI, $sql_cb5);
                                echo "<select name='idEncuesta' id='cb5' class='combo-box form-control $readonly1' $disabled>";
                                while($row = mysqli_fetch_array($result_cb5)) {
                                    $selected = ($idEncuesta != $row['idEncuesta']) ? ('') : ('selected');
                                    echo "<option class='item-combo-box' $selected value='" . $row['idEncuesta'] . "'>" . $row['idEncuesta'] . ' * ' . $row['fechaHoraDiligenciamiento'] . ' * ' . $row['nombreCompleto'] . "</option>";                                
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idEncuesta_err; ?></span>
                        </div>

						<div class="form-group ocultar-columna">
                            <label>Fecha/hora toma de entrada</label>
                            <input type="text" name="fechaHoraTomaEntrada" class="form-control" value="<?php echo $fechaHoraTomaEntrada; ?>">
                            <span class="form-text"><?php echo $fechaHoraTomaEntrada_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Temperatura a la entrada</label>
                            <input type="text" name="temperaturaEntrada" class="form-control" value="<?php echo $temperaturaEntrada; ?>">
                            <span class="form-text"><?php echo $temperaturaEntrada_err; ?></span>
                        </div>

						<div class="form-group ocultar-columna">
                            <label>Fecha/hora toma de salida</label>
                            <input type="text" name="fechaHoraTomaSalida" class="form-control" value="<?php echo $fechaHoraTomaSalida; ?>">
                            <span class="form-text"><?php echo $fechaHoraTomaSalida_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Temperatura a la salida</label>
                            <input type="text" name="temperaturaSalida" class="form-control" value="<?php echo $temperaturaSalida; ?>">
                            <span class="form-text"><?php echo $temperaturaSalida_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Usuario que registra</label>
                            <?php
                                if(!empty($_POST["idUsuario"])) {
                                    $where = "idUsuario = " . $_POST['idUsuario'];
                                    $readonly2 = "solo-lectura";    
                                }
                                else {
                                    $where = "TRUE";
                                    $readonly2 = ""; 
                                }
                                $sql_cb1 = "SELECT idUsuario, nombreCompleto FROM usuarios WHERE $where";
                                $result_cb1 = mysqli_query($linkMYSQLI, $sql_cb1);
                                echo "<select name='idUsuario' id='cb1' class='combo-box form-control $readonly2'>";
                                while($row = mysqli_fetch_array($result_cb1)) {
                                    if ($idUsuario != $row['idUsuario'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idUsuario'] . "'>" . $row['nombreCompleto'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idUsuario'] . "'>" . $row['nombreCompleto'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idUsuario_err; ?></span>
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

                        <input type="hidden" name="idToma" value="<?php echo $idToma; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="Grabar">
                            <a href="tomas_temperatura-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>
