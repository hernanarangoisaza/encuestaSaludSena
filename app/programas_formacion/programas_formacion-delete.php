<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../index.php");
    exit();    
}
// foreach ($_SESSION as $key=>$val)
// echo $key." ".$val."<br/>";
// echo $_SESSION['permisosRolSistema'];
?>

<?php
if (!strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
    header("Location: ../index.php");
    exit();
}
?>

<?php
// Process delete operation after confirmation
if(isset($_POST["idProgramaFormacion"]) && !empty($_POST["idProgramaFormacion"])){
    // Include config file
    require_once "../core/config.php";

    // Prepare a delete statement
    $sql = "DELETE FROM programas_formacion WHERE idProgramaFormacion = ?";

    if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmtPDO, "i", $param_id);

        // Set parameters
        $param_id = trim($_POST["idProgramaFormacion"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmtPDO)){
            // Records deleted successfully. Redirect to landing page
            header("location: programas_formacion-index.php");
            exit();
        } else{
            echo "Oops! Algo falló. Por favor intente de nuevo.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmtPDO);

    // Close connection
    mysqli_close($linkMYSQLI);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["idProgramaFormacion"]))){
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
    <title>Borrar Programa de Formación</title>
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
                        <h1>Programa de Formación - Borrar</h1>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div class="fade-in">
                            <input type="hidden" name="idProgramaFormacion" value="<?php echo trim($_GET["idProgramaFormacion"]); ?>"/>
                            <p class="mensaje-eliminar">Está a punto de eliminar el registro seleccionado.</p>
                            <p>
                                <input type="submit" value="Eliminar permanentemente" class="btn btn-danger">
                                <a href="programas_formacion-index.php" class="btn btn-secondary">No borrar</a>
                            </p>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>
