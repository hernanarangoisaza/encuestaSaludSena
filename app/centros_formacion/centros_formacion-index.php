<?php
session_start();
if (empty($_SESSION["login"])) {
    header("Location: ../core/menu.php");
    exit();    
}
?>

<?php
if (!strstr($_SESSION['permisosRolSistema'], "[super-admin]") != '') {
    header("Location: ../core/menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Maestro de Centros de Formación</title>
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
                        <h2 class="float-left">Centros de formación - Panel General</h2>
                        <a href="centros_formacion-create.php" class="btn btn-success float-right">Crear</a>
                        <a href="centros_formacion-index.php" class="btn btn-info float-right mr-2">Restablecer listado</a>
                        <a href="../core/menu.php" class="btn btn-secondary float-right mr-2">Menú Principal</a>
                    </div>

                    <div class="form-row">
                        <form action="centros_formacion-index.php" method="get">
                            <div class="col">
                              <input type="text" class="form-control" placeholder="Buscar en este listado" name="search">
                            </div>
                        </form>
                    </div>

                    <?php
                    // Include config file
                    require_once "../core/config.php";

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

                    $total_pages_sql = "SELECT COUNT(*) FROM centros_formacion";
                    $resultMSQLI = mysqli_query($linkMYSQLI,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($resultMSQLI)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);
                    
                    //Column sorting on column name
                    $orderBy = array('idCentroFormacion', 'nombreCorto', 'nombreLargoCentroFormacion', 'direccion', 'idMunicipio', 'idDepartamento', 'telefono1', 'telefono2', 'emailContacto1', 'emailContacto2', 'estado', 'auditoria'); 
                    $order = 'idCentroFormacion';
                    if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
                            $order = $_GET['order'];
                        }

                    //Column sort order
                    $sortBy = array('asc', 'desc'); $sort = 'asc';
                    if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {                                                                    
                          if($_GET['sort']=='asc') {                                                                                                                            
                            $sort='desc';
                            }                                                                                   
                    else {
                        $sort='asc';
                        }                                                                                                                           
                    }

                    // Attempt select query execution
                    $sql = "SELECT CF.*, 
                        MN.municipio AS 'nombreMunicipio',
                        DP.departamento AS 'nombreDepartamento'
                        FROM centros_formacion CF
                        LEFT JOIN municipios MN ON MN.idMunicipio = CF.idMunicipio
                        LEFT JOIN departamentos DP ON DP.idDepartamento = CF.idDepartamento
                        ORDER BY $order $sort 
                        LIMIT $offset, $no_of_records_per_page";

                    $count_pages = "SELECT * FROM centros_formacion";
                    
                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);
                        $sql = "SELECT * FROM centros_formacion
                            WHERE CONCAT (CF.idCentroFormacion, CF.nombreCorto, CF.nombreLargoCentroFormacion, CF.direccion, MN.nombreMunicipio, DP.nombreDepartamento, CF.telefono1, CF.telefono2, CF.emailContacto1, CF.emailContacto2, CF.estado, CF.auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
                        $count_pages = "SELECT * FROM centros_formacion
                            WHERE CONCAT (CF.idCentroFormacion, CF.nombreCorto, CF.nombreLargoCentroFormacion, CF.direccion, MN.nombreMunicipio, DP.nombreDepartamento, CF.telefono1, CF.telefono2, CF.emailContacto1, CF.emailContacto2, CF.estado, CF.auditoria)
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
                            echo "<table class='estilo-tabla-index table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th class='estilo-acciones'>Acciones</th>";
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=idCentroFormacion&sort=$sort>Id Centro Formación</th>";
										echo "<th><a href=?search=$search&sort=&order=nombreCorto&sort=$sort>Nombre corto</th>";
										echo "<th><a href=?search=$search&sort=&order=nombreLargoCentroFormacion&sort=$sort>Nombre del Centro</th>";
										echo "<th><a href=?search=$search&sort=&order=direccion&sort=$sort>Dirección</th>";
										echo "<th><a href=?search=$search&sort=&order=idMunicipio&sort=$sort>Municipio</th>";
										echo "<th><a href=?search=$search&sort=&order=idDepartamento&sort=$sort>Departamento</th>";
										echo "<th><a href=?search=$search&sort=&order=telefono1&sort=$sort>Teléfono #1</th>";
										echo "<th><a href=?search=$search&sort=&order=telefono2&sort=$sort>Teléfono #2</th>";
										echo "<th><a href=?search=$search&sort=&order=emailContacto1&sort=$sort>Correo<br>electrónico #1</th>";
										echo "<th><a href=?search=$search&sort=&order=emailContacto2&sort=$sort>Correo<br>electrónico #2</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=estado&sort=$sort>Estado del registro</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=auditoria&sort=$sort>Fecha/Hora<br>de auditoría</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($resultMSQLI)){
                                    echo "<tr>";
                                    echo "<td class='centrar-columna'>";
                                        echo "<a href='centros_formacion-read.php?idCentroFormacion=". $row['idCentroFormacion'] ."'><i class='far fa-eye'></i></a>";
                                        echo "<a href='centros_formacion-update.php?idCentroFormacion=". $row['idCentroFormacion'] ."'><i class='far fa-edit'></i></a>";
                                        echo "<a href='centros_formacion-delete.php?idCentroFormacion=". $row['idCentroFormacion'] ."'><i class='far fa-trash-alt'></i></a>";
                                    echo "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['idCentroFormacion'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreCorto'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreLargoCentroFormacion'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['direccion'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreMunicipio'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreDepartamento'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['telefono1'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['telefono2'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['emailContacto1'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['emailContacto2'] . "</td>";
                                    echo "<td class='ocultar-columna centrar-columna'>" . $row['estado'] . "</td>";
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
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
</body>
</html>