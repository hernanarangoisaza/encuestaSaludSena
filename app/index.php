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
        $sql = "SELECT PE.* 
            FROM personas PE
            WHERE email=?
              AND identificacion=?";
                            
        if($stmtPDO = mysqli_prepare($linkMYSQLI, $sql)){
            
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmtPDO, "ss", $correo, $identificacion);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmtPDO)){
                
                $resultPDO = mysqli_stmt_get_result($stmtPDO);

                if(mysqli_num_rows($resultPDO) == 1){
                    
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($resultPDO, MYSQLI_ASSOC);

                    if ($identificacion == $row['identificacion']) {

                        // USUARIO HA INGRESADO LAS CREDENCIALES CORRECTAS
                        $_SESSION['login'] = true;
                        
                        // Retrieve individual field value
                        $_SESSION['idPersona'] = $row['idPersona'];
                        $_SESSION['emailUsuario'] = $row["email"];   
                        $_SESSION['nombreCompleto'] = $row["nombreCompleto"];   
                        $_SESSION['permisosRolSistema'] = "[usuario-encuestas]";   
                        $_SESSION['restriccionesRolSistema'] = "[n/a]";
                        $_SESSION['modoLogin'] = "normal";     
                        header("location: core/menu.php");

                    } else {

                        // USUARIO EXISTE PERO CONTRASEÑA NO COINCIDE.

                        $_SESSION['login'] = false;
                        header("location: index.php");

                    }

                } else{

                    // USUARIO NO EXISTE Y NO ES POSIBLE VALIDAR CONTRASEÑA.

                    $_SESSION['login'] = false;
                    header("location: index.php");

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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Sistema de encuestas para registro de síntomas y temperatura en <?php echo $sedeUso ?> - <?php echo $regionalSedes ?></title>
        <link rel="stylesheet" href="css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" />
        <link rel="stylesheet" href="css/estilos.css" />
        <link rel="icon" href="imagenes/favicon.ico" type="image/png" />

    </head>

    <body class="fondo-inicial">

        <div class="container-fluid h-100">

            <div class="row h-100">

                <div class="col-sm-12 col-xs-12 d-flex flex-column justify-content-between align-items-center">

                    <div class="row mt-3">

                        <div class="col-12 w-100">

                            <div class="text-center">

                                <H3><?php echo $sedeUso ?></H3>
                                <H5>Sistema de encuestas para registro de síntomas y temperatura COVID-19</H5>

                            </div>

                        </div>

                    </div>

                    <div class="row w-100 justify-content-center">
                        
                        <div class="col-sm-2 col-x6-12 text-center">

                            <img src="imagenes/logo-sena-naranja.png" class="img-fluid" width="175">

                        </div>

                        <div class="col-sm-3 col-xs-12 text-center">

                           <form method="post" action="" name="signin-form">

                                <div class="form-group">
                                    <label>Correo Institucional</label>
                                    <input type="email" name="correo" class="form-control campo-login" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <label>Número de Identificación</label>
                                    <input type="password" name="identificacion" class="form-control campo-login" autocomplete="off" required />
                                </div>

                                <button type="submit" name="login" value="login" class="btn btn-info btn-login btn-xl btn-block">Validar credenciales</button>

                            </form>  

                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-12 w-100">

                            <div class="text-center">

                                <div>Diseñado y desarrollado por</div>
                                <div><strong> - ADSI Ficha 1752586 - </strong></div>
                                <div>CDITI, Dosquebradas, Risaralda. Diciembre de 2020.</div>

                            </div>  
                                                      
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <script src="js/jquery-3.5.1.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
        <script src="js/bootstrap.bundle-4.5.3.min.js" integrity="sha512-iceXjjbmB2rwoX93Ka6HAHP+B76IY1z0o3h+N1PeDtRSsyeetU3/0QKJqGyPJcX63zysNehggFwMC/bi7dvMig==" crossorigin="anonymous"></script>
        <script src="js/libreria.js" type="text/javascript"></script>

        <script>
            $(document).ready(function () {});
        </script>

    </body>
</html>
