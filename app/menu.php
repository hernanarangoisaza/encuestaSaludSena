<?php
// Include config file
require_once "config.php";
?>

<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}
// foreach ($_SESSION as $key=>$val)
// echo $key." ".$val."<br/>";
// echo $_SESSION['permisosRolSistema'];
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

                <div class="titulo-aforo">

                    ESTADÍSTICAS DE AFORO

                </div>

                <div class="secciones-aforo">

                    <span class="titulo-seccion-aforo">Mañana</span>

                    <div class="seccion-datos">
                    
                        <span class="subtitulo-seccion-aforo">Aforo: </span>
                        
                        <span class="dato-seccion-aforo">
                            
                            <?php 
                                echo $aforoJornada;
                            ?>

                        </span>
                        
                        <span class="subtitulo-seccion-aforo">- Registrados: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 1;
                                $sql_dt1 = "SELECT COUNT(*) registradosJornada1
                                            FROM encuesta_signos ES
                                            LEFT JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraDiligenciamiento) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt1 = mysqli_query($link, $sql_dt1);
                                $row = mysqli_fetch_array($result_dt1);
                                echo ($row['registradosJornada1'] == '') ? (0) : ($row['registradosJornada1']);
                            ?>

                        </span>
                        
                        <br>

                        <span class="subtitulo-seccion-aforo">Ingresaron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 1;
                                $sql_dt2 = "SELECT COUNT(*) ingresaronJornada1
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt2 = mysqli_query($link, $sql_dt2);
                                $row = mysqli_fetch_array($result_dt2);
                                echo ($row['ingresaronJornada1'] == '') ? (0) : ($row['ingresaronJornada1']);
                            ?>

                        </span>

                        <span class="subtitulo-seccion-aforo">- Salieron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 1;
                                $sql_dt3 = "SELECT COUNT(*) salieronJornada1
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaSalida) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt3 = mysqli_query($link, $sql_dt3);
                                $row = mysqli_fetch_array($result_dt3);
                                echo ($row['salieronJornada1'] == '') ? (0) : ($row['salieronJornada1']);
                            ?>

                        </span>

                        <br>
                        
                        <span class="subtitulo-seccion-aforo">Permanecen: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 1;
                                $sql_dt4 = "SELECT COUNT(*) permanecenJornada1
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND fechaHoraTomaSalida IS NULL
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt4 = mysqli_query($link, $sql_dt4);
                                $row = mysqli_fetch_array($result_dt4);
                                echo ($row['permanecenJornada1'] == '') ? (0) : ($row['permanecenJornada1']);
                            ?>

                        </span>
                    
                    </div>
                
                </div>

                <div class="secciones-aforo">

                    <span class="titulo-seccion-aforo">Tarde</span>

                    <div class="seccion-datos">
                    
                        <span class="subtitulo-seccion-aforo">Aforo: </span>
                        
                        <span class="dato-seccion-aforo">
                            
                            <?php 
                                echo $aforoJornada;
                            ?>

                        </span>
                        
                        <span class="subtitulo-seccion-aforo">- Registrados: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 2;
                                $sql_dt1 = "SELECT COUNT(*) registradosJornada2
                                            FROM encuesta_signos ES
                                            LEFT JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraDiligenciamiento) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt1 = mysqli_query($link, $sql_dt1);
                                $row = mysqli_fetch_array($result_dt1);
                                echo ($row['registradosJornada2'] == '') ? (0) : ($row['registradosJornada2']);
                            ?>

                        </span>
                        
                        <br>

                        <span class="subtitulo-seccion-aforo">Ingresaron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 2;
                                $sql_dt2 = "SELECT COUNT(*) ingresaronJornada2
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt2 = mysqli_query($link, $sql_dt2);
                                $row = mysqli_fetch_array($result_dt2);
                                echo ($row['ingresaronJornada2'] == '') ? (0) : ($row['ingresaronJornada2']);
                            ?>

                        </span>

                        <span class="subtitulo-seccion-aforo">- Salieron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 2;
                                $sql_dt3 = "SELECT COUNT(*) salieronJornada2
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaSalida) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt3 = mysqli_query($link, $sql_dt3);
                                $row = mysqli_fetch_array($result_dt3);
                                echo ($row['salieronJornada2'] == '') ? (0) : ($row['salieronJornada2']);
                            ?>

                        </span>

                        <br>
                        
                        <span class="subtitulo-seccion-aforo">Permanecen: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 2;
                                $sql_dt4 = "SELECT COUNT(*) permanecenJornada2
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND fechaHoraTomaSalida IS NULL
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt4 = mysqli_query($link, $sql_dt4);
                                $row = mysqli_fetch_array($result_dt4);
                                echo ($row['permanecenJornada2'] == '') ? (0) : ($row['permanecenJornada2']);
                            ?>

                        </span>

                    </div>

                </div>

                <div class="secciones-aforo">

                    <span class="titulo-seccion-aforo">Noche</span>

                    <div class="seccion-datos">
                    
                        <span class="subtitulo-seccion-aforo">Aforo: </span>
                        
                        <span class="dato-seccion-aforo">
                            
                            <?php 
                                echo $aforoJornada;
                            ?>

                        </span>
                        
                        <span class="subtitulo-seccion-aforo">- Registrados: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 3;
                                $sql_dt1 = "SELECT COUNT(*) registradosJornada3
                                            FROM encuesta_signos ES
                                            LEFT JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraDiligenciamiento) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt1 = mysqli_query($link, $sql_dt1);
                                $row = mysqli_fetch_array($result_dt1);
                                echo ($row['registradosJornada3'] == '') ? (0) : ($row['registradosJornada3']);
                            ?>

                        </span>
                        
                        <br>

                        <span class="subtitulo-seccion-aforo">Ingresaron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 3;
                                $sql_dt2 = "SELECT COUNT(*) ingresaronJornada3
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt2 = mysqli_query($link, $sql_dt2);
                                $row = mysqli_fetch_array($result_dt2);
                                echo ($row['ingresaronJornada3'] == '') ? (0) : ($row['ingresaronJornada3']);
                            ?>

                        </span>

                        <span class="subtitulo-seccion-aforo">- Salieron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 3;
                                $sql_dt3 = "SELECT COUNT(*) salieronJornada3
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaSalida) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt3 = mysqli_query($link, $sql_dt3);
                                $row = mysqli_fetch_array($result_dt3);
                                echo ($row['salieronJornada3'] == '') ? (0) : ($row['salieronJornada3']);
                            ?>

                        </span>

                        <br>
                        
                        <span class="subtitulo-seccion-aforo">Permanecen: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 3;
                                $sql_dt4 = "SELECT COUNT(*) permanecenJornada3
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND fechaHoraTomaSalida IS NULL
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt4 = mysqli_query($link, $sql_dt4);
                                $row = mysqli_fetch_array($result_dt4);
                                echo ($row['permanecenJornada3'] == '') ? (0) : ($row['permanecenJornada3']);
                            ?>

                        </span>

                    </div>

                </div>

                <div class="secciones-aforo">

                    <span class="titulo-seccion-aforo">Mixta mañana-tarde</span>

                    <div class="seccion-datos">
                    
                        <span class="subtitulo-seccion-aforo">Aforo: </span>
                        
                        <span class="dato-seccion-aforo">
                            
                            <?php 
                                echo $aforoJornada;
                            ?>

                        </span>
                        
                        <span class="subtitulo-seccion-aforo">- Registrados: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 4;
                                $sql_dt1 = "SELECT COUNT(*) registradosJornada4
                                            FROM encuesta_signos ES
                                            LEFT JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraDiligenciamiento) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt1 = mysqli_query($link, $sql_dt1);
                                $row = mysqli_fetch_array($result_dt1);
                                echo ($row['registradosJornada4'] == '') ? (0) : ($row['registradosJornada4']);
                            ?>

                        </span>
                        
                        <br>

                        <span class="subtitulo-seccion-aforo">Ingresaron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 4;
                                $sql_dt2 = "SELECT COUNT(*) ingresaronJornada4
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt2 = mysqli_query($link, $sql_dt2);
                                $row = mysqli_fetch_array($result_dt2);
                                echo ($row['ingresaronJornada4'] == '') ? (0) : ($row['ingresaronJornada4']);
                            ?>

                        </span>

                        <span class="subtitulo-seccion-aforo">- Salieron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 4;
                                $sql_dt3 = "SELECT COUNT(*) salieronJornada4
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaSalida) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt3 = mysqli_query($link, $sql_dt3);
                                $row = mysqli_fetch_array($result_dt3);
                                echo ($row['salieronJornada4'] == '') ? (0) : ($row['salieronJornada4']);
                            ?>

                        </span>

                        <br>
                        
                        <span class="subtitulo-seccion-aforo">Permanecen: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 4;
                                $sql_dt4 = "SELECT COUNT(*) permanecenJornada4
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND fechaHoraTomaSalida IS NULL
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt4 = mysqli_query($link, $sql_dt4);
                                $row = mysqli_fetch_array($result_dt4);
                                echo ($row['permanecenJornada4'] == '') ? (0) : ($row['permanecenJornada4']);
                            ?>

                        </span>

                    </div>

                </div>

                <div class="secciones-aforo">

                    <span class="titulo-seccion-aforo">Mixta tarde-noche</span>

                    <div class="seccion-datos">
                    
                        <span class="subtitulo-seccion-aforo">Aforo: </span>
                        
                        <span class="dato-seccion-aforo">
                            
                            <?php 
                                echo $aforoJornada;
                            ?>

                        </span>
                        
                        <span class="subtitulo-seccion-aforo">- Registrados: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 5;
                                $sql_dt1 = "SELECT COUNT(*) registradosJornada5
                                            FROM encuesta_signos ES
                                            LEFT JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraDiligenciamiento) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt1 = mysqli_query($link, $sql_dt1);
                                $row = mysqli_fetch_array($result_dt1);
                                echo ($row['registradosJornada5'] == '') ? (0) : ($row['registradosJornada5']);
                            ?>

                        </span>
                        
                        <br>

                        <span class="subtitulo-seccion-aforo">Ingresaron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 5;
                                $sql_dt2 = "SELECT COUNT(*) ingresaronJornada5
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt2 = mysqli_query($link, $sql_dt2);
                                $row = mysqli_fetch_array($result_dt2);
                                echo ($row['ingresaronJornada5'] == '') ? (0) : ($row['ingresaronJornada5']);
                            ?>

                        </span>

                        <span class="subtitulo-seccion-aforo">- Salieron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 5;
                                $sql_dt3 = "SELECT COUNT(*) salieronJornada5
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaSalida) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt3 = mysqli_query($link, $sql_dt3);
                                $row = mysqli_fetch_array($result_dt3);
                                echo ($row['salieronJornada5'] == '') ? (0) : ($row['salieronJornada5']);
                            ?>

                        </span>

                        <br>
                        
                        <span class="subtitulo-seccion-aforo">Permanecen: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 5;
                                $sql_dt4 = "SELECT COUNT(*) permanecenJornada5
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND fechaHoraTomaSalida IS NULL
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt4 = mysqli_query($link, $sql_dt4);
                                $row = mysqli_fetch_array($result_dt4);
                                echo ($row['permanecenJornada5'] == '') ? (0) : ($row['permanecenJornada5']);
                            ?>

                        </span>

                    </div>

                </div>

                <div class="secciones-aforo">

                    <span class="titulo-seccion-aforo">Completa</span>

                    <div class="seccion-datos">
                    
                        <span class="subtitulo-seccion-aforo">Aforo: </span>
                        
                        <span class="dato-seccion-aforo">
                            
                            <?php 
                                echo $aforoJornada;
                            ?>

                        </span>
                        
                        <span class="subtitulo-seccion-aforo">- Registrados: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 6;
                                $sql_dt1 = "SELECT COUNT(*) registradosJornada6
                                            FROM encuesta_signos ES
                                            LEFT JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraDiligenciamiento) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt1 = mysqli_query($link, $sql_dt1);
                                $row = mysqli_fetch_array($result_dt1);
                                echo ($row['registradosJornada6'] == '') ? (0) : ($row['registradosJornada6']);
                            ?>

                        </span>
                        
                        <br>

                        <span class="subtitulo-seccion-aforo">Ingresaron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 6;
                                $sql_dt2 = "SELECT COUNT(*) ingresaronJornada6
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt2 = mysqli_query($link, $sql_dt2);
                                $row = mysqli_fetch_array($result_dt2);
                                echo ($row['ingresaronJornada6'] == '') ? (0) : ($row['ingresaronJornada6']);
                            ?>

                        </span>

                        <span class="subtitulo-seccion-aforo">- Salieron: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 6;
                                $sql_dt3 = "SELECT COUNT(*) salieronJornada6
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaSalida) = DATE(NOW()) 
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt3 = mysqli_query($link, $sql_dt3);
                                $row = mysqli_fetch_array($result_dt3);
                                echo ($row['salieronJornada6'] == '') ? (0) : ($row['salieronJornada6']);
                            ?>

                        </span>

                        <br>
                        
                        <span class="subtitulo-seccion-aforo">Permanecen: </span>
                        
                        <span class="dato-seccion-aforo">

                            <?php
                                $nroJornada = 6;
                                $sql_dt4 = "SELECT COUNT(*) permanecenJornada6
                                            FROM tomas_temperatura TT
                                            INNER JOIN encuesta_signos ES USING(idEncuesta)
                                            INNER JOIN horarios HO USING(idHorario)
                                            WHERE DATE(fechaHoraTomaEntrada) = DATE(NOW()) 
                                              AND fechaHoraTomaSalida IS NULL
                                              AND jornada = $nroJornada
                                            GROUP BY jornada";
                                $result_dt4 = mysqli_query($link, $sql_dt4);
                                $row = mysqli_fetch_array($result_dt4);
                                echo ($row['permanecenJornada6'] == '') ? (0) : ($row['permanecenJornada6']);
                            ?>

                        </span>

                    </div>

                </div>

                <!--

                <option value="1"></option>
                <option value="2"></option>
                <option value="3"></option>
                <option value="4"></option>
                <option value="5"></option>
                <option value="6"></option>

                -->

            </div>

            <div class="contenedor-central">

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
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-encuestas]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '')) {
                                echo '<li class="item-menu-principal"><a href="encuesta_signos-index.php">Encuesta de signos</a></li>';
                            }
                            ?>

                            <?php
                            if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-encuestas]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '')) {
                                echo '<li class="item-menu-principal"><a href="tomas_temperatura-index.php">Tomas de temperatura</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="preguntas_encuesta-index.php">Preguntas de la encuesta</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="horarios-index.php">Horarios</a></li>';
                            }
                            ?>

                            <?php
                            if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-encuestas]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '')) {
                                echo '<li class="item-menu-principal"><a href="respuestas_encuesta-index.php">Respuestas de la encuesta</a></li>';
                            }
                            ?>

                            <?php
                            if ((strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-encuestas]") != '') or
                                (strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '')) {
                                echo '<li class="item-menu-principal"><a href="personas-index.php">Personas</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="centros_formacion-index.php">Centros de formación</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="programas_formacion-index.php">Programas de formación</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="fichas_formacion-index.php">Fichas de formación</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="tipos_generos-index.php">Tipos de géneros</a></li>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="tipos_identificacion-index.php">Tipos de identificación</a></li>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="tipos_vinculaciones_sena-index.php">Tipos de vinculaciones</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="roles_sistema-index.php">Roles del sistema</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="usuarios-index.php">Usuarios del sistema</a></li>';
                            }
                            ?>
                            
                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="departamentos-index.php">Departamentos</a></li>';
                            }
                            ?>

                            <?php
                            if (strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
                                echo '<li class="item-menu-principal"><a href="municipios-index.php">Municipios</a></li>';
                            }
                            ?>

                            <li class="item-menu-principal"><a href="cerrar-sesion.php">Cerrar sesión</a></li>

                        </ul>

                    </div>

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
