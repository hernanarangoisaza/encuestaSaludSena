<?php
session_start();

// Include config file
require_once "core/config.php";

// remove all session variables
session_unset();

if (isset($_POST['login'])) {
 
    // Check existence of id parameter before processing further

    if(isset($_POST["correo"]) && isset($_POST["identificacion"])){
        
		// Get URL parameter

		$correo = $_POST['correo'];
		$identificacion = $_POST['identificacion'];
		
        // Prepare a select statement
        $sql = "SELECT US.*, 
            RS.nombreCorto AS 'nombreCortoRolSistema', 
            RS.permisos AS 'permisosRolSistema', 
            RS.restricciones AS 'restriccionesRolSistema' 
            FROM usuarios US
            LEFT JOIN roles_sistema RS ON RS.idRolSistema = US.idRolSistema
            WHERE email=?";
                            
		if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
            
			// Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmtPDO, "s", $correo);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmtPDO)){
				
                $resultPDO = mysqli_stmt_get_result($stmtPDO);

                if(mysqli_num_rows($resultPDO) == 1){
					
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

					if (password_verify($identificacion, $row['passwordSistema'])) {

                        // USUARIO HA INGRESADO LAS CREDENCIALES CORRECTAS
                        $_SESSION['login'] = true;
                        
                        // Retrieve individual field value
                        $_SESSION['idUsuario'] = $row['idUsuario'];
                        $_SESSION['emailUsuario'] = $row["email"];   
                        $_SESSION['idRolSistema'] = $row["idRolSistema"];  	
                        $_SESSION['nombreCortoRolSistema'] = $row["nombreCortoRolSistema"];   
                        $_SESSION['nombreCompleto'] = $row["nombreCompleto"];   
                        $_SESSION['permisosRolSistema'] = $row["permisosRolSistema"];   
                        $_SESSION['restriccionesRolSistema'] = $row["restriccionesRolSistema"];   
                        header("location: core/menu.php");

					} else {

                        // USUARIO EXISTE PERO CONTRASEÑA NO COINCIDE.

                        $_SESSION['login'] = false;
                        header("location: core/menu.php");

					}

                } else{

					// USUARIO NO EXISTE Y NO ES POSIBLE VALIDAR CONTRASEÑA.

                    $_SESSION['login'] = false;
                    header("location: core/menu.php");

                }

            } else{

                echo "Oops! Algo falló. Por favor intente de nuevo.";

            }
        }

        // Close statement
        mysqli_stmt_close($stmtPDO);

        // Close connection
        mysqli_close($linkMYSQLI);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: core/error.php");
        exit();
    }

}

?>

<!DOCTYPE html>

<html lang="es">

    <head>
        <meta charset="UTF-8" />
        <title>Sistema de encuestas para registro de síntomas y temperatura en el SENA - CDITI</title>
        <link rel="stylesheet" href="css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="css/estilos.css" />
        <link rel="icon" href="imagenes/favicon.ico" type="image/png" />
    </head>

    <body>

        <div class="contenedor-principal">

            <div class="contenedor-izquierdo-login">

                <div class="contenedor-superior">

                    <div class="intro">
                        SENA - CDITI<br />
                        Gestión de encuestas para registro de síntomas y temperatura<br />
                        COVID-19
                    </div>

                </div>

                <div class="contenedor-medio">

                    <img src="imagenes/logo-sena-naranja.png" class="logo-responsive" />

                </div>

                <div class="contenedor-inferior">

                    <div class="">

                        <span>Diseñado y desarrollado por CDITI</span>
                        <br>
                        <span>ADSI - Ficha 1752586</span>
                        <br>
                        <span>Dosquebradas, Risaralda. Diciembre de 2020</span>
                        
                    </div>
                    
                </div>

            </div>

            <div class="contenedor-derecho-login">

                <section class="section-login">

                    <div class="container-login">

                        <div class="row">

                            <div class="col-md-12 mx-auto">

                                <div class="page-header titulo-login">
                                    <h1>CREDENCIALES</h1>
                                </div>

                                <form method="post" action="" name="signin-form">

                                    <div class="form-group">
                                        <label>Correo Institucional</label>
                                        <input type="email" name="correo" class="form-control" autocomplete="off" required />
                                    </div>

                                    <div class="form-group">
                                        <label>Número de Identificación</label>
                                        <input type="password" name="identificacion" class="form-control" autocomplete="off" required />
                                    </div>

                                    <button type="submit" name="login" value="login" class="btn btn-secondary btn-login">Validar</button>

                                </form>   

                            </div>

                        </div>

                    </div>

                </section>

            </div>

        </div>

        <script src="js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
        <script src="js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="js/libreria.js" type="text/javascript"></script>

        <script>
            $(document).ready(function () {});
        </script>

    </body>

</html>


