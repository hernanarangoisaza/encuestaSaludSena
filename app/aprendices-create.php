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
        $idTipoVinculacion = trim($_POST["idTipoVinculacion"]);
		$nombreCompleto = trim($_POST["nombreCompleto"]);
		$idTipoIdentificacion = trim($_POST["idTipoIdentificacion"]);
		$identificacion = trim($_POST["identificacion"]);
		$email = trim($_POST["email"]);
		$telefonoPersonal = trim($_POST["telefonoPersonal"]);
		$telefonoAcudiente = trim($_POST["telefonoAcudiente"]);
		$fechaNacimiento = trim($_POST["fechaNacimiento"]);
		$idTipoGenero = trim($_POST["idTipoGenero"]);
		$direccionResidencia = trim($_POST["direccionResidencia"]);
		$idMunicipio = trim($_POST["idMunicipio"]);
		$idDepartamento = trim($_POST["idDepartamento"]);
		$idCentroFormacion = trim($_POST["idCentroFormacion"]);
		$idFichaFormacion = trim($_POST["idFichaFormacion"]);
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
          exit('Algo extraño sucedió'); //something a user can understand
        }
        $stmt = $pdo->prepare("INSERT INTO aprendices (idTipoVinculacion,nombreCompleto,idTipoIdentificacion,identificacion,email,telefonoPersonal,telefonoAcudiente,fechaNacimiento,idTipoGenero,direccionResidencia,idMunicipio,idDepartamento,idCentroFormacion,idFichaFormacion,estado,auditoria) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"); 
        
        if($stmt->execute([ $idTipoVinculacion,$nombreCompleto,$idTipoIdentificacion,$identificacion,$email,$telefonoPersonal,$telefonoAcudiente,$fechaNacimiento,$idTipoGenero,$direccionResidencia,$idMunicipio,$idDepartamento,$idCentroFormacion,$idFichaFormacion,$estado,$auditoria  ])) {
                $stmt = null;
                header("location: aprendices-index.php");
            } else{
                echo "Algo falló. Por favor intente de nuevo.";
            }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crear Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css" />
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mx-auto">
                    <div class="page-header">
                        <h2>Crear Registro</h2>
                    </div>
                    <p>Por favor diligencie todo el formulario para adicionar un registro a la Base de Datos</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Id Tipo de vinculación</label>
                            <input type="number" name="idTipoVinculacion" class="form-control" value="<?php echo $idTipoVinculacion; ?>">
                            <span class="form-text"><?php echo $idTipoVinculacion_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Nombre completo</label>
                            <input type="text" name="nombreCompleto" maxlength="50"class="form-control" value="<?php echo $nombreCompleto; ?>">
                            <span class="form-text"><?php echo $nombreCompleto_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Tipo de identificación</label>
                            <input type="number" name="idTipoIdentificacion" class="form-control" value="<?php echo $idTipoIdentificacion; ?>">
                            <span class="form-text"><?php echo $idTipoIdentificacion_err; ?></span>
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
                            <label>Id Género</label>
                            <input type="number" name="idTipoGenero" class="form-control" value="<?php echo $idTipoGenero; ?>">
                            <span class="form-text"><?php echo $idTipoGenero_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Dirección de la residencia</label>
                            <input type="text" name="direccionResidencia" maxlength="50"class="form-control" value="<?php echo $direccionResidencia; ?>">
                            <span class="form-text"><?php echo $direccionResidencia_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Municipio</label>
                            <input type="number" name="idMunicipio" class="form-control" value="<?php echo $idMunicipio; ?>">
                            <span class="form-text"><?php echo $idMunicipio_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Departamento</label>
                            <input type="number" name="idDepartamento" class="form-control" value="<?php echo $idDepartamento; ?>">
                            <span class="form-text"><?php echo $idDepartamento_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Centro de formación</label>
                            <input type="number" name="idCentroFormacion" class="form-control" value="<?php echo $idCentroFormacion; ?>">
                            <span class="form-text"><?php echo $idCentroFormacion_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Ficha de formación</label>
                            <input type="number" name="idFichaFormacion" class="form-control" value="<?php echo $idFichaFormacion; ?>">
                            <span class="form-text"><?php echo $idFichaFormacion_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Estado del registro</label>
                            <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                            <span class="form-text"><?php echo $estado_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Fecha/Hora de auditoría</label>
                            <input type="text" name="auditoria" class="form-control" value="<?php echo $auditoria; ?>">
                            <span class="form-text"><?php echo $auditoria_err; ?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="aprendices-index.php" class="btn btn-secondary">Cancelar</a>
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