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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Toma y registro de temperatura para control de ingreso a sedes del SENA <?php echo $regionalSedes ?></title>
    <link rel="stylesheet" href="../css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="../js/kit-fontawesome-6b773fe9e4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid index">
            <div class="row">
                <div class="col-md-12">

                    <div class="page-header clearfix">
                        <h2 class="float-left">Encuestas - Toma y Registro de Temperatura</h2>
                        <a href="../tomas_temperatura/tomas_temperatura-index.php" class="btn btn-dark float-right mr-2">Tomas de Temperatura</a>
                        <a href="registrar-temperatura.php" class="btn btn-info float-right mr-2">Restablecer listado</a>
                        <a href="../core/menu.php" class="btn btn-secondary float-right mr-2">Menú Principal</a>
                    </div>

                    <div class="form-row">
                        <form action="registrar-temperatura.php" method="get">
                        <div class="col">
                          <input type="text" class="form-control" placeholder="Buscar en este listado" name="search">
                        </div>
                        </form>
                    </div>

                    <?php
                    // Include config file
                    require_once "../core/config.php";

                    $_SESSION["rutaRegresarA"] = '../formato_toma_temperatura/registrar-temperatura.php';

                    //Get current URL and parameters for correct pagination
                    $protocol = $_SERVER['SERVER_PROTOCOL'];
                    $domain     = $_SERVER['HTTP_HOST'];
                    $script   = $_SERVER['SCRIPT_NAME'];
                    $parameters   = $_SERVER['QUERY_STRING'];
                    $protocol=strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https')
                                === FALSE ? 'http' : 'https';
                    $currenturl = $protocol . '://' . $domain. $script . '?' . $parameters;

                    //Pagination
                    if (isset($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }

                    //$no_of_records_per_page is set on the index page. Default is 10.
                    $offset = ($pageno-1) * $no_of_records_per_page;

                    $total_pages_sql = "SELECT COUNT(*) FROM encuesta_signos";
                    $resultMSQLI = mysqli_query($linkMYSQLI,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($resultMSQLI)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);
                    
                    //Column sorting on column name
                    $orderBy = array('idEncuesta', 'idPersona', 'fechaHoraDiligenciamiento', 'idSedeIngreso', 'idHorario', 'aceptacionConsideraciones', 'autorizacionTratamientoDatos', 'autorizacionIngreso', 'observacionAdicional', 'aceptacionRespuestaPositiva', 'estado', 'auditoria'); 
                    $order = 'idEncuesta';
                    if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
                            $order = $_GET['order'];
                        }

                    //Column sort order
                    $sortBy = array('asc', 'desc'); $sort = 'desc';
                    if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {                                                                    
                          if($_GET['sort']=='asc') {                                                                                                                            
                            $sort='desc';
                            }                                                                                   
                    else {
                        $sort='asc';
                        }                                                                                                                           
                    }

                    // Attempt select query execution
                    $sql = "SELECT ES.*, 
                        CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                        HO.nombreCorto AS 'nombreCorto',
                        PE.nombreCompleto AS 'nombreCompleto'
                        FROM encuesta_signos ES
                        LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = ES.idSedeIngreso
                        LEFT JOIN horarios HO ON HO.idHorario = ES.idHorario
                        LEFT JOIN personas PE USING(idPersona)
                        ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";

                    $count_pages = "SELECT * FROM encuesta_signos";
                    
                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);
                    $sql = "SELECT ES.*, 
                            CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                            HO.nombreCorto AS 'nombreCorto',
                            PE.nombreCompleto AS 'nombreCompleto'
                            FROM encuesta_signos ES
                            LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = ES.idSedeIngreso
                            LEFT JOIN horarios HO ON HO.idHorario = ES.idHorario
                            LEFT JOIN personas PE USING(idPersona)
                            WHERE CONCAT (ES.idEncuesta,ES.fechaHoraDiligenciamiento,PE.nombreCompleto,CF.nombreLargoCentroFormacion,HO.nombreCorto,ES.aceptacionConsideraciones,ES.autorizacionTratamientoDatos,ES.autorizacionIngreso,ES.observacionAdicional,ES.aceptacionRespuestaPositiva,ES.estado,ES.auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
                        $count_pages = "SELECT ES.*, 
                            CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                            HO.nombreCorto AS 'nombreCorto',
                            PE.nombreCompleto AS 'nombreCompleto'
                            FROM encuesta_signos ES
                            LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = ES.idSedeIngreso
                            LEFT JOIN horarios HO ON HO.idHorario = ES.idHorario
                            LEFT JOIN personas PE USING(idPersona)
                            WHERE CONCAT (ES.idEncuesta,ES.fechaHoraDiligenciamiento,PE.nombreCompleto,CF.nombreLargoCentroFormacion,HO.nombreCorto,ES.aceptacionConsideraciones,ES.autorizacionTratamientoDatos,ES.autorizacionIngreso,ES.observacionAdicional,ES.aceptacionRespuestaPositiva,ES.estado,ES.auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort";
                    }
                    else {
                        $search = "";
                    }

                    if($resultMSQLI = mysqli_query($linkMYSQLI, $sql)){
                        if(mysqli_num_rows($resultMSQLI) > 0){
                            if ($result_count = mysqli_query($linkMYSQLI, $count_pages)) {
                               $total_pages = ceil(mysqli_num_rows($result_count) / $no_of_records_per_page);
                           }
                            $number_of_results = mysqli_num_rows($result_count);
                            echo "<div class='cantidad-paginas'>" . $number_of_results . " resultado(s) - Página " . $pageno . " de " . $total_pages . "</div>";
                            echo "<p class='tip-columnas-index'>Clic en encabezados de columna para ordenar por esos criterios. Botón [Restablecer listado] para orden original o ver todos los registros</p>";
                            echo "<div class='seccion-tabla-scroll-horizontal'>";
                            echo "<table class='flipped estilo-tabla-index table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th class='estilo-acciones'>Acciones</th>";
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=idEncuesta&sort=$sort>Id<br>Encuesta</th>";
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=idPersona&sort=$sort>Id<br>Persona</th>";
                                        echo "<th><a href=?search=$search&sort=&order=fechaHoraDiligenciamiento&sort=$sort>Fecha/Hora<br>de diligenciamiento</th>";
                                        echo "<th><a href=?search=$search&sort=&order=idPersona&sort=$sort>Persona</th>";
                                        echo "<th><a href=?search=$search&sort=&order=idSedeIngreso&sort=$sort>Sede de ingreso</th>";
                                        echo "<th><a href=?search=$search&sort=&order=idHorario&sort=$sort>Horario</th>";
                                        echo "<th><a href=?search=$search&sort=&order=aceptacionConsideraciones&sort=$sort>Aceptación<br>de consideraciones</th>";
                                        echo "<th><a href=?search=$search&sort=&order=autorizacionTratamientoDatos&sort=$sort>Autorización de<br>tratamiento de datos</th>";
                                        echo "<th><a href=?search=$search&sort=&order=autorizacionIngreso&sort=$sort>Autorización<br>de ingreso</th>";
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=observacionAdicional&sort=$sort>Observaciones<br>adicionales</th>";
                                        echo "<th><a href=?search=$search&sort=&order=aceptacionRespuestaPositiva&sort=$sort>Alerta de<br>síntomas presentes</th>";
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=estado&sort=$sort>Estado del registro</th>";
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=auditoria&sort=$sort>Fecha/Hora<br>de auditoría</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($resultMSQLI)){
                                    echo "<tr>";
                                    echo "<td class='centrar-columna'>";
                                    echo "<a href='encuesta_signos-view.php?idEncuesta=". $row['idEncuesta'] ."'><i class='fas fa-list-ol'></i></a>";
                                    echo "<a href='../personas/personas-read.php?idPersona=". $row['idPersona'] ."'><i class='far fa-user'></i></a>";
                                    echo "<a href='../tomas_temperatura/tomas_temperatura-create.php?idEncuesta=". $row['idEncuesta'] ."'><i class='fas fa-thermometer-half'></i></a>";
                                    echo "</td>";                                    
                                    echo "<td class='ocultar-columna'>" . $row['idEncuesta'] . "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['idPersona'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['fechaHoraDiligenciamiento'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreCompleto'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreLargoCentroFormacion'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreCorto'] . "</td>";
                                    echo "<td class='centrar-columna'>";
                                        if ($row['aceptacionConsideraciones'] == 0) { echo 'No'; }
                                        else if ($row['aceptacionConsideraciones'] == 1) { echo 'Si'; }
                                        else if ($row['aceptacionConsideraciones'] == -1) { echo 'Sin asignar'; }
                                    echo "</td>";
                                    echo "<td class='centrar-columna'>";
                                        if ($row['autorizacionTratamientoDatos'] == 0) { echo 'No'; }
                                        else if ($row['autorizacionTratamientoDatos'] == 1) { echo 'Si'; }
                                        else if ($row['autorizacionTratamientoDatos'] == -1) { echo 'Sin asignar'; }
                                    echo "</td>";
                                    echo "<td class='centrar-columna'>";
                                        if ($row['autorizacionIngreso'] == 0) { echo 'No'; }
                                        else if ($row['autorizacionIngreso'] == 1) { echo 'Si'; }
                                        else if ($row['autorizacionIngreso'] == -1) { echo 'Sin asignar'; }
                                    echo "</td>";
                                    echo "<td class='ocultar-columna'>" . '< En vista individual >' . "</td>";
                                    if ($row['aceptacionRespuestaPositiva'] == 1) {$alertaPositivo = "alerta-positivo";} else {$alertaPositivo = "";}
                                    echo "<td class='centrar-columna $alertaPositivo'>";
                                        if ($row['aceptacionRespuestaPositiva'] == 0) { echo 'No'; }
                                        else if ($row['aceptacionRespuestaPositiva'] == 1) { echo 'Si'; }
                                        else if ($row['aceptacionRespuestaPositiva'] == -1) { echo 'Sin asignar'; }
                                     echo "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['estado'] . "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['auditoria'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                                echo "</div>";
                                ?>
                                <ul class="pagination" align-right>
                                    <?php
                                        $new_url = preg_replace('/&?pageno=[^&]*/', '', $currenturl);
                                    ?> 
                                    <li class="page-item"><a class="page-link" href="<?php echo $new_url .'&pageno=1' ?>">Primera</a></li>
                                    <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                                        <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo $new_url ."&pageno=".($pageno - 1); } ?>">Anterior</a>
                                    </li>
                                    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                        <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo $new_url . "&pageno=".($pageno + 1); } ?>">Siguiente</a>
                                    </li>
                                    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                        <a class="page-item"><a class="page-link" href="<?php echo $new_url .'&pageno=' . $total_pages; ?>">Última</a>
                                    </li>
                                </ul>
                                <?php
                            // Free result set
                            mysqli_free_result($resultMSQLI);
                        } else{
                            echo "<p class='lead sin-registros'><em>No se encontraron coincidencias.</em></p>";
                        }
                    } else{
                        echo "ERROR: No fue posible ejecutar $sql. " . mysqli_error($linkMYSQLI);
                    }

                    // Close connection
                    mysqli_close($linkMYSQLI);
                    ?>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="../js/libreria.js" type="text/javascript"></script>   
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
</body>
</html>