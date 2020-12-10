<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$idEncuesta = "";
$fechaHoraTomaEntrada = "";
$temperaturaEntrada = "";
$fechaHoraTomaSalida = "";
$temperaturaSalida = "";
$estado = "1";
$auditoria = "";

$idEncuesta_err = "";
$fechaHoraTomaEntrada_err = "";
$temperaturaEntrada_err = "";
$fechaHoraTomaSalida_err = "";
$temperaturaSalida_err = "";
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
        $idEncuesta = trim($_POST["idEncuesta"]);
		$fechaHoraTomaEntrada = trim($_POST["fechaHoraTomaEntrada"]);
		$temperaturaEntrada = trim($_POST["temperaturaEntrada"]);
		$fechaHoraTomaSalida = trim($_POST["fechaHoraTomaSalida"]);
		$temperaturaSalida = trim($_POST["temperaturaSalida"]);
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
        $stmt = $pdo->prepare("INSERT INTO tomas_temperatura (idEncuesta,fechaHoraTomaEntrada,temperaturaEntrada,fechaHoraTomaSalida,temperaturaSalida,estado,auditoria) VALUES (?,?,?,?,?,?,?)"); 
        
        if($stmt->execute([ $idEncuesta,$fechaHoraTomaEntrada,$temperaturaEntrada,$fechaHoraTomaSalida,$temperaturaSalida,$estado,$auditoria  ])) {
                $stmt = null;
                header("location: tomas_temperatura-index.php");
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
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="icon" href="imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">

                    <div class="page-header">
                        <h2>Toma de Temperatura - Crear</h2>
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
                        <a href="tomas_temperatura-index.php" class="btn btn-secondary">Cancelar</a>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="js/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>