<?php
// Include config file
require_once "config.php";
?>

<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../core/menu.php");
    exit();
}
// foreach ($_SESSION as $key=>$val)
// echo $key." ".$val."<br/>";
// echo $_SESSION['permisosRolSistema'];
?>

<?php

    $_SESSION["rutaRegresarA"] = '../core/menu.php';

    if ($_SESSION['modoLogin'] == "normal") {

        $visibilidadContenedorIzquierdo = "ocultar-contenedor-izquierdo";
        $ajusteContenedorCentral = "ajuste-contenedor-central";
        $ajusteContenedorDerecho = "ajuste-contenedor-derecho";
    } else {
        $visibilidadContenedorIzquierdo = "";
        $ajusteContenedorCentral = "";
        $ajusteContenedorDerecho = "";
    } 

?>

<!DOCTYPE html>

<html lang="es">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Sistema de encuestas para registro de síntomas y temperatura en <?php echo $sedeUso ?> - <?php echo $regionalSedes ?></title>
        <link rel="stylesheet" href="../css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" />
        <link rel="stylesheet" href="../css/iconos/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../css/estilos.css" />
        <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />
    </head>

    <body class="fondo-inicial">

        <div class="d-flex" id="wrapper">
        
            <!-- Sidebar -->
            <div class="bg-light border-right" id="sidebar-wrapper">

                <?php
                    include "panel-opciones.html";
                ?>

            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">

                <div class="container-fluid h-100">

                    <div class="row h-100">

                        <div class="col-sm-12 col-xs-12 h-100 d-flex flex-column justify-content-between align-items-center">
                            
                            <div class="icon align-self-start">
                                <i id="menu-toggle" class="bi bi-justify"></i>
                            </div>

                            <div class="row">
                                <div class="col-12 w-100">
                                    <div class="text-center">
                                        <h3><?php echo $sedeUso ?></h3>
                                        <h5>Sistema de encuestas para registro de síntomas y temperatura COVID-19</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row w-100 justify-content-center">
                                <div class="col-sm-2 col-x6-12 text-center">
                                    <img src="../imagenes/logo-sena-naranja.png" class="img-fluid" width="175" />
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

            </div>

        </div>

        <script src="../js/jquery-3.5.1.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
        <script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha512-iceXjjbmB2rwoX93Ka6HAHP+B76IY1z0o3h+N1PeDtRSsyeetU3/0QKJqGyPJcX63zysNehggFwMC/bi7dvMig==" crossorigin="anonymous"></script>
        <script src="../js/libreria.js" type="text/javascript"></script>

        <script>
            $(document).ready(function () {});
        </script>

        <!-- Menu Toggle Script -->
        <script>
        $("#menu-toggle").click(function(e) {
          e.preventDefault();
          $("#wrapper").toggleClass("toggled");
        });
        </script>

    </body>
</html>
