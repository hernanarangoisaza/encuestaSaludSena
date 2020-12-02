<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$idAprendiz = "";
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

$idAprendiz_err = "";
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
        $idAprendiz = trim($_POST["idAprendiz"]);
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
        $stmt = $pdo->prepare("INSERT INTO encuesta_signos (idAprendiz,fechaHoraDiligenciamiento,idSedeIngreso,idHorario,aceptacionConsideraciones,autorizacionTratamientoDatos,autorizacionIngreso,observacionAdicional,aceptacionRespuestaPositiva,estado,auditoria) VALUES (?,?,?,?,?,?,?,?,?,?,?)"); 
        
        if($stmt->execute([ $idAprendiz,$fechaHoraDiligenciamiento,$idSedeIngreso,$idHorario,$aceptacionConsideraciones,$autorizacionTratamientoDatos,$autorizacionIngreso,$observacionAdicional,$aceptacionRespuestaPositiva,$estado,$auditoria  ])) {
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="icon" href="imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">

                    <div class="page-header">
                        <h2>Encuesta de Signos - Crear</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Aprendiz</label>
                            <span class="form-text"><?php echo $idAprendiz_err; ?></span>
                            <?php
                                $sql_cb1 = "SELECT idAprendiz, nombreCompleto FROM aprendices";
                                $result_cb1 = mysqli_query($link, $sql_cb1);
                                echo "<select name='idAprendiz' id='cb1' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb1)) {
                                    if ($idAprendiz != $row['idAprendiz'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idAprendiz'] . "'>" . $row['nombreCompleto'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idAprendiz'] . "'>" . $row['nombreCompleto'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idAprendiz_err; ?></span>
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
                                $sql_cb3 = "SELECT idHorario, nombreCorto FROM horarios";
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
                                <option value="-1">Sin responder</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span class="form-text"><?php echo $aceptacionConsideraciones_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Autorización de tratamiento de datos</label>
                            <select name="autorizacionTratamientoDatos" class="form-control combo-box" id="autorizacionTratamientoDatos">
                                <option value="-1">Sin responder</option>
    						    <option value="0">No</option>
    						    <option value="1">Si</option>
						    </select>
                            <span class="form-text"><?php echo $autorizacionTratamientoDatos_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Autorización de ingreso</label>
                            <select name="autorizacionIngreso" class="form-control combo-box" id="autorizacionIngreso">
                                <option value="-1">Sin responder</option>
    						    <option value="0">No</option>
    						    <option value="1">Si</option>
						    </select>
                            <span class="form-text"><?php echo $autorizacionIngreso_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Observaciones adicionales</label>
                            <textarea name="observacionAdicional" class="form-control" rows="5"><?php echo $observacionAdicional ; ?></textarea>
                            <span class="form-text"><?php echo $observacionAdicional_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Aceptación de respuestas positivas</label>
                            <select name="aceptacionRespuestaPositiva" class="form-control combo-box" id="aceptacionRespuestaPositiva">
                                <option value="-1">Sin responder</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span class="form-text"><?php echo $aceptacionRespuestaPositiva_err; ?></span>
                        </div>

                        <div class="form-group ocultar-columna">
                            <label>Estado del registro</label>
                            <input type="text" name="estado" class="form-control" value="<?php echo $estado; ?>">
                            <span class="form-text"><?php echo $estado_err; ?></span>
                        </div>

                        <div class="form-group ocultar-columna">
                            <label>Fecha/Hora de auditoría</label>
                            <input type="text" name="auditoria" class="form-control" value="<?php echo $auditoria; ?>">
                            <span class="form-text"><?php echo $auditoria_err; ?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="encuesta_signos-index.php" class="btn btn-secondary">Cancelar</a>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>