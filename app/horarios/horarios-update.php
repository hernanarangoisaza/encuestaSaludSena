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
$horaInicial = "";
$horaFinal = "";
$nombreCorto = "";
$jornada = "";
$estado = "";
$auditoria = "";

// Processing form data when form is submitted
if(isset($_POST["idHorario"]) && !empty($_POST["idHorario"])){
    // Get hidden input value
    $idHorario = $_POST["idHorario"];

    // Prepare an update statement
    
    $horaInicial = trim($_POST["horaInicial"]);
	$horaFinal = trim($_POST["horaFinal"]);
	$nombreCorto = trim($_POST["nombreCorto"]);
    $jornada = trim($_POST["jornada"]);
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
   $stmtPDO = $linkPDO->prepare("UPDATE horarios SET horaInicial=?,horaFinal=?,nombreCorto=?,jornada=?,estado=?,auditoria=? WHERE idHorario=?");

    if(!$stmtPDO->execute([ $horaInicial,$horaFinal,$nombreCorto,$jornada,$estado,$auditoria,$idHorario ])) {
            echo "Algo falló. Por favor intente de nuevo.";
            header("location: ../core/error.php");
        } else{
           $stmtPDO = null;
            // header("location: horarios-read.php?idHorario=$idHorario");
            header("location: horarios-index.php");
        }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idHorario"]) && !empty(trim($_GET["idHorario"]))){
        // Get URL parameter
        $idHorario =  trim($_GET["idHorario"]);

        // Prepare a select statement
        $sql = "SELECT * FROM horarios WHERE idHorario = ?";
        if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

            // Set parameters
            $param_id = $idHorario;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmtPDO)){
                $resultPDO = mysqli_stmt_get_result($stmtPDO);

                if(mysqli_num_rows($resultPDO) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $horaInicial = $row["horaInicial"];
					$horaFinal = $row["horaFinal"];
					$nombreCorto = $row["nombreCorto"];
                    $jornada = $row["jornada"];
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
        mysqli_close($linkMYSQLI);

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
    <title>Actualizar Horario</title>
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
                        <h2>Horario - Actualizar</h2>
                    </div>
                    <p>Por favor ingrese nueva información para actualizar el registro.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Hora Inicial</label>
                            <input type="time" name="horaInicial" class="form-control" value="<?php echo $horaInicial; ?>">
                            <span class="form-text"><?php echo $horaInicial_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Hora Final</label>
                            <input type="time" name="horaFinal" class="form-control" value="<?php echo $horaFinal; ?>">
                            <span class="form-text"><?php echo $horaFinal_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Nombre corto</label>
                            <input type="text" name="nombreCorto" maxlength="50" class="form-control" value="<?php echo $nombreCorto; ?>">
                            <span class="form-text"><?php echo $nombreCorto_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Jornada</label>
                            <select name="jornada" class="form-control combo-box" id="jornada">
                            <?php
                                $selected1 = ''; $selected2 = ''; $selected3 = ''; $selected4 = ''; 
                                $selected5 = ''; $selected6 = ''; $selected7 = '';
                                if ($jornada == -1) { $selected1 = 'selected'; }
                                else if ($jornada == 1) { $selected2 = 'selected'; }
                                else if ($jornada == 2) { $selected3 = 'selected'; }
                                else if ($jornada == 3) { $selected4 = 'selected'; }
                                else if ($jornada == 4) { $selected5 = 'selected'; }
                                else if ($jornada == 5) { $selected6 = 'selected'; }
                                else if ($jornada == 6) { $selected7 = 'selected'; }
                                else { $selected1 = 'selected'; }
                                echo "<option class='item-combo-box' value='-1' $selected1>Sin asignar</option>";
                                echo "<option class='item-combo-box' value='1'  $selected2>Mañana</option>";
                                echo "<option class='item-combo-box' value='2'  $selected3>Tarde</option>";
                                echo "<option class='item-combo-box' value='3'  $selected4>Noche</option>";
                                echo "<option class='item-combo-box' value='4'  $selected5>Mixta mañana-tarde</option>";
                                echo "<option class='item-combo-box' value='5'  $selected6>Mixta tarde-noche</option>";
                                echo "<option class='item-combo-box' value='6'  $selected7>Completa</option>";
                            ?>
                            </select>
                            <span class="form-text"><?php echo $jornada_err; ?></span>
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

                        <input type="hidden" name="idHorario" value="<?php echo $idHorario; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="Grabar">
                            <a href="horarios-index.php" class="btn btn-secondary">Cancelar</a>
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
