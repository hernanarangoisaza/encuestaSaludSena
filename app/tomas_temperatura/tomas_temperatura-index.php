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
    <title>Gestión de Tomas de Temperatura</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
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
                        <h2 class="float-left">Tomas de Temperatura - Panel General</h2>
                        <a href="tomas_temperatura-create.php" class="btn btn-success float-right">Crear</a>
                        <a href="tomas_temperatura-index.php" class="btn btn-info float-right mr-2">Restablecer listado</a>
                        <a href="../core/menu.php" class="btn btn-secondary float-right mr-2">Menú Principal</a>
                    </div>

                    <div class="form-row">
                        <form action="tomas_temperatura-index.php" method="get">
                        <div class="col">
                          <input type="text" class="form-control" placeholder="Buscar en este listado" name="search">
                        </div>
                        </form>
                    </div>

                    <?php
                    // Include config file
                    require_once "../core/config.php";

                    $_SESSION["rutaRegresarA"] = 'tomas_temperatura-index.php';

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

                    $total_pages_sql = "SELECT COUNT(*) FROM tomas_temperatura";
                    $result = mysqli_query($link,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);
                    
                    //Column sorting on column name
                    $orderBy = array('idToma', 'idEncuesta', 'idPersona', 'fechaHoraTomaEntrada', 'temperaturaEntrada', 'fechaHoraTomaSalida', 'temperaturaSalida', 'estado', 'auditoria'); 
                    $order = 'idToma';
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
                    $sql = "SELECT TT.*, 
                            ES.idPersona AS 'idPersona' 
                            FROM tomas_temperatura TT
                            LEFT JOIN encuesta_signos ES ON ES.idEncuesta = TT.idEncuesta
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
                    
                    $count_pages = "SELECT * FROM tomas_temperatura";
                    
                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);

                        $sql = "SELECT TT.*, 
                            ES.idPersona AS 'idPersona' 
                            FROM tomas_temperatura TT
                            LEFT JOIN encuesta_signos ES ON ES.idEncuesta = TT.idEncuesta
                            WHERE CONCAT (TT.idToma,TT.idEncuesta,ES.idPersona,TT.fechaHoraTomaEntrada,TT.temperaturaEntrada,TT.fechaHoraTomaSalida,TT.temperaturaSalida,TT.estado,TT.auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
                        
                        $count_pages = "SELECT TT.*, 
                            ES.idPersona AS 'idPersona' 
                            FROM tomas_temperatura TT
                            LEFT JOIN encuesta_signos ES ON ES.idEncuesta = TT.idEncuesta
                            WHERE CONCAT (TT.idToma,TT.idEncuesta,ES.idPersona,TT.fechaHoraTomaEntrada,TT.temperaturaEntrada,TT.fechaHoraTomaSalida,TT.temperaturaSalida,TT.estado,TT.auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort";
                    }
                    else {
                        $search = "";
                    }

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            if ($result_count = mysqli_query($link, $count_pages)) {
                               $total_pages = ceil(mysqli_num_rows($result_count) / $no_of_records_per_page);
                           }
                            $number_of_results = mysqli_num_rows($result_count);
                            echo "<div class='cantidad-paginas'>" . $number_of_results . " resultado(s) - Página " . $pageno . " de " . $total_pages . "</div>";
                            echo "<p class='tip-columnas-index'>Clic en encabezados de columna para ordenar por esos criterios. Botón [Restablecer listado] para orden original o ver todos los registros</p>";
                            echo "<div class='seccion-tabla-scroll-horizontal'>";
                            echo "<table class='estilo-tabla-index table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th class='estilo-acciones'>Acciones</th>";
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=idToma&sort=$sort>Id Toma Temperatura</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=idEncuesta&sort=$sort>Id<br>Encuesta</th>";
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=idPersona&sort=$sort>Id<br>Persona</th>";
										echo "<th><a href=?search=$search&sort=&order=fechaHoraTomaEntrada&sort=$sort>Fecha/hora<br>toma de entrada</th>";
										echo "<th><a href=?search=$search&sort=&order=temperaturaEntrada&sort=$sort>Temperatura<br>a la entrada</th>";
										echo "<th><a href=?search=$search&sort=&order=fechaHoraTomaSalida&sort=$sort>Fecha/hora<br>toma de salida</th>";
										echo "<th><a href=?search=$search&sort=&order=temperaturaSalida&sort=$sort>Temperatura<br>a la salida</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=estado&sort=$sort>Estado del registro</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=auditoria&sort=$sort>Fecha/Hora<br>de auditoría</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td class='centrar-columna'>";
                                        echo "<a href='tomas_temperatura-read.php?idToma=". $row['idToma'] ."'><i class='far fa-eye'></i></a>";
                                        echo "<a href='tomas_temperatura-update.php?idToma=". $row['idToma'] ."'><i class='far fa-edit'></i></a>";
                                        echo "<a href='tomas_temperatura-delete.php?idToma=". $row['idToma'] ."'><i class='far fa-trash-alt'></i></a>";
                                        echo "<a href='../encuesta_signos/encuesta_signos-view.php?idEncuesta=". $row['idEncuesta'] ."'><i class='fas fa-list-ol'></i></a>";
                                        echo "<a href='../personas/personas-read.php?idPersona=". $row['idPersona'] ."'><i class='far fa-user'></i></a>";
                                    echo "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['idToma'] . "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['idEncuesta'] . "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['idPersona'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['fechaHoraTomaEntrada'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['temperaturaEntrada'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['fechaHoraTomaSalida'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['temperaturaSalida'] . "</td>";
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
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead sin-registros'><em>No se encontraron coincidencias.</em></p>";
                        }
                    } else{
                        echo "ERROR: No fue posible ejecutar $sql. " . mysqli_error($link);
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </section>
<script src="../js/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="../js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="../js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>