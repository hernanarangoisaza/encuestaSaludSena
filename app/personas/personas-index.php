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
if ((strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '')) {
    header("Location: ../core/menu.php");
}
?>

<?php
if ((strstr($_SESSION['permisosRolSistema'], "[auxiliar-temperatura]") != '') or 
   (strstr($_SESSION['permisosRolSistema'], "[auxiliar-encuestas]") != '') or 
   (strstr($_SESSION['permisosRolSistema'], "[auxiliar-aprendices]") != '')) {
    $isDisabled = "isDisabled";
    $ariaDisabled = "true";
}
else {
    $isDisabled = "notDisabled";
    $ariaDisabled = "false";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Personas</title>
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
                        <h2 class="float-left">Personas - Panel General</h2>
                        <?php echo '<span class="' . $isDisabled. '"><a href="personas-create.php" class="btn btn-success float-right" aria-disabled="' . $ariaDisabled . '">Crear</a></span>' ?>
                        <a href="personas-index.php" class="btn btn-info float-right mr-2">Restablecer listado</a>
                        <a href="../core/menu.php" class="btn btn-secondary float-right mr-2">Menú Principal</a>
                    </div>

                    <div class="form-row">
                        <form action="personas-index.php" method="get">
                        <div class="col">
                          <input type="text" class="form-control" placeholder="Buscar en este listado" name="search">
                        </div>
                        </form>
                    </div>

                    <?php
                    // Include config file
                    require_once "../core/config.php";

                    $_SESSION["rutaRegresarA"] = '../personas/personas-index.php';

                    //Get current URL and parameters for correct pagination
                    $protocol = $_SERVER['SERVER_PROTOCOL'];
                    $domain = $_SERVER['HTTP_HOST'];
                    $script = $_SERVER['SCRIPT_NAME'];
                    $parameters = $_SERVER['QUERY_STRING'];
                    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false ? 'http' : 'https';
                    $currenturl = $protocol . '://' . $domain . $script . '?' . $parameters;

                    //Pagination
                    if (isset($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }

                    //$no_of_records_per_page is set on the index page. Default is 10.
                    $offset = ($pageno - 1) * $no_of_records_per_page;

                    $total_pages_sql = "SELECT COUNT(*) FROM personas";
                    $resultMSQLI = mysqli_query($linkMYSQLI, $total_pages_sql);
                    $total_rows = mysqli_fetch_array($resultMSQLI)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);

                    //Column sorting on column name
                    $orderBy = [
                        'idPersona',
                        'idTipoVinculacion',
                        'nombreCompleto',
                        'idTipoIdentificacion',
                        'identificacion',
                        'email',
                        'telefonoPersonal',
                        'telefonoAcudiente',
                        'fechaNacimiento',
                        'idTipoGenero',
                        'direccionResidencia',
                        'idMunicipio',
                        'idDepartamento',
                        'idCentroFormacion',
                        'idFichaFormacion',
                        'estado',
                        'auditoria',
                    ];
                    $order = 'idPersona';
                    if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
                        $order = $_GET['order'];
                    }

                    //Column sort order
                    $sortBy = array('asc', 'desc'); $sort = 'desc';
                    if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {
                        if ($_GET['sort'] == 'asc') {
                            $sort = 'desc';
                        } else {
                            $sort = 'asc';
                        }
                    }

                    // Attempt select query execution
                    $sql = "SELECT PE.*, 
                        TV.nombreLargoVinculacion AS 'nombreLargoVinculacion', 
                        TI.nombreLargoIdentificacion AS 'nombreLargoIdentificacion',
                        TG.nombreLargoGenero AS 'nombreLargoGenero',
                        MN.municipio AS 'nombreMunicipio',
                        DP.departamento AS 'nombreDepartamento',
                        CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                        FF.codigoFichaFormacion AS 'codigoFichaFormacion'
                        FROM personas PE
                        LEFT JOIN tipos_vinculaciones_sena TV ON TV.idTipoVinculacion = PE.idTipoVinculacion
                        LEFT JOIN tipos_identificacion TI ON TI.idTipoIdentificacion = PE.idTipoIdentificacion
                        LEFT JOIN tipos_generos TG ON TG.idTipoGenero = PE.idTipoGenero
                        LEFT JOIN municipios MN ON MN.idMunicipio = PE.idMunicipio
                        LEFT JOIN departamentos DP ON DP.idDepartamento = PE.idDepartamento
                        LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = PE.idCentroFormacion
                        LEFT JOIN fichas_formacion FF ON FF.idFichaFormacion = PE.idFichaFormacion
                        ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";

                    $count_pages = "SELECT * FROM personas";

                    if (!empty($_GET['search'])) {
                        $search = $_GET['search'];
                        $sql = "SELECT PE.*, 
                            TV.nombreLargoVinculacion AS 'nombreLargoVinculacion', 
                            TI.nombreLargoIdentificacion AS 'nombreLargoIdentificacion',
                            TG.nombreLargoGenero AS 'nombreLargoGenero',
                            MN.municipio AS 'nombreMunicipio',
                            DP.departamento AS 'nombreDepartamento',
                            CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                            FF.codigoFichaFormacion AS 'codigoFichaFormacion'
                            FROM personas PE
                            LEFT JOIN tipos_vinculaciones_sena TV ON TV.idTipoVinculacion = PE.idTipoVinculacion
                            LEFT JOIN tipos_identificacion TI ON TI.idTipoIdentificacion = PE.idTipoIdentificacion
                            LEFT JOIN tipos_generos TG ON TG.idTipoGenero = PE.idTipoGenero
                            LEFT JOIN municipios MN ON MN.idMunicipio = PE.idMunicipio
                            LEFT JOIN departamentos DP ON DP.idDepartamento = PE.idDepartamento
                            LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = PE.idCentroFormacion
                            LEFT JOIN fichas_formacion FF ON FF.idFichaFormacion = PE.idFichaFormacion
                            WHERE CONCAT (PE.idPersona,TV.nombreLargoVinculacion,PE.nombreCompleto,TI.nombreLargoIdentificacion,PE.identificacion,PE.email,PE.telefonoPersonal,PE.telefonoAcudiente,PE.fechaNacimiento,TG.nombreLargoGenero,PE.direccionResidencia,MN.municipio,DP.departamento,CF.nombreLargoCentroFormacion,FF.codigoFichaFormacion,PE.estado,PE.auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
                            
                        $count_pages = "SELECT PE.*, 
                            TV.nombreLargoVinculacion AS 'nombreLargoVinculacion', 
                            TI.nombreLargoIdentificacion AS 'nombreLargoIdentificacion',
                            TG.nombreLargoGenero AS 'nombreLargoGenero',
                            MN.municipio AS 'nombreMunicipio',
                            DP.departamento AS 'nombreDepartamento',
                            CF.nombreLargoCentroFormacion AS 'nombreLargoCentroFormacion',
                            FF.codigoFichaFormacion AS 'codigoFichaFormacion'
                            FROM personas PE
                            LEFT JOIN tipos_vinculaciones_sena TV ON TV.idTipoVinculacion = PE.idTipoVinculacion
                            LEFT JOIN tipos_identificacion TI ON TI.idTipoIdentificacion = PE.idTipoIdentificacion
                            LEFT JOIN tipos_generos TG ON TG.idTipoGenero = PE.idTipoGenero
                            LEFT JOIN municipios MN ON MN.idMunicipio = PE.idMunicipio
                            LEFT JOIN departamentos DP ON DP.idDepartamento = PE.idDepartamento
                            LEFT JOIN centros_formacion CF ON CF.idCentroFormacion = PE.idCentroFormacion
                            LEFT JOIN fichas_formacion FF ON FF.idFichaFormacion = PE.idFichaFormacion
                            WHERE CONCAT (PE.idPersona,TV.nombreLargoVinculacion,PE.nombreCompleto,TI.nombreLargoIdentificacion,PE.identificacion,PE.email,PE.telefonoPersonal,PE.telefonoAcudiente,PE.fechaNacimiento,TG.nombreLargoGenero,PE.direccionResidencia,MN.municipio,DP.departamento,CF.nombreLargoCentroFormacion,FF.codigoFichaFormacion,PE.estado,PE.auditoria)
                             LIKE '%$search%'
                             ORDER BY $order $sort";
                    } else {
                        $search = "";
                    }

                    if ($resultMSQLI = mysqli_query($linkMYSQLI, $sql)) {
                        if (mysqli_num_rows($resultMSQLI) > 0) {

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
                            echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=idPersona&sort=$sort>Id Persona</th>";
                            echo "<th><a href=?search=$search&sort=&order=idTipoVinculacion&sort=$sort>Tipo de<br>vinculación</th>";
                            echo "<th><a href=?search=$search&sort=&order=nombreCompleto&sort=$sort>Nombre completo</th>";
                            echo "<th><a href=?search=$search&sort=&order=idTipoIdentificacion&sort=$sort>Tipo de<br>identificación</th>";
                            echo "<th><a href=?search=$search&sort=&order=identificacion&sort=$sort>Identificación</th>";
                            echo "<th><a href=?search=$search&sort=&order=email&sort=$sort>Correo electrónico</th>";
                            echo "<th><a href=?search=$search&sort=&order=telefonoPersonal&sort=$sort>Teléfono<br>personal</th>";
                            echo "<th><a href=?search=$search&sort=&order=telefonoAcudiente&sort=$sort>Teléfono<br>del acudiente</th>";
                            echo "<th><a href=?search=$search&sort=&order=fechaNacimiento&sort=$sort>Fecha<br>de nacimiento</th>";
                            echo "<th><a href=?search=$search&sort=&order=idTipoGenero&sort=$sort>Género</th>";
                            echo "<th><a href=?search=$search&sort=&order=direccionResidencia&sort=$sort>Dirección de la residencia</th>";
                            echo "<th><a href=?search=$search&sort=&order=idMunicipio&sort=$sort>Municipio</th>";
                            echo "<th><a href=?search=$search&sort=&order=idDepartamento&sort=$sort>Departamento</th>";
                            echo "<th><a href=?search=$search&sort=&order=idCentroFormacion&sort=$sort>Centro de formación</th>";
                            echo "<th><a href=?search=$search&sort=&order=idFichaFormacion&sort=$sort>Ficha de formación</th>";
                            echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=estado&sort=$sort>Estado del registro</th>";
                            echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=auditoria&sort=$sort>Fecha/Hora<br>de auditoría</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($resultMSQLI)) {
                                echo "<tr>";
                                echo "<td class='centrar-columna'>";
                                echo "<a href='../personas/personas-read.php?idPersona=". $row['idPersona'] ."'><i class='far fa-user'></i></a>";
                                echo "<span class='$isDisabled'>" . "<a href='personas-update.php?idPersona=" . $row['idPersona'] . "' aria-disabled='$ariaDisabled'>" . "<i class='far fa-edit'></i></a></span>";
                                echo "<span class='$isDisabled'>" . "<a href='personas-delete.php?idPersona=" . $row['idPersona'] . "' aria-disabled='$ariaDisabled'>" . "<i class='far fa-trash-alt'></i></a></span>";
                                echo "</td>";
                                echo "<td class='ocultar-columna'>" . $row['idPersona'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['nombreLargoVinculacion'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['nombreCompleto'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['nombreLargoIdentificacion'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['identificacion'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['email'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['telefonoPersonal'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['telefonoAcudiente'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['fechaNacimiento'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['nombreLargoGenero'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['direccionResidencia'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['nombreMunicipio'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['nombreDepartamento'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['nombreLargoCentroFormacion'] . "</td>";
                                echo "<td class='centrar-columna'>" . $row['codigoFichaFormacion'] . "</td>";
                                echo "<td class='ocultar-columna'>" . $row['estado'] . "</td>";
                                echo "<td class='ocultar-columna'>" . $row['auditoria'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                            ?>

                                <ul class="pagination" align-right>
                                <?php $new_url = preg_replace('/&?pageno=[^&]*/', '', $currenturl); ?> 
                                    <li class="page-item"><a class="page-link" href="<?php echo $new_url . '&pageno=1'; ?>">Primera</a></li>
                                    <li class="page-item <?php if ($pageno <= 1) {
                                        echo 'disabled';
                                    } ?>">
                                        <a class="page-link" href="<?php if ($pageno <= 1) {
                                            echo '#';
                                        } else {
                                            echo $new_url . "&pageno=" . ($pageno - 1);
                                        } ?>">Anterior</a>
                                    </li>
                                    <li class="page-item <?php if ($pageno >= $total_pages) {
                                        echo 'disabled';
                                    } ?>">
                                        <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                            echo '#';
                                        } else {
                                            echo $new_url . "&pageno=" . ($pageno + 1);
                                        } ?>">Siguiente</a>
                                    </li>
                                    <li class="page-item <?php if ($pageno >= $total_pages) {
                                        echo 'disabled';
                                    } ?>">
                                        <a class="page-item"><a class="page-link" href="<?php echo $new_url . '&pageno=' . $total_pages; ?>">Última</a>
                                    </li>
                                </ul>
                            <?php // Free result set
                            mysqli_free_result($resultMSQLI);
                        } else {
                            echo "<p class='lead sin-registros'><em>No se encontraron coincidencias.</em></p>";
                        }
                    } else {
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
</body>
</html>