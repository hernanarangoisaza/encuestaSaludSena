<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$idEncuesta = "";
$idPreguntaEncuesta = "";
$respuestaSiNo = "";
$estado = "";
$auditoria = "";

$idEncuesta_err = "";
$idPreguntaEncuesta_err = "";
$respuestaSiNo_err = "";
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
		$idPreguntaEncuesta = trim($_POST["idPreguntaEncuesta"]);
		$respuestaSiNo = trim($_POST["respuestaSiNo"]);
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
          exit('Something weird happened'); //something a user can understand
        }
        $stmt = $pdo->prepare("INSERT INTO respuestas_encuesta (idEncuesta,idPreguntaEncuesta,respuestaSiNo,estado,auditoria) VALUES (?,?,?,?,?)"); 
        
        if($stmt->execute([ $idEncuesta,$idPreguntaEncuesta,$respuestaSiNo,$estado,$auditoria  ])) {
                $stmt = null;
                header("location: respuestas_encuesta-index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add a record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Id Encuesta</label>
                            <input type="number" name="idEncuesta" class="form-control" value="<?php echo $idEncuesta; ?>">
                            <span class="form-text"><?php echo $idEncuesta_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Id Pregunta</label>
                            <input type="number" name="idPreguntaEncuesta" class="form-control" value="<?php echo $idPreguntaEncuesta; ?>">
                            <span class="form-text"><?php echo $idPreguntaEncuesta_err; ?></span>
                        </div>
						<div class="form-group">
                            <label>Respuesta</label>
                            <input type="number" name="respuestaSiNo" class="form-control" value="<?php echo $respuestaSiNo; ?>">
                            <span class="form-text"><?php echo $respuestaSiNo_err; ?></span>
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

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="respuestas_encuesta-index.php" class="btn btn-secondary">Cancel</a>
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