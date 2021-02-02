<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../core/menu.php");
    exit();    
}
?>

<?php
if ((!strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') and
    (!strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '')) {
    header("Location: ../core/menu.php");
    exit();
}
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
        $disabled = "disabled";
        $readonly1 = "solo-lectura";
    } else {
        //$idEncuesta = isset($_POST['idEncuesta']) ? $_POST['idEncuesta'] : '';
        $disabled = "";
        $readonly1 = "";
    }

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        print_r($_POST);

        $idEncuesta = trim($_POST["idEncuesta"]);
        $fechaHoraTomaEntrada = trim($_POST["fechaHoraTomaEntrada"]);
    	$temperaturaEntrada = trim($_POST["temperaturaEntrada"]);
    	$fechaHoraTomaSalida = trim($_POST["fechaHoraTomaSalida"]);
    	$temperaturaSalida = trim($_POST["temperaturaSalida"]);
        $idUsuario = trim($_POST["idUsuario"]);
        $idPersona = trim($_POST["idPersona"]);
        $estado = trim($_POST["estado"]);
    	$auditoria = date('Y-m-d H:i:s');

        if ($temperaturaEntrada != '' and $temperaturaSalida == '') {
            $fechaHoraTomaEntrada = date('Y-m-d H:i:s');
        }

        if ($temperaturaEntrada == '' and $temperaturaSalida != '') {
            $fechaHoraTomaSalida = date('Y-m-d H:i:s');
        }

        if ($temperaturaEntrada != '' and $temperaturaSalida != '') {
            $fechaHoraTomaEntrada = date('Y-m-d H:i:s');
            $fechaHoraTomaSalida = date('Y-m-d H:i:s');
        }

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

        $stmtPDO = $linkPDO->prepare("INSERT INTO tomas_temperatura (idEncuesta,fechaHoraTomaEntrada,temperaturaEntrada,fechaHoraTomaSalida,temperaturaSalida,idUsuario,idPersona,estado,auditoria) VALUES (?,?,?,?,?,?,?,?,?)"); 
        
        if($stmtPDO->execute([ $idEncuesta,$fechaHoraTomaEntrada,$temperaturaEntrada,$fechaHoraTomaSalida,$temperaturaSalida,$idUsuario,$idPersona,$estado,$auditoria ])) {
                $stmtPDO = null;
                $rutaRegresarA = $_SESSION["rutaRegresarA"];
                header("location: " . $rutaRegresarA);
            } else{
                echo "Algo falló. Por favor intente de nuevo.";
            }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Toma de Temperatura</title>
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
                        <h2>Toma de Temperatura - Crear</h2>
                    </div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

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
                                    $idEncuesta = $row['idEncuesta'];
                                    $idPersona = $row['idPersona'];
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

                        <?php echo $fechaHoraTomaEntrada; ?>

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

                        <?php echo $fechaHoraTomaSalida; ?>

						<div class="form-group">
                            <label>Temperatura a la salida</label>
                            <input type="text" name="temperaturaSalida" class="form-control" value="<?php echo $temperaturaSalida; ?>">
                            <span class="form-text"><?php echo $temperaturaSalida_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Usuario que registra</label>
                            <?php
                                if(!empty($_SESSION["idUsuario"])) {
                                    $where = "idUsuario = " . $_SESSION['idUsuario'];
                                    $readonly2 = "solo-lectura";    
                                }
                                else {
                                    $where = "TRUE";
                                    $readonly2 = ""; 
                                }
                                $sql_cb1 = "SELECT idUsuario, nombreCompleto FROM usuarios WHERE $where";
                                $result_cb1 = mysqli_query($linkMYSQLI, $sql_cb1);
                                echo "<select name='idUsuario' id='cb1' class='combo-box form-control $readonly2' $disabled>";
                                while($row = mysqli_fetch_array($result_cb1)) {
                                    if ($idUsuario != $row['idUsuario'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idUsuario'] . "'>" . $row['nombreCompleto'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idUsuario'] . "'>" . $row['nombreCompleto'] . "</option>";
                                    }
                                    $idUsuario = $row['idUsuario'];
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

                        <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">

                        <input type="hidden" name="idEncuesta" value="<?php echo $idEncuesta; ?>">

                        <input type="hidden" name="idPersona" value="<?php echo $idPersona; ?>">

                        <input type="submit" class="btn btn-primary" value="Grabar">

                        <?php
                            $rutaRegresarA = $_SESSION["rutaRegresarA"];
                            echo "<a href='$rutaRegresarA' class='btn btn-secondary'>Cancelar</a>";
                        ?>                        

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha512-iceXjjbmB2rwoX93Ka6HAHP+B76IY1z0o3h+N1PeDtRSsyeetU3/0QKJqGyPJcX63zysNehggFwMC/bi7dvMig==" crossorigin="anonymous"></script>
<script src="../js/libreria.js" type="text/javascript"></script>   

</body>
</html>