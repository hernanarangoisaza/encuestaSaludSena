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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Sistema de encuestas para registro de síntomas y temperatura en <?php echo $sedeUso ?> - <?php echo $regionalSedes ?></title>
        <link rel="stylesheet" href="../css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/estilos.css" />
        <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />

    </head>

    <body>

        <div class="contenedor-principal">

            <div class="contenedor-izquierdo <?php echo $visibilidadContenedorIzquierdo ?>">
            
                <?php
                    include "estadisticas-aforo.html";
                ?>

            </div>

            <div class="contenedor-central <?php echo $ajusteContenedorCentral ?>">

                <div class="contenedor-superior">

                    <div class="intro">
                        SENA - CDITI<br />
                        Gestión de encuestas para registro de síntomas y temperatura<br />
                        COVID-19
                    </div>

                </div>

                <div class="contenedor-medio">

                    <img src="../imagenes/logo-sena-naranja.png" class="logo-responsive" />

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

            <div class="contenedor-derecho <?php echo $ajusteContenedorDerecho ?>">

                <div class="contenedor-usuario">

                    <?php
                        echo "<p class='bienvenido'>Bienvenid@</p>";
                        echo "<p class='nombre-usuario'>" . $_SESSION['nombreCompleto'] . "</p>";
                        echo "<p class='email-usuario'>" . $_SESSION['emailUsuario'] . "</p>";
                    ?>

                </div>

                <div class="contenedor-menu">

                    <div class="menu">

                        <div class="titulo-menu">MENÚ PRINCIPAL</div>

                        <ul class="nav">

                            <?php
                            if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '')) {
                                echo '<li class="item-menu-principal"><a href="../formato_toma_temperatura/registrar-temperatura.php">Registrar Temperatura</a></li>';
                            }
                            ?>

                            <?php
                            if (!strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '') {
                                echo '<li class="item-menu-principal"><a href="../tomas_temperatura/tomas_temperatura-index.php">Gestión de Tomas de temperatura</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '') {
                                echo '<li class="item-menu-principal"><a href="../formato_encuesta/encuesta.php">Registrar Encuesta</a></li>';
                            }
                            ?>

                            <?php
                            if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-encuestas]") != '')) {
                                echo '<li class="item-menu-principal"><a href="../encuesta_signos/encuesta_signos-index.php">Gestión de Encuestas de signos</a></li>';
                            }
                            ?>

                            <?php
                            if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '')) {
                                echo '<li class="item-menu-principal"><a href="../respuestas_encuesta/respuestas_encuesta-index.php">Gestión de Respuestas de la encuesta</a></li>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../preguntas_encuesta/preguntas_encuesta-index.php">Maestro de Preguntas de la encuesta</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../horarios/horarios-index.php">Maestro de Horarios</a></li>';
                            }
                            ?>

                            <?php
                            if (!strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '') {
                                echo '<li class="item-menu-principal"><a href="../personas/personas-index.php">Maestro de Personas</a></li>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../centros_formacion/centros_formacion-index.php">Maestro de Centros de formación</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../programas_formacion/programas_formacion-index.php">Maestro de Programas de formación</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../fichas_formacion/fichas_formacion-index.php">Maestro de Fichas de formación</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../tipos_generos/tipos_generos-index.php">Maestro de Tipos de géneros</a></li>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../tipos_identificacion/tipos_identificacion-index.php">Maestro de Tipos de identificación</a></li>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../tipos_vinculaciones_sena/tipos_vinculaciones_sena-index.php">Maestro de Tipos de vinculaciones</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../roles_sistema/roles_sistema-index.php">Maestro de Roles del sistema</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../usuarios/usuarios-index.php">Maestro de Usuarios del sistema</a></li>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../departamentos/departamentos-index.php">Maestro de Departamentos</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="../municipios/municipios-index.php">Maestro de Municipios</a></li>';
                            }
                            ?>

                            <?php
                            if ($_SESSION['modoLogin'] == "normal") {
                                echo '<li class="item-menu-principal"><a href="../personas/personas-read.php?idPersona=' . $_SESSION['idPersona'] . '">Información Personal</a></li>';
                            } if ($_SESSION['modoLogin'] == "backend") {
                                echo '<li class="item-menu-principal"><a href="../usuarios/usuarios-read.php?idUsuario=' . $_SESSION['idUsuario'] . '">Información Personal</a></li>';
                            }
                            ?>

                            <li class="item-menu-principal"><a href="cerrar-sesion.php">Cerrar sesión</a></li>

                        </ul>

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

    </body>

</html>
