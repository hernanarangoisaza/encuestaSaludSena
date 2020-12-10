<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$horaInicial = "";
$horaFinal = "";
$nombreCorto = "";
$jornada = "";
$estado = "";
$auditoria = "";

$horaInicial_err = "";
$horaFinal_err = "";
$nombreCorto_err = "";
$jornada_err = "";
$estado_err = "";
$auditoria_err = "";

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
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
        } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Algo extraño sucedió');
        }
        $stmt = $pdo->prepare("UPDATE horarios SET horaInicial=?,horaFinal=?,nombreCorto=?,jornada=?,estado=?,auditoria=? WHERE idHorario=?");

        if(!$stmt->execute([ $horaInicial,$horaFinal,$nombreCorto,$jornada,$estado,$auditoria,$idHorario ])) {
                echo "Algo falló. Por favor intente de nuevo.";
                header("location: error.php");
            } else{
                $stmt = null;
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
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idHorario;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $horaInicial = $row["horaInicial"];
					$horaFinal = $row["horaFinal"];
					$nombreCorto = $row["nombreCorto"];
                    $jornada = $row["jornada"];
					$estado = $row["estado"];
					$auditoria = $row["auditoria"];
					
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Algo falló. Por favor intente de nuevo.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Horario</title>
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
<script src="js/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
