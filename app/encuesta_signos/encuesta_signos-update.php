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
$estado = "";
$auditoria = "";

$idPersona_err = "";
$fechaHoraDiligenciamiento_err = "";
$idSedeIngreso_err = "";
$idHorario_err = "";
$aceptacionConsideraciones_err = "";
$autorizacionTratamientoDatos_err = "";
$autorizacionIngreso_err = "";
$observacionAdicional_err = "";
$aceptacionRespuestaPositiva_err = "";
$estado_err = "";
$auditoria_err = "";

// Processing form data when form is submitted
if(isset($_POST["idEncuesta"]) && !empty($_POST["idEncuesta"])){
    // Get hidden input value
    $idEncuesta = $_POST["idEncuesta"];

        // Prepare an update statement
        
        $idPersona = trim($_POST["idPersona"]);
		$fechaHoraDiligenciamiento = trim($_POST["fechaHoraDiligenciamiento"]);
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
          exit('Algo extraño sucedió');
        }
        $stmt = $pdo->prepare("UPDATE encuesta_signos SET idPersona=?,fechaHoraDiligenciamiento=?,idSedeIngreso=?,idHorario=?,aceptacionConsideraciones=?,autorizacionTratamientoDatos=?,autorizacionIngreso=?,observacionAdicional=?,aceptacionRespuestaPositiva=?,estado=?,auditoria=? WHERE idEncuesta=?");

        if(!$stmt->execute([ $idPersona,$fechaHoraDiligenciamiento,$idSedeIngreso,$idHorario,$aceptacionConsideraciones,$autorizacionTratamientoDatos,$autorizacionIngreso,$observacionAdicional,$aceptacionRespuestaPositiva,$estado,$auditoria,$idEncuesta  ])) {
                echo "Algo falló. Por favor intente de nuevo.";
                header("location: ../core/error.php");
            } else{
                $stmt = null;
                // header("location: encuesta_signos-read.php?idEncuesta=$idEncuesta");
                header("location: encuesta_signos-index.php");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idEncuesta"]) && !empty(trim($_GET["idEncuesta"]))){
        // Get URL parameter
        $idEncuesta =  trim($_GET["idEncuesta"]);

        // Prepare a select statement
        $sql = "SELECT * FROM encuesta_signos WHERE idEncuesta = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idEncuesta;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $idPersona = $row["idPersona"];
					$fechaHoraDiligenciamiento = $row["fechaHoraDiligenciamiento"];
					$idSedeIngreso = $row["idSedeIngreso"];
					$idHorario = $row["idHorario"];
					$aceptacionConsideraciones = $row["aceptacionConsideraciones"];
					$autorizacionTratamientoDatos = $row["autorizacionTratamientoDatos"];
					$autorizacionIngreso = $row["autorizacionIngreso"];
					$observacionAdicional = $row["observacionAdicional"];
					$aceptacionRespuestaPositiva = $row["aceptacionRespuestaPositiva"];
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
        mysqli_stmt_close($stmt);

        // Close connection
        // mysqli_close($link);

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
    <title>Actualizar Encuesta de Signos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">

                    <div class="page-header">
                        <h2>Encuesta de Signos - Actualizar</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

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
                                    if ($idSedeIngreso != $row['idCentroFormacion'])
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
                            <?php
                                $selected1 = ''; $selected2 = ''; $selected3 = '';
                                if ($aceptacionConsideraciones == -1) { $selected1 = 'selected'; }
                                else if ($aceptacionConsideraciones == 0) { $selected2 = 'selected'; }
                                else if ($aceptacionConsideraciones == 1) { $selected3 = 'selected'; }
                                else { $selected1 = 'selected'; }
                                echo "<option class='item-combo-box' value='-1' $selected1>Sin asignar</option>";
                                echo "<option class='item-combo-box' value='0'  $selected2>No</option>";
                                echo "<option class='item-combo-box' value='1'  $selected3>Si</option>";
                             ?>
                             </select>
                             <span class="form-text"><?php echo $aceptacionConsideraciones_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Autorización de tratamiento de datos</label>
                            <select name="autorizacionTratamientoDatos" class="form-control combo-box" id="autorizacionTratamientoDatos">
                            <?php
                                $selected1 = ''; $selected2 = ''; $selected3 = '';
                                if ($autorizacionTratamientoDatos == -1) { $selected1 = 'selected'; }
                                else if ($autorizacionTratamientoDatos == 0) { $selected2 = 'selected'; }
                                else if ($autorizacionTratamientoDatos == 1) { $selected3 = 'selected'; }
                                else { $selected1 = 'selected'; }
                                echo "<option class='item-combo-box' value='-1' $selected1>Sin asignar</option>";
                                echo "<option class='item-combo-box' value='0'  $selected2>No</option>";
                                echo "<option class='item-combo-box' value='1'  $selected3>Si</option>";
                             ?>
                             </select>
                             <span class="form-text"><?php echo $autorizacionTratamientoDatos_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Autorización de ingreso</label>
                            <select name="autorizacionIngreso" class="form-control combo-box" id="autorizacionIngreso">
                            <?php
                                $selected1 = ''; $selected2 = ''; $selected3 = '';
                                if ($autorizacionIngreso == -1) { $selected1 = 'selected'; }
                                else if ($autorizacionIngreso == 0) { $selected2 = 'selected'; }
                                else if ($autorizacionIngreso == 1) { $selected3 = 'selected'; }
                                else { $selected1 = 'selected'; }
                                echo "<option class='item-combo-box' value='-1' $selected1>Sin asignar</option>";
                                echo "<option class='item-combo-box' value='0'  $selected2>No</option>";
                                echo "<option class='item-combo-box' value='1'  $selected3>Si</option>";
                             ?>
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
                            <?php
                                $selected1 = ''; $selected2 = ''; $selected3 = '';
                                if ($aceptacionRespuestaPositiva == -1) { $selected1 = 'selected'; }
                                else if ($aceptacionRespuestaPositiva == 0) { $selected2 = 'selected'; }
                                else if ($aceptacionRespuestaPositiva == 1) { $selected3 = 'selected'; }
                                else { $selected1 = 'selected'; }
                                echo "<option class='item-combo-box' value='-1' $selected1>Sin asignar</option>";
                                echo "<option class='item-combo-box' value='0'  $selected2>No</option>";
                                echo "<option class='item-combo-box' value='1'  $selected3>Si</option>";
                             ?>
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

                        <input type="hidden" name="idEncuesta" value="<?php echo $idEncuesta; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="Grabar">
                            <a href="encuesta_signos-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="../js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="../js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>