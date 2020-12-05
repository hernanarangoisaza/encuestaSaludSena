<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$codigoFichaFormacion = "";
$idProgramaFormacion = "";
$estado = "";
$auditoria = "";

$codigoFichaFormacion_err = "";
$idProgramaFormacion_err = "";
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
        $codigoFichaFormacion = trim($_POST["codigoFichaFormacion"]);
		$idProgramaFormacion = trim($_POST["idProgramaFormacion"]);
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
        $stmt = $pdo->prepare("INSERT INTO fichas_formacion (codigoFichaFormacion,idProgramaFormacion,estado,auditoria) VALUES (?,?,?,?)"); 
        
        if($stmt->execute([ $codigoFichaFormacion,$idProgramaFormacion,$estado,$auditoria  ])) {
                $stmt = null;
                header("location: fichas_formacion-index.php");
            } else{
                echo "Algo falló. Por favor intente de nuevo.";
            }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Ficha de Formación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="icon" href="imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="page-header">
                        <h2>Ficha de Formación - Crear</h2>
                    </div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Código</label>
                            <input type="text" name="codigoFichaFormacion" maxlength="50" class="form-control" value="<?php echo $codigoFichaFormacion; ?>">
                            <span class="form-text"><?php echo $codigoFichaFormacion_err; ?></span>
                        </div>
						
                        <div class="form-group">
                            <label>Programa de formación</label>
                            <?php
                                $sql_cb5 = "SELECT idProgramaFormacion, nombreLargoProgramaFormacion FROM programas_formacion";
                                $result_cb5 = mysqli_query($link, $sql_cb5);
                                echo "<select name='idProgramaFormacion' id='cb5' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb5)) {
                                    if ($idProgramaFormacion != $row['idProgramaFormacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idProgramaFormacion'] . "'>" . $row['nombreLargoProgramaFormacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idProgramaFormacion'] . "'>" . $row['nombreLargoProgramaFormacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="form-text"><?php echo $idProgramaFormacion_err; ?></span>
                        </div>

                        <div class="form-group ocultar-columna">
                            <label>Estado del registro</label>
                            <input type="number" name="estado" class="form-control" value="<?php echo $row['estado']; ?>" readonly>
                        </div>

                        <div class="form-group ocultar-columna">
                            <label>Fecha/Hora de auditoría</label>
                            <input type="text" name="auditoria" class="form-control" value="<?php echo $row['auditoria']; ?>" readonly>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="fichas_formacion-index.php" class="btn btn-secondary">Cancelar</a>

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