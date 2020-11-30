<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$idTipoVinculacion = "";
$nombreCompleto = "";
$idTipoIdentificacion = "";
$identificacion = "";
$email = "";
$telefonoPersonal = "";
$telefonoAcudiente = "";
$fechaNacimiento = "";
$idTipoGenero = "";
$direccionResidencia = "";
$idMunicipio = "";
$idDepartamento = "";
$idCentroFormacion = "";
$idFichaFormacion = "";
$estado = "";
$auditoria = "";

$idTipoVinculacion_err = "";
$nombreCompleto_err = "";
$idTipoIdentificacion_err = "";
$identificacion_err = "";
$email_err = "";
$telefonoPersonal_err = "";
$telefonoAcudiente_err = "";
$fechaNacimiento_err = "";
$idTipoGenero_err = "";
$direccionResidencia_err = "";
$idMunicipio_err = "";
$idDepartamento_err = "";
$idCentroFormacion_err = "";
$idFichaFormacion_err = "";
$estado_err = "";
$auditoria_err = "";

// Processing form data when form is submitted
if(isset($_POST["idAprendiz"]) && !empty($_POST["idAprendiz"])){

    // Get hidden input value
    $idAprendiz = $_POST["idAprendiz"];

        // Prepare an update statement

        $idTipoVinculacion = $_POST["idTipoVinculacion"];
		$nombreCompleto = trim($_POST["nombreCompleto"]);
		$idTipoIdentificacion = $_POST["idTipoIdentificacion"];
		$identificacion = trim($_POST["identificacion"]);
		$email = trim($_POST["email"]);
		$telefonoPersonal = trim($_POST["telefonoPersonal"]);
		$telefonoAcudiente = trim($_POST["telefonoAcudiente"]);
		$fechaNacimiento = trim($_POST["fechaNacimiento"]);
		$idTipoGenero = $_POST["idTipoGenero"];
		$direccionResidencia = trim($_POST["direccionResidencia"]);
		$idMunicipio = $_POST["idMunicipio"];
		$idDepartamento = $_POST["idDepartamento"];
		$idCentroFormacion = $_POST["idCentroFormacion"];
		$idFichaFormacion = $_POST["idFichaFormacion"];
		$estado = trim($_POST["estado"]);
		$auditoria = trim($_POST["auditoria"]);

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
        $stmt = $pdo->prepare("UPDATE aprendices SET idTipoVinculacion=?,nombreCompleto=?,idTipoIdentificacion=?,identificacion=?,email=?,telefonoPersonal=?,telefonoAcudiente=?,fechaNacimiento=?,idTipoGenero=?,direccionResidencia=?,idMunicipio=?,idDepartamento=?,idCentroFormacion=?,idFichaFormacion=?,estado=?,auditoria=? WHERE idAprendiz=?");

        if(!$stmt->execute([ $idTipoVinculacion,$nombreCompleto,$idTipoIdentificacion,$identificacion,$email,$telefonoPersonal,$telefonoAcudiente,$fechaNacimiento,$idTipoGenero,$direccionResidencia,$idMunicipio,$idDepartamento,$idCentroFormacion,$idFichaFormacion,$estado,$auditoria,$idAprendiz  ])) {
                echo "Algo falló. Por favor intente de nuevo.";
                header("location: error.php");
            } else{
                $stmt = null;
                header("location: aprendices-read.php?idAprendiz=$idAprendiz");
            }
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["idAprendiz"]) && !empty(trim($_GET["idAprendiz"]))){
        // Get URL parameter
        $idAprendiz =  trim($_GET["idAprendiz"]);

        // Prepare a select statement
        $sql = "SELECT * FROM aprendices WHERE idAprendiz = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $idAprendiz;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $idTipoVinculacion = $row["idTipoVinculacion"];
					$nombreCompleto = $row["nombreCompleto"];
					$idTipoIdentificacion = $row["idTipoIdentificacion"];
					$identificacion = $row["identificacion"];
					$email = $row["email"];
					$telefonoPersonal = $row["telefonoPersonal"];
					$telefonoAcudiente = $row["telefonoAcudiente"];
					$fechaNacimiento = $row["fechaNacimiento"];
					$idTipoGenero = $row["idTipoGenero"];
					$direccionResidencia = $row["direccionResidencia"];
					$idMunicipio = $row["idMunicipio"];
					$idDepartamento = $row["idDepartamento"];
					$idCentroFormacion = $row["idCentroFormacion"];
					$idFichaFormacion = $row["idFichaFormacion"];
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
        // mysqli_close($link);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Aprendiz</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css" />
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mx-auto">
                    <div class="page-header">
                        <h2>Aprendices - Actualizar</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Tipo de vinculación</label>
                            <?php
                                $sql_cb1 = "SELECT idTipoVinculacion, nombreLargoVinculacion, nombreCorto FROM tipos_vinculaciones_sena";
                                $result_cb1 = mysqli_query($link, $sql_cb1);
                                echo "<select name='idTipoVinculacion' id='cb1' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb1)) {
                                    if ($idTipoVinculacion != $row['idTipoVinculacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idTipoVinculacion'] . "'>" . $row['nombreLargoVinculacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idTipoVinculacion'] . "'>" . $row['nombreLargoVinculacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                        </div>

						<div class="form-group">
                            <label>Nombre completo</label>
                            <input type="text" name="nombreCompleto" maxlength="50"class="form-control" value="<?php echo $nombreCompleto; ?>">
                            <span class="form-text"><?php echo $nombreCompleto_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Tipo de identificación</label>
                            <?php
                                $sql_cb2 = "SELECT idTipoIdentificacion, nombreLargoIdentificacion, nombreCorto FROM tipos_identificacion";
                                $result_cb2 = mysqli_query($link, $sql_cb2);
                                echo "<select name='idTipoIdentificacion' id='cb2' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb2)) {
                                    if ($idTipoIdentificacion != $row['idTipoIdentificacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idTipoIdentificacion'] . "'>" . $row['nombreLargoIdentificacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idTipoIdentificacion'] . "'>" . $row['nombreLargoIdentificacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                        </div>

						<div class="form-group">
                            <label>Identificación</label>
                            <input type="text" name="identificacion" maxlength="50"class="form-control" value="<?php echo $identificacion; ?>">
                            <span class="form-text"><?php echo $identificacion_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Correo electrónico</label>
                            <input type="text" name="email" maxlength="50"class="form-control" value="<?php echo $email; ?>">
                            <span class="form-text"><?php echo $email_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Teléfono personal</label>
                            <input type="text" name="telefonoPersonal" maxlength="50"class="form-control" value="<?php echo $telefonoPersonal; ?>">
                            <span class="form-text"><?php echo $telefonoPersonal_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Teléfono del acudiente</label>
                            <input type="text" name="telefonoAcudiente" maxlength="50"class="form-control" value="<?php echo $telefonoAcudiente; ?>">
                            <span class="form-text"><?php echo $telefonoAcudiente_err; ?></span>
                        </div>

						<div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <input type="text" name="fechaNacimiento" class="form-control" value="<?php echo $fechaNacimiento; ?>">
                            <span class="form-text"><?php echo $fechaNacimiento_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Género</label>
                            <?php
                                $sql_cb3 = "SELECT idTipoGenero, nombreLargoGenero, nombreCorto FROM tipos_generos";
                                $result_cb3 = mysqli_query($link, $sql_cb3);
                                echo "<select name='idTipoGenero' id='cb3' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb3)) {
                                    if ($idTipoGenero != $row['idTipoGenero'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idTipoGenero'] . "'>" . $row['nombreLargoGenero'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idTipoGenero'] . "'>" . $row['nombreLargoGenero'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                        </div>

						<div class="form-group">
                            <label>Dirección de la residencia</label>
                            <input type="text" name="direccionResidencia" maxlength="50"class="form-control" value="<?php echo $direccionResidencia; ?>">
                            <span class="form-text"><?php echo $direccionResidencia_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Municipio</label>
                            <?php
                                $sql_cb4 = "SELECT idMunicipio, municipio FROM municipios";
                                $result_cb4 = mysqli_query($link, $sql_cb4);
                                echo "<select name='idMunicipio' id='cb4' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb4)) {
                                    if ($idMunicipio != $row['idMunicipio'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idMunicipio'] . "'>" . $row['municipio'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idMunicipio'] . "'>" . $row['municipio'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                        </div>

                        <div class="form-group">
                            <label>Departamento</label>
                            <?php
                                $sql_cb5 = "SELECT idDepartamento, departamento FROM departamentos";
                                $result_cb5 = mysqli_query($link, $sql_cb5);
                                echo "<select name='idDepartamento' id='cb5' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb5)) {
                                    if ($idDepartamento != $row['idDepartamento'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idDepartamento'] . "'>" . $row['departamento'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idDepartamento'] . "'>" . $row['departamento'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                        </div>

                        <div class="form-group">
                            <label>Centro de formación</label>
                            <?php
                                $sql_cb6 = "SELECT idCentroFormacion, nombreLargoCentroFormacion, nombreCorto FROM centros_formacion";
                                $result_cb6 = mysqli_query($link, $sql_cb6);
                                echo "<select name='idCentroFormacion' id='cb6' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb6)) {
                                    if ($idCentroFormacion != $row['idCentroFormacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idCentroFormacion'] . "'>" . $row['nombreLargoCentroFormacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idCentroFormacion'] . "'>" . $row['nombreLargoCentroFormacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                        </div>

                        <div class="form-group">
                            <label>Ficha de formación</label>
                            <?php
                                $sql_cb7 = "SELECT idFichaFormacion, codigoFichaFormacion FROM fichas_formacion";
                                $result_cb7 = mysqli_query($link, $sql_cb7);
                                echo "<select name='idFichaFormacion' id='cb7' class='combo-box form-control'>";
                                while($row = mysqli_fetch_array($result_cb7)) {
                                    if ($idFichaFormacion != $row['idFichaFormacion'])
                                    {
                                        echo "<option class='item-combo-box' value='" . $row['idFichaFormacion'] . "'>" . $row['codigoFichaFormacion'] . "</option>";
                                    } else {
                                        echo "<option class='item-combo-box' selected value='" . $row['idFichaFormacion'] . "'>" . $row['codigoFichaFormacion'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
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

                        <input type="hidden" name="idAprendiz" value="<?php echo $idAprendiz; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="Actualizar">
                            <a href="aprendices-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
