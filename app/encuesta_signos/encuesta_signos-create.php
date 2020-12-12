<?php
// Include config file
require_once "../core/config.php";

// Define variables and initialize with empty values
$idPersona = "";
$fechaHoraDiligenciamiento = "";
$idSedeIngreso = "";
$idHorario = "";
$aceptacionConsideraciones = "";
$autorizacionTratamientoDatos = "";
$autorizacionIngreso = "";
$observacionAdicional = "";
$aceptacionRespuestaPositiva = "";
$estado = "1";
$auditoria = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
/*    
    // Validate input
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";
    } else{
        $address = $input_address;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err)){
        // Prepare an insert statement
 */
        $idPersona = trim($_POST["idPersona"]);
		$fechaHoraDiligenciamiento = date('Y-m-d H:i:s');
		$idSedeIngreso = trim($_POST["idSedeIngreso"]);
		$idHorario = trim($_POST["idHorario"]);
		$aceptacionConsideraciones = trim($_POST["aceptacionConsideraciones"]);
		$autorizacionTratamientoDatos = trim($_POST["autorizacionTratamientoDatos"]);
		$autorizacionIngreso = trim($_POST["autorizacionIngreso"]);
		$observacionAdicional = trim($_POST["observacionAdicional"]);
		$aceptacionRespuestaPositiva = trim($_POST["aceptacionRespuestaPositiva"]);
		$estado = trim($_POST["estado"]);
		$auditoria = date('Y-m-d H:i:s');
		
        $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
        $options = [
          PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        ];
        try {
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
        } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Algo extraño sucedió'); //something a user can understand
        }
        $stmt = $pdo->prepare("INSERT INTO encuesta_signos (idPersona,fechaHoraDiligenciamiento,idSedeIngreso,idHorario,aceptacionConsideraciones,autorizacionTratamientoDatos,autorizacionIngreso,observacionAdicional,aceptacionRespuestaPositiva,estado,auditoria) VALUES (?,?,?,?,?,?,?,?,?,?,?)"); 
        
        if($stmt->execute([ $idPersona,$fechaHoraDiligenciamiento,$idSedeIngreso,$idHorario,$aceptacionConsideraciones,$autorizacionTratamientoDatos,$autorizacionIngreso,$observacionAdicional,$aceptacionRespuestaPositiva,$estado,$auditoria  ])) {
                $stmt = null;
                header("location: encuesta_signos-index.php");
            } else{
                echo "Algo falló. Por favor intente de nuevo.";
            }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Encuesta de Signos</title>
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
                        <h2>Encuesta de Signos - Crear</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Persona</label>
                            <?php
                                $sql_cb1 = "SELECT idPersona, nombreCompleto FROM personas";
                                $result_cb1 = mysqli_query($link, $sql_cb1);
                                echo "<select name='idPersona' id='cb1' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb1)) {
                                    if ($idPersona != $row['idPersona'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idPersona'] . "'>" . $row['nombreCompleto'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idPersona'] . "'>" . $row['nombreCompleto'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idPersona_err; ?></span>
                        </div>

						<div class="form-group ocultar-columna">
                            <label>Fecha/Hora de diligenciamiento</label>
                            <input type="text" name="fechaHoraDiligenciamiento" class="form-control" value="<?php echo $fechaHoraDiligenciamiento; ?>">
                            <span class="form-text"><?php echo $fechaHoraDiligenciamiento_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Sede de ingreso</label>
                            <?php
                                $sql_cb2 = "SELECT idCentroFormacion, nombreLargoCentroFormacion, nombreCorto FROM centros_formacion";
                                $result_cb2 = mysqli_query($link, $sql_cb2);
                                echo "<select name='idSedeIngreso' id='cb2' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb2)) {
                                    if ($idCentroFormacion != $row['idCentroFormacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idCentroFormacion'] . "'>" . $row['nombreLargoCentroFormacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idCentroFormacion'] . "'>" . $row['nombreLargoCentroFormacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idSedeIngreso_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Horario</label>
                            <?php
                                $sql_cb3 = "SELECT idHorario, nombreCorto FROM horarios ORDER BY horaInicial, horaFinal";
                                $result_cb3 = mysqli_query($link, $sql_cb3);
                                echo "<select name='idHorario' id='cb3' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb3)) {
                                    if ($idHorario != $row['idHorario'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idHorario'] . "'>" . $row['nombreCorto'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idHorario'] . "'>" . $row['nombreCorto'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idHorario_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Aceptación de consideraciones</label>
                            <select name="aceptacionConsideraciones" class="form-control combo-box" id="aceptacionConsideraciones">
                                <option value="-1">Sin asignar</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span class="form-text"><?php echo $aceptacionConsideraciones_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Autorización de tratamiento de datos</label>
                            <select name="autorizacionTratamientoDatos" class="form-control combo-box" id="autorizacionTratamientoDatos">
                                <option value="-1">Sin asignar</option>
    						    <option value="0">No</option>
    						    <option value="1">Si</option>
						    </select>
                            <span class="form-text"><?php echo $autorizacionTratamientoDatos_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Autorización de ingreso</label>
                            <select name="autorizacionIngreso" class="form-control combo-box" id="autorizacionIngreso">
                                <option value="-1">Sin asignar</option>
    						    <option value="0">No</option>
    						    <option value="1">Si</option>
						    </select>
                            <span class="form-text"><?php echo $autorizacionIngreso_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Observaciones adicionales</label>
                            <textarea name="observacionAdicional" class="form-control" rows="5"><?php echo $observacionAdicional; ?></textarea>
                            <span class="form-text"><?php echo $observacionAdicional_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Aceptación de respuestas positivas</label>
                            <select name="aceptacionRespuestaPositiva" class="form-control combo-box" id="aceptacionRespuestaPositiva">
                                <option value="-1">Sin asignar</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span class="form-text"><?php echo $aceptacionRespuestaPositiva_err; ?></span>
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
                        <a href="encuesta_signos-index.php" class="btn btn-secondary">Cancelar</a>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>