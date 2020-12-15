<?php
// Include config file
require_once "config.php";
?>

<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../index.php");
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
        <title>Sistema de encuestas para registro de síntomas y temperatura en el SENA - CDITI</title>
        <link rel="stylesheet" href="../css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/estilos.css" />
        <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />

    </head>

    <body>

        <div class="contenedor-principal">

            <div class="contenedor-izquierdo <?php echo $visibilidadContenedorIzquierdo ?>">

                <div class="titulo-aforo">

                    ESTADÍSTICAS DE AFORO

                </div>

                <div class="aviso-aforos">

                    Haga clic sobre los encabezados de sección de cada jornada para consultar los datos de aforo. Presione la <i><b>tecla F5</b></i> para refrescar la página y sus estádisticas.

                </div>

                <div class="accordion" id="accordionAforos">

                  <div class="card">
                    <div class="card-header" id="heading1">
                      <h2 class="mb-0">
                        <button class="btn text-left titulo-tarjeta collapsed" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                          Mañana
                        </button>
                      </h2>
                    </div>
                    <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#accordionAforos">
                      <div class="card-body">

                        <div class="secciones-aforo">

                            <div class="seccion-datos">
                            
                                <span class="subtitulo-seccion-aforo">Permitido: </span>
                                
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
                                        $result_dt1 = mysqli_query($linkMYSQLI, $sql_dt1);
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
                                        $result_dt2 = mysqli_query($linkMYSQLI, $sql_dt2);
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
                                        $result_dt3 = mysqli_query($linkMYSQLI, $sql_dt3);
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
                                        $result_dt4 = mysqli_query($linkMYSQLI, $sql_dt4);
                                        $row = mysqli_fetch_array($result_dt4);
                                        echo ($row['permanecenJornada1'] == '') ? (0) : ($row['permanecenJornada1']);
                                    ?>

                                </span>
                            
                            </div>
                        
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="heading2">
                      <h2 class="mb-0">
                        <button class="btn text-left titulo-tarjeta collapsed" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                          Tarde
                        </button>
                      </h2>
                    </div>
                    <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionAforos">
                      <div class="card-body">

                        <div class="secciones-aforo">
                            
                            <div class="seccion-datos">
                            
                                <span class="subtitulo-seccion-aforo">Permitido: </span>
                                
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
                                        $result_dt1 = mysqli_query($linkMYSQLI, $sql_dt1);
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
                                        $result_dt2 = mysqli_query($linkMYSQLI, $sql_dt2);
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
                                        $result_dt3 = mysqli_query($linkMYSQLI, $sql_dt3);
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
                                        $result_dt4 = mysqli_query($linkMYSQLI, $sql_dt4);
                                        $row = mysqli_fetch_array($result_dt4);
                                        echo ($row['permanecenJornada2'] == '') ? (0) : ($row['permanecenJornada2']);
                                    ?>

                                </span>

                            </div>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="heading3">
                      <h2 class="mb-0">
                        <button class="btn text-left titulo-tarjeta collapsed" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                          Noche
                        </button>
                      </h2>
                    </div>
                    <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionAforos">
                      <div class="card-body">

                        <div class="secciones-aforo">

                            <div class="seccion-datos">
                            
                                <span class="subtitulo-seccion-aforo">Permitido: </span>
                                
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
                                        $result_dt1 = mysqli_query($linkMYSQLI, $sql_dt1);
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
                                        $result_dt2 = mysqli_query($linkMYSQLI, $sql_dt2);
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
                                        $result_dt3 = mysqli_query($linkMYSQLI, $sql_dt3);
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
                                        $result_dt4 = mysqli_query($linkMYSQLI, $sql_dt4);
                                        $row = mysqli_fetch_array($result_dt4);
                                        echo ($row['permanecenJornada3'] == '') ? (0) : ($row['permanecenJornada3']);
                                    ?>

                                </span>

                            </div>

                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="heading4">
                      <h2 class="mb-0">
                        <button class="btn text-left titulo-tarjeta collapsed" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                          Mixta mañana - tarde
                        </button>
                      </h2>
                    </div>
                    <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionAforos">
                      <div class="card-body">
                          
                        <div class="secciones-aforo">

                            <div class="seccion-datos">
                            
                                <span class="subtitulo-seccion-aforo">Permitido: </span>
                                
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
                                        $result_dt1 = mysqli_query($linkMYSQLI, $sql_dt1);
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
                                        $result_dt2 = mysqli_query($linkMYSQLI, $sql_dt2);
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
                                        $result_dt3 = mysqli_query($linkMYSQLI, $sql_dt3);
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
                                        $result_dt4 = mysqli_query($linkMYSQLI, $sql_dt4);
                                        $row = mysqli_fetch_array($result_dt4);
                                        echo ($row['permanecenJornada4'] == '') ? (0) : ($row['permanecenJornada4']);
                                    ?>

                                </span>

                            </div>

                        </div> 

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="heading5">
                      <h2 class="mb-0">
                        <button class="btn text-left titulo-tarjeta collapsed" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                          Mixta tarde-noche
                        </button>
                      </h2>
                    </div>
                    <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordionAforos">
                      <div class="card-body">
                          
                        <div class="secciones-aforo">

                            <div class="seccion-datos">
                            
                                <span class="subtitulo-seccion-aforo">Permitido: </span>
                                
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
                                        $result_dt1 = mysqli_query($linkMYSQLI, $sql_dt1);
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
                                        $result_dt2 = mysqli_query($linkMYSQLI, $sql_dt2);
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
                                        $result_dt3 = mysqli_query($linkMYSQLI, $sql_dt3);
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
                                        $result_dt4 = mysqli_query($linkMYSQLI, $sql_dt4);
                                        $row = mysqli_fetch_array($result_dt4);
                                        echo ($row['permanecenJornada5'] == '') ? (0) : ($row['permanecenJornada5']);
                                    ?>

                                </span>

                            </div>

                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="heading6">
                      <h2 class="mb-0">
                        <button class="btn text-left titulo-tarjeta collapsed" type="button" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                          Completa
                        </button>
                      </h2>
                    </div>
                    <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordionAforos">
                      <div class="card-body">
                          
                        <div class="secciones-aforo">

                            <div class="seccion-datos">
                            
                                <span class="subtitulo-seccion-aforo">Permitido: </span>
                                
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
                                        $result_dt1 = mysqli_query($linkMYSQLI, $sql_dt1);
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
                                        $result_dt2 = mysqli_query($linkMYSQLI, $sql_dt2);
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
                                        $result_dt3 = mysqli_query($linkMYSQLI, $sql_dt3);
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
                                        $result_dt4 = mysqli_query($linkMYSQLI, $sql_dt4);
                                        $row = mysqli_fetch_array($result_dt4);
                                        echo ($row['permanecenJornada6'] == '') ? (0) : ($row['permanecenJornada6']);
                                    ?>

                                </span>

                            </div>

                        </div>

                      </div>
                    </div>
                  </div>

                </div>           

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
        <script src="../js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
        <script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="../js/libreria.js" type="text/javascript"></script>

        <script>
            $(document).ready(function () {});
        </script>

    </body>

</html>
