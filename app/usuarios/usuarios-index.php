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
    <title>Maestro de Usuarios del Sistema</title>
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
                        <h2 class="float-left">Usuarios del Sistema - Panel General</h2>
                        <a href="usuarios-create.php" class="btn btn-success float-right">Crear</a>
                        <a href="usuarios-index.php" class="btn btn-info float-right mr-2">Restablecer listado</a>
                        <a href="../core/menu.php" class="btn btn-secondary float-right mr-2">Menú Principal</a>
                    </div>

                    <div class="form-row">
                        <form action="usuarios-index.php" method="get">
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

                    $total_pages_sql = "SELECT COUNT(*) FROM usuarios";
                    $resultMSQLI = mysqli_query($linkMYSQLI,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($resultMSQLI)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);
                    
                    //Column sorting on column name
                    $orderBy = array('idUsuario', 'idTipoVinculacion', 'nombreCompleto', 'idTipoIdentificacion', 'identificacion', 'email', 'telefonoPersonal', 'fechaNacimiento', 'idTipoGenero', 'direccionResidencia', 'idMunicipio', 'idDepartamento', 'idCentroFormacion', 'idRolSistema', 'passwordSistema', 'estado', 'auditoria'); 
                    $order = 'idUsuario';
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
                    $sql = "SELECT US.*, 
                        TV.nombreLargoVinculacion AS 'nombreLargoVinculacion', 
                        TI.nombreLargoIdentificacion AS 'nombreLargoIdentificacion',
                        TG.nombreLargoGenero AS 'nombreLargoGenero',
                        MN.municipio AS 'nombreMunicipio',
                        DP.departamento AS 'nombreDepartamento',
                        CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                        RS.nombreCorto AS 'nombreCorto'
                        FROM usuarios US
                        LEFT JOIN tipos_vinculaciones_sena TV ON TV.idTipoVinculacion = US.idTipoVinculacion
                        LEFT JOIN tipos_identificacion TI ON TI.idTipoIdentificacion = US.idTipoIdentificacion
                        LEFT JOIN tipos_generos TG ON TG.idTipoGenero = US.idTipoGenero
                        LEFT JOIN municipios MN ON MN.idMunicipio = US.idMunicipio
                        LEFT JOIN departamentos DP ON DP.idDepartamento = US.idDepartamento
                        LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = US.idCentroFormacion
                        LEFT JOIN roles_sistema RS ON RS.idRolSistema = US.idRolSistema
                        ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";

                    $count_pages = "SELECT * FROM usuarios";
                    
                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);
                        $sql = "SELECT US.*, 
                            TV.nombreLargoVinculacion AS 'nombreLargoVinculacion', 
                            TI.nombreLargoIdentificacion AS 'nombreLargoIdentificacion',
                            TG.nombreLargoGenero AS 'nombreLargoGenero',
                            MN.municipio AS 'nombreMunicipio',
                            DP.departamento AS 'nombreDepartamento',
                            CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                            RS.nombreCorto AS 'nombreCorto'
                            FROM usuarios US
                            LEFT JOIN tipos_vinculaciones_sena TV ON TV.idTipoVinculacion = US.idTipoVinculacion
                            LEFT JOIN tipos_identificacion TI ON TI.idTipoIdentificacion = US.idTipoIdentificacion
                            LEFT JOIN tipos_generos TG ON TG.idTipoGenero = US.idTipoGenero
                            LEFT JOIN municipios MN ON MN.idMunicipio = US.idMunicipio
                            LEFT JOIN departamentos DP ON DP.idDepartamento = US.idDepartamento
                            LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = US.idCentroFormacion
                            LEFT JOIN roles_sistema RS ON RS.idRolSistema = US.idRolSistema
                            WHERE CONCAT (US.idUsuario,TV.idTipoVinculacion,US.nombreCompleto,TI.idTipoIdentificacion,US.identificacion,US.email,US.telefonoPersonal,US.fechaNacimiento,TG.idTipoGenero,US.direccionResidencia,MN.idMunicipio,DP.idDepartamento,CF.idCentroFormacion,RS.idRolSistema,US.passwordSistema,US.estado,US.auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
                        $count_pages = "SELECT US.*, 
                            TV.nombreLargoVinculacion AS 'nombreLargoVinculacion', 
                            TI.nombreLargoIdentificacion AS 'nombreLargoIdentificacion',
                            TG.nombreLargoGenero AS 'nombreLargoGenero',
                            MN.municipio AS 'nombreMunicipio',
                            DP.departamento AS 'nombreDepartamento',
                            CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                            RS.nombreCorto AS 'nombreCorto'
                            FROM usuarios US
                            LEFT JOIN tipos_vinculaciones_sena TV ON TV.idTipoVinculacion = US.idTipoVinculacion
                            LEFT JOIN tipos_identificacion TI ON TI.idTipoIdentificacion = US.idTipoIdentificacion
                            LEFT JOIN tipos_generos TG ON TG.idTipoGenero = US.idTipoGenero
                            LEFT JOIN municipios MN ON MN.idMunicipio = US.idMunicipio
                            LEFT JOIN departamentos DP ON DP.idDepartamento = US.idDepartamento
                            LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = US.idCentroFormacion
                            LEFT JOIN roles_sistema RS ON RS.idRolSistema = US.idRolSistema
                            WHERE CONCAT (US.idUsuario,TV.idTipoVinculacion,US.nombreCompleto,TI.idTipoIdentificacion,US.identificacion,US.email,US.telefonoPersonal,US.fechaNacimiento,TG.idTipoGenero,US.direccionResidencia,MN.idMunicipio,DP.idDepartamento,CF.idCentroFormacion,RS.idRolSistema,US.passwordSistema,US.estado,US.auditoria)
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
                                        echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=idUsuario&sort=$sort>Id Usuario</th>";
										echo "<th><a href=?search=$search&sort=&order=idTipoVinculacion&sort=$sort>Tipo de<br>vinculación</th>";
										echo "<th><a href=?search=$search&sort=&order=nombreCompleto&sort=$sort>Nombre completo</th>";
										echo "<th><a href=?search=$search&sort=&order=idTipoIdentificacion&sort=$sort>Tipo de<br>identificación</th>";
										echo "<th><a href=?search=$search&sort=&order=identificacion&sort=$sort>Identificación</th>";
										echo "<th><a href=?search=$search&sort=&order=email&sort=$sort>Correo electrónico</th>";
										echo "<th><a href=?search=$search&sort=&order=telefonoPersonal&sort=$sort>Teléfono<br>personal</th>";
										echo "<th><a href=?search=$search&sort=&order=fechaNacimiento&sort=$sort>Fecha<br>de nacimiento</th>";
										echo "<th><a href=?search=$search&sort=&order=idTipoGenero&sort=$sort>Género</th>";
										echo "<th><a href=?search=$search&sort=&order=direccionResidencia&sort=$sort>Dirección de la residencia</th>";
										echo "<th><a href=?search=$search&sort=&order=idMunicipio&sort=$sort>Municipio</th>";
										echo "<th><a href=?search=$search&sort=&order=idDepartamento&sort=$sort>Departamento</th>";
										echo "<th><a href=?search=$search&sort=&order=idCentroFormacion&sort=$sort>Centro de formación</th>";
										echo "<th><a href=?search=$search&sort=&order=idRolSistema&sort=$sort>Rol del sistema</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=passwordSistema&sort=$sort>Contraseña del sistema</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=estado&sort=$sort>Estado del registro</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=auditoria&sort=$sort>Fecha/Hora<br>de auditoría</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($resultMSQLI)){
                                    echo "<tr>";
                                    echo "<td class='centrar-columna'>";
                                        echo "<a href='usuarios-read.php?idUsuario=". $row['idUsuario'] ."'><i class='far fa-eye'></i></a>";
                                        echo "<a href='usuarios-update.php?idUsuario=". $row['idUsuario'] ."'><i class='far fa-edit'></i></a>";
                                        echo "<a href='usuarios-delete.php?idUsuario=". $row['idUsuario'] ."'><i class='far fa-trash-alt'></i></a>";
                                        echo "<a href='usuarios-password.php?idUsuario=". $row['idUsuario'] ."'><i class='fas fa-key'></i></a>";
                                    echo "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['idUsuario'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreLargoVinculacion'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreCompleto'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreLargoIdentificacion'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['identificacion'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['email'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['telefonoPersonal'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['fechaNacimiento'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreLargoGenero'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['direccionResidencia'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreMunicipio'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreDepartamento'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreLargoCentroFormacion'] . "</td>";
                                    echo "<td class='centrar-columna'>" . $row['nombreCorto'] . "</td>";
                                    echo "<td class='ocultar-columna'>" . $row['passwordSistema'] . "</td>";
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
</body>
</html>