<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: index.php");
    exit();
//foreach ($_SESSION as $key=>$val)
//echo $key." ".$val."<br/>";
}
?>

<!DOCTYPE html>

<html lang="es">

    <head>

        <meta charset="UTF-8" />
        <title>Sistema de encuestas para registro de síntomas y temperatura en el SENA - CDITI</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="css/estilos.css" />
        <link rel="icon" href="imagenes/favicon.ico" type="image/png" />

    </head>

    <body>

        <div class="contenedor-principal">

            <div class="contenedor-izquierdo">

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

            <div class="contenedor-derecho">

                <div class="menu">

                    <div class="titulo-menu">MENÚ</div>

                    <ul class="nav">

                        <li class="item-menu-principal"><a href="encuesta_signos-index.php">Encuesta de signos</a></li>
                        <li class="item-menu-principal"><a href="tomas_temperatura-index.php">Tomas de temperatura</a></li>
                        <li class="item-menu-principal"><a href="preguntas_encuesta-index.php">Preguntas de la encuesta</a></li>
                        <li class="item-menu-principal"><a href="respuestas_encuesta-index.php">Respuestas de la encuesta</a></li>

                        <li class="item-menu-principal"><a href="aprendices-index.php">Aprendices</a></li>

                        <li class="item-menu-principal"><a href="centros_formacion-index.php">Centros de formación</a></li>
                        <li class="item-menu-principal"><a href="programas_formacion-index.php">Programas de formación</a></li>
                        <li class="item-menu-principal"><a href="fichas_formacion-index.php">Fichas de formación</a></li>
                        <li class="item-menu-principal"><a href="horarios-index.php">Horarios</a></li>

                        <li class="item-menu-principal"><a href="departamentos-index.php">Departamentos</a></li>
                        <li class="item-menu-principal"><a href="municipios-index.php">Municipios</a></li>

                        <li class="item-menu-principal"><a href="tipos_generos-index.php">Tipos de géneros</a></li>
                        <li class="item-menu-principal"><a href="tipos_identificacion-index.php">Tipos de identificación</a></li>
                        <li class="item-menu-principal"><a href="tipos_vinculaciones_sena-index.php">Tipos de vinculaciones</a></li>

                        <li class="item-menu-principal"><a href="usuarios-index.php">Usuarios del sistema</a></li>
                        <li class="item-menu-principal"><a href="roles_sistema-index.php">Roles del sistema</a></li>

                        <li class="item-menu-principal"><a href="#">Autenticarse</a></li>
                        <li class="item-menu-principal"><a href="#">Cerrar sesión</a></li>

                    </ul>

                </div>

            </div>

        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script src="js/libreria.js" type="text/javascript"></script>

        <script>
            $(document).ready(function () {});
        </script>

    </body>

</html>
