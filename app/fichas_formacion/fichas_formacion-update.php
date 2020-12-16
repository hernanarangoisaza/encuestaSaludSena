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
$codigoFichaFormacion = "";
$idProgramaFormacion = "";
$estado = "";
$auditoria = "";

// Processing form data when form is submitted
if(isset($_POST["idFichaFormacion"]) && !empty($_POST["idFichaFormacion"])){
    // Get hidden input value
    $idFichaFormacion = $_POST["idFichaFormacion"];

    // Prepare an update statement
    
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
      $linkPDO = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
      error_log($e->getMessage());
      exit('Algo extraño sucedió');
    }
   $stmtPDO = $linkPDO->prepare("UPDATE fichas_formacion SET codigoFichaFormacion=?,idProgramaFormacion=?,estado=?,auditoria=? WHERE idFichaFormacion=?");

    if(!$stmtPDO->execute([ $codigoFichaFormacion,$idProgramaFormacion,$estado,$auditoria,$idFichaFormacion  ])) {
            echo "Algo falló. Por favor intente de nuevo.";
            header("location: ../core/error.php");
        } else{
           $stmtPDO = null;
            // header("location: fichas_formacion-read.php?idFichaFormacion=$idFichaFormacion");
            header("location: fichas_formacion-index.php");
        }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idFichaFormacion"]) && !empty(trim($_GET["idFichaFormacion"]))){
        // Get URL parameter
        $idFichaFormacion =  trim($_GET["idFichaFormacion"]);

        // Prepare a select statement
        $sql = "SELECT * FROM fichas_formacion WHERE idFichaFormacion = ?";
        if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

            // Set parameters
            $param_id = $idFichaFormacion;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmtPDO)){
                $resultPDO = mysqli_stmt_get_result($stmtPDO);

                if(mysqli_num_rows($resultPDO) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $codigoFichaFormacion = $row["codigoFichaFormacion"];
					$idProgramaFormacion = $row["idProgramaFormacion"];
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
        // mysqli_close($linkMYSQLI);

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
    <title>Actualizar Ficha de Formación</title>
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
                        <h2>Ficha de Formación - Actualizar</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Código</label>
                            <input type="text" name="codigoFichaFormacion" maxlength="50" class="form-control" value="<?php echo $codigoFichaFormacion; ?>">
                            <span class="form-text"><?php echo $codigoFichaFormacion_err; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Programa de formación</label>
                            <?php
                                $sql_cb5 = "SELECT idProgramaFormacion, nombreLargoProgramaFormacion FROM programas_formacion ORDER BY nombreLargoProgramaFormacion";
                                $result_cb5 = mysqli_query($linkMYSQLI, $sql_cb5);
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
                            <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                            <span class="form-text"><?php echo $estado_err; ?></span>
                        </div>

                        <div class="form-group ocultar-columna">
                            <label>Fecha/Hora de auditoría</label>
                            <input type="text" name="auditoria" class="form-control" value="<?php echo $auditoria; ?>">
                            <span class="form-text"><?php echo $auditoria_err; ?></span>
                        </div>

                        <input type="hidden" name="idFichaFormacion" value="<?php echo $idFichaFormacion; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="Grabar">
                            <a href="fichas_formacion-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>
