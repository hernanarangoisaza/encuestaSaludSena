<?php
// Include config file
require_once "../core/config.php";

// Define variables and initialize with empty values
$horaInicial = "";
$horaFinal = "";
$nombreCorto = "";
$jornada = "";
$estado = "1";
$auditoria = "";

$horaInicial_err = "";
$horaFinal_err = "";
$nombreCorto_err = "";
$jornada_err = "";
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
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
        } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Algo extraño sucedió'); //something a user can understand
        }
        $stmt = $pdo->prepare("INSERT INTO horarios (horaInicial,horaFinal,nombreCorto,jornada,estado,auditoria) VALUES (?,?,?,?,?,?)"); 
        
        if($stmt->execute([ $horaInicial,$horaFinal,$nombreCorto,$jornada,$estado,$auditoria  ])) {
                $stmt = null;
                header("location: horarios-index.php");
            } else{
                echo "Algo falló. Por favor intente de nuevo.";
            }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Horario</title>
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
                        <h2>Horario - Crear</h2>
                    </div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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
                                <option value="-1">Sin asignar</option>
                                <option value="1">Mañana</option>
                                <option value="2">Tarde</option>
                                <option value="3">Noche</option>
                                <option value="4">Mixta mañana-tarde</option>
                                <option value="5">Mixta tarde-noche</option>
                                <option value="6">Completa</option>
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

                        <input type="submit" class="btn btn-primary" value="Grabar">
                        <a href="horarios-index.php" class="btn btn-secondary">Cancelar</a>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>