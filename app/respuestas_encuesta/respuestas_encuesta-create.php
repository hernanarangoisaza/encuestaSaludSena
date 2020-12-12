<?php
// Include config file
require_once "../core/config.php";

// Define variables and initialize with empty values
$idEncuesta = "";
$idPreguntaEncuesta = "";
$respuestaSiNo = "";
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
        $idEncuesta = trim($_POST["idEncuesta"]);
		$idPreguntaEncuesta = trim($_POST["idPreguntaEncuesta"]);
		$respuestaSiNo = trim($_POST["respuestaSiNo"]);
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
        $stmt = $pdo->prepare("INSERT INTO respuestas_encuesta (idEncuesta,idPreguntaEncuesta,respuestaSiNo,estado,auditoria) VALUES (?,?,?,?,?)"); 
        
        if($stmt->execute([ $idEncuesta,$idPreguntaEncuesta,$respuestaSiNo,$estado,$auditoria  ])) {
                $stmt = null;
                header("location: respuestas_encuesta-index.php");
            } else{
                echo "Algo falló. Por favor intente de nuevo.";
            }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Respuesta de la Encuesta</title>
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
                        <h2>Respuesta de la Encuesta - Crear</h2>
                    </div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Encuesta</label>
                            <?php
                                $sql_cb5 = "SELECT ES.idEncuesta,
                                                   ES.idPersona,
                                                   ES.fechaHoraDiligenciamiento,
                                                   PE.nombreCompleto
                                                   FROM encuesta_signos ES
                                                   LEFT JOIN personas PE ON PE.idPersona = ES.idPersona
                                                   ORDER BY idEncuesta DESC";
                                $result_cb5 = mysqli_query($link, $sql_cb5);
                                echo "<select name='idEncuesta' id='cb5' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb5)) {
                                    $selected = ($idEncuesta != $row['idEncuesta']) ? ('') : ('selected');
                                    echo "<option class='item-combo-box' $selected value='" . $row['idEncuesta'] . "'>" . $row['idEncuesta'] . ' * ' . $row['fechaHoraDiligenciamiento'] . ' * ' . $row['nombreCompleto'] . "</option>";                                
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idEncuesta_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Pregunta</label>
                           <?php
                                $sql_cb5 = "SELECT PE.idPreguntaEncuesta,
                                                   PE.textoPregunta
                                                   FROM preguntas_encuesta PE";
                                $result_cb5 = mysqli_query($link, $sql_cb5);
                                echo "<select name='idPreguntaEncuesta' id='cb5' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb5)) {
                                    $selected = ($idPreguntaEncuesta != $row['idPreguntaEncuesta']) ? ('') : ('selected');
                                    $option = (strlen($row['textoPregunta']) >= 100) ? (substr($row['textoPregunta'],0,100)) : ($row['textoPregunta']);
                                    $muyLargo = (strlen($row['textoPregunta']) >= 100) ? ('...') : ('');
                                    echo "<option class='item-combo-box' $selected value='" . $row['idPreguntaEncuesta'] . "'>$option $muyLargo</option>";                                    
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idPreguntaEncuesta_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Respuesta</label>
                            <select name="respuestaSiNo" class="form-control combo-box" id="respuestaSiNo">
                                <option value="-1">Sin asignar</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span class="form-text"><?php echo $respuestaSiNo_err; ?></span>
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
                        <a href="respuestas_encuesta-index.php" class="btn btn-secondary">Cancelar</a>

                    </form>
                </div>
            </div>
        </div>
    </section> 
<script src="../js/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>