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

                <div class="sidebar-heading text-center">MENÚ PRINCIPAL</div>

                <div class="list-group list-group-flush">

                    <a class="list-group-item list-group-item-action bg-light" href="#">Inicio</a>

                    <?php
                    if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') or
                        (strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '')) {
                        echo '<a class="list-group-item list-group-item-action bg-light" href="../formato_toma_temperatura/registrar-temperatura.php">Registrar Temperatura</a>';
                    }
                    ?>

                    <?php
                    if (strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '') {
                        echo '<a class="list-group-item list-group-item-action bg-light" href="../formato_encuesta/encuesta.php">Registrar Encuesta</a>';
                    }
                    ?>

                    <li class="nav-item dropdown">

                        <a class="nav-link list-group-item list-group-item-action bg-light" href="#" id="navbarDropdownMenuLink3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Maestros generales&nbsp;&nbsp;
                            <i class="bi bi-arrow-return-left size-menu"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">

                            <?php
                            if (!strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '') {
                                echo '<a class="dropdown-item" href="../tomas_temperatura/tomas_temperatura-index.php">Tomas de temperatura</a>';
                            }
                            ?>

                            <?php
                            if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-encuestas]") != '')) {
                                echo '<a class="dropdown-item" href="../encuesta_signos/encuesta_signos-index.php">Encuestas de signos</a>';
                            }
                            ?>

                            <?php
                            if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '')) {
                                echo '<a class="dropdown-item" href="../respuestas_encuesta/respuestas_encuesta-index.php">Respuestas de la encuesta</a>';
                            }
                            ?>

                        </div>

                    </li>

                    <li class="nav-item dropdown">

                        <a class="nav-link list-group-item list-group-item-action bg-light" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Maestros del sistema
                            <i class="bi bi-arrow-return-left size-menu"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../preguntas_encuesta/preguntas_encuesta-index.php">Preguntas de la encuesta</a>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../horarios/horarios-index.php">Horarios</a>';
                            }
                            ?>

                            <?php
                            if (!strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '') {
                                echo '<a class="dropdown-item" href="../personas/personas-index.php">Personas</a>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../centros_formacion/centros_formacion-index.php">Centros de formación</a>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../programas_formacion/programas_formacion-index.php">Programas de formación</a>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../fichas_formacion/fichas_formacion-index.php">Fichas de formación</a>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../tipos_generos/tipos_generos-index.php">Tipos de géneros</a>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../tipos_identificacion/tipos_identificacion-index.php">Tipos de identificación</a>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../tipos_vinculaciones_sena/tipos_vinculaciones_sena-index.php">Tipos de vinculaciones</a>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../roles_sistema/roles_sistema-index.php">Roles del sistema</a>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../usuarios/usuarios-index.php">Usuarios del sistema</a>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../departamentos/departamentos-index.php">Departamentos</a>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<a class="dropdown-item" href="../municipios/municipios-index.php">Municipios</a>';
                            }
                            ?>

                        </div>

                    </li>

                    <li class="nav-item dropdown">

                        <a class="nav-link list-group-item list-group-item-action bg-light" href="#" id="navbarDropdownMenuLink3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Estadísticas de Aforo
                            <i class="bi bi-arrow-return-left size-menu"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
                            <a class="dropdown-item" href="#">Mañana</a>
                            <a class="dropdown-item" href="#">Tarde</a>
                            <a class="dropdown-item" href="#">Noche</a>
                            <a class="dropdown-item" href="#">Mixta Mañana-Tarde</a>
                            <a class="dropdown-item" href="#">Mixta Tarde-Noche</a>
                            <a class="dropdown-item" href="#">Completa</a>
                        </div>

                    </li>

                    <?php
                    if ($_SESSION['modoLogin'] == "normal") {
                        echo '<a class="list-group-item list-group-item-action bg-light" href="../personas/personas-read.php?idPersona=' . $_SESSION['idPersona'] . '">Información Personal</a>';
                    } if ($_SESSION['modoLogin'] == "backend") {
                        echo '<a class="list-group-item list-group-item-action bg-light" href="../usuarios/usuarios-read.php?idUsuario=' . $_SESSION['idUsuario'] . '">Información Personal</a>';
                    }
                    ?>

                    <a class="list-group-item list-group-item-action bg-light" href="cerrar-sesion.php">Cerrar sesión</a>

                </div>

            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">


                <div class="container-fluid h-100">

                    <div class="row h-100">

                        <div class="icon">
                            <i id="menu-toggle" class="bi bi-justify"></i>
                        </div>

                        <div class="col-sm-12 col-xs-12 h-100 d-flex flex-column justify-content-between align-items-center">
                            
                            <div class="row mt-3">
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
