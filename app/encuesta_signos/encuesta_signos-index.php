<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../core/menu.php");
    exit();    
}
?>

<?php
if ((!strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') and
    (!strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '')) {
    header("Location: ../core/menu.php");
    exit();
}
?>

<?php
    
    // Dada la funcionalidad, el botón CREAR se deja deshabilitado para todos los perfiles. En esta zona, de ser necesario, se debe plantear otra alternativa similiar a la planteada en otras secciones del código para el mismo botón.

    $isDisabled = "isDisabled";
    $ariaDisabled = "true";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Encuestas de Signos</title>
    <link rel="stylesheet" href="../css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous">
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
                        <h2 class="float-left">Encuesta de Signos - Panel General</h2>
                        <?php echo '<span class="' . $isDisabled. '"><a href="encuesta_signos-create.php" class="btn btn-success float-right" aria-disabled="' . $ariaDisabled . '">Crear</a></span>' ?>
                        <a href="encuesta_signos-index.php" class="btn btn-info float-right mr-2">Restablecer listado</a>
                        <a href="../core/menu.php" class="btn btn-secondary float-right mr-2">Menú Principal</a>
                    </div>

                    <div class="form-row">
                        <form action="encuesta_signos-index.php" method="get">
                        <div class="col">
                          <input type="text" class="form-control" placeholder="Buscar en este listado" name="search">
                        </div>
                        </form>
                    </div>

                    <?php
                    // Include config file
                    require_once "../core/config.php";

                    $_SESSION["rutaRegresarA"] = '../encuesta_signos/encuesta_signos-index.php';

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
                        PE.nombreCompleto AS 'nombreCompleto',
                        CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                        HO.nombreCorto AS 'nombreCorto'
                        FROM encuesta_signos ES
                        LEFT JOIN personas PE ON PE.idPersona = ES.idPersona
                        LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = ES.idSedeIngreso
                        LEFT JOIN horarios HO ON HO.idHorario = ES.idHorario
                        ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";

                    $count_pages = "SELECT * FROM encuesta_signos";
                    
                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);
                    $sql = "SELECT ES.*, 
                            PE.nombreCompleto AS 'nombreCompleto',
                            CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                            HO.nombreCorto AS 'nombreCorto'
                            FROM encuesta_signos ES
                            LEFT JOIN personas PE ON PE.idPersona = ES.idPersona
                            LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = ES.idSedeIngreso
                            LEFT JOIN horarios HO ON HO.idHorario = ES.idHorario
                            WHERE CONCAT (ES.idEncuesta,ES.fechaHoraDiligenciamiento,PE.nombreCompleto,CF.nombreLargoCentroFormacion,HO.nombreCorto,ES.aceptacionConsideraciones,ES.autorizacionTratamientoDatos,ES.autorizacionIngreso,ES.observacionAdicional,ES.aceptacionRespuestaPositiva,ES.estado,ES.auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
                        $count_pages = "SELECT ES.*, 
                            PE.nombreCompleto AS 'nombreCompleto',
                            CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                            HO.nombreCorto AS 'nombreCorto'
                            FROM encuesta_signos ES
                            LEFT JOIN personas PE ON PE.idPersona = ES.idPersona
                            LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = ES.idSedeIngreso
                            LEFT JOIN horarios HO ON HO.idHorario = ES.idHorario
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
                                        echo "<th class=''><a href=?search=$search&sort=&order=idEncuesta&sort=$sort>Id<br>Encuesta</th>";
										echo "<th><a href=?search=$search&sort=&order=fechaHoraDiligenciamiento&sort=$sort>Fecha/Hora<br>de diligenciamiento</th>";
                                        echo "<th class=''><a href=?search=$search&sort=&order=nombreCompleto&sort=$sort>Persona</th>";
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
                                    echo "<span class='$isDisabled'>" . "<a href='encuesta_signos-read.php?idEncuesta=". $row['idEncuesta'] . "' aria-disabled='$ariaDisabled'>" . "<i class='far fa-eye'></i></a></span>";
                                    echo "<span class='$isDisabled'>" . "<a href='encuesta_signos-update.php?idEncuesta=". $row['idEncuesta'] . "' aria-disabled='$ariaDisabled'>" . "<i class='far fa-edit'></i></a></span>";
                                    echo "<span class='$isDisabled'>" . "<a href='encuesta_signos-delete.php?idEncuesta=". $row['idEncuesta'] . "' aria-disabled='$ariaDisabled'>" . "<i class='far fa-trash-alt'></i></a></span>";
                                    echo "<a href='../formato_encuesta/encuesta-view.php?idEncuesta=". $row['idEncuesta'] ."&idPersona=". $row['idPersona']."' target='_blank'><i class='fas fa-list-ol'></i></a>";
                                    echo "<a href='../personas/personas-read.php?idPersona=". $row['idPersona'] ."' target='_blank'><i class='far fa-user'></i></a>";
                                    echo "<a href='../tomas_temperatura/tomas_temperatura-index.php?idEncuesta=". $row['idEncuesta'] ."&idPersona=". $row['idPersona']."' target='_blank'><i class='fas fa-thermometer-half'></i></a>";
                                    echo "</td>";                                    
                                    echo "<td class='centrar-columna'>" . $row['idEncuesta'] . "</td>";
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

                                    if ($row['aceptacionRespuestaPositiva'] == 1) {
                                        $alertaPositivo = "alerta-positivo";
                                    } else {
                                        $alertaPositivo = "";
                                    }

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
<script src="../js/jquery-3.5.1.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha512-iceXjjbmB2rwoX93Ka6HAHP+B76IY1z0o3h+N1PeDtRSsyeetU3/0QKJqGyPJcX63zysNehggFwMC/bi7dvMig==" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
</body>
</html>