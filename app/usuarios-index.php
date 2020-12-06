<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6b773fe9e4.js" crossorigin="anonymous"></script>
    <style type="text/css">
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 5px;
        }
        body {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <section class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="float-left">Usuarios del sistema - Detalle</h2>
                        <a href="usuarios-create.php" class="btn btn-success float-right">Agregar registro</a>
                        <a href="usuarios-index.php" class="btn btn-info float-right mr-2">Restablecer vista</a>
                        <a href="index.php" class="btn btn-secondary float-right mr-2">Menú Inicial</a>
                    </div>

                    <div class="form-row">
                        <form action="usuarios-index.php" method="get">
                        <div class="col">
                          <input type="text" class="form-control" placeholder="Buscar en esta tabla" name="search">
                        </div>
                    </div>
                        </form>
                    <br>

                    <?php
                    // Include config file
                    require_once "config.php";

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
                    $result = mysqli_query($link,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);
                    
                    //Column sorting on column name
                    $orderBy = array('idUsuario', 'idTipoVinculacion', 'nombreCompleto', 'idTipoIdentificacion', 'identificacion', 'email', 'telefonoPersonal', 'fechaNacimiento', 'idTipoGenero', 'direccionResidencia', 'idMunicipio', 'idDepartamento', 'idCentroFormacion', 'idRolSistema', 'passwordSistema', 'estado', 'auditoria'); 
                    $order = 'idUsuario';
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
                    $sql = "SELECT * FROM usuarios ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT * FROM usuarios";

                    
                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);
                        $sql = "SELECT * FROM usuarios
                            WHERE CONCAT (idUsuario,idTipoVinculacion,nombreCompleto,idTipoIdentificacion,identificacion,email,telefonoPersonal,fechaNacimiento,idTipoGenero,direccionResidencia,idMunicipio,idDepartamento,idCentroFormacion,idRolSistema,passwordSistema,estado,auditoria)
                            LIKE '%$search%'
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
                        $count_pages = "SELECT * FROM usuarios
                            WHERE CONCAT (idUsuario,idTipoVinculacion,nombreCompleto,idTipoIdentificacion,identificacion,email,telefonoPersonal,fechaNacimiento,idTipoGenero,direccionResidencia,idMunicipio,idDepartamento,idCentroFormacion,idRolSistema,passwordSistema,estado,auditoria)
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

                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th><a href=?search=$search&sort=&order=idUsuario&sort=$sort>Id Usuario</th>";
										echo "<th><a href=?search=$search&sort=&order=idTipoVinculacion&sort=$sort>Id Tipo de vinculación</th>";
										echo "<th><a href=?search=$search&sort=&order=nombreCompleto&sort=$sort>Nombre completo</th>";
										echo "<th><a href=?search=$search&sort=&order=idTipoIdentificacion&sort=$sort>Id Tipo de identificación</th>";
										echo "<th><a href=?search=$search&sort=&order=identificacion&sort=$sort>Identificación</th>";
										echo "<th><a href=?search=$search&sort=&order=email&sort=$sort>Correo electrónico</th>";
										echo "<th><a href=?search=$search&sort=&order=telefonoPersonal&sort=$sort>Teléfono personal</th>";
										echo "<th><a href=?search=$search&sort=&order=fechaNacimiento&sort=$sort>Fecha de nacimiento</th>";
										echo "<th><a href=?search=$search&sort=&order=idTipoGenero&sort=$sort>Id Género</th>";
										echo "<th><a href=?search=$search&sort=&order=direccionResidencia&sort=$sort>Dirección de la residencia</th>";
										echo "<th><a href=?search=$search&sort=&order=idMunicipio&sort=$sort>Id Municipio</th>";
										echo "<th><a href=?search=$search&sort=&order=idDepartamento&sort=$sort>Id Departamento</th>";
										echo "<th><a href=?search=$search&sort=&order=idCentroFormacion&sort=$sort>Id Centro de formación</th>";
										echo "<th><a href=?search=$search&sort=&order=idRolSistema&sort=$sort>Id Rol del sistema</th>";
										echo "<th><a href=?search=$search&sort=&order=passwordSistema&sort=$sort>Contraseña del sistema</th>";
										echo "<th><a href=?search=$search&sort=&order=estado&sort=$sort>Estado del registro</th>";
										echo "<th class='ocultar-columna'><a href=?search=$search&sort=&order=auditoria&sort=$sort>Fecha/Hora de auditoría</th>";
										
                                        echo "<th class='estilo-acciones'>Acciones</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . $row['idUsuario'] . "</td>";echo "<td>" . $row['idTipoVinculacion'] . "</td>";echo "<td>" . $row['nombreCompleto'] . "</td>";echo "<td>" . $row['idTipoIdentificacion'] . "</td>";echo "<td>" . $row['identificacion'] . "</td>";echo "<td>" . $row['email'] . "</td>";echo "<td>" . $row['telefonoPersonal'] . "</td>";echo "<td>" . $row['fechaNacimiento'] . "</td>";echo "<td>" . $row['idTipoGenero'] . "</td>";echo "<td>" . $row['direccionResidencia'] . "</td>";echo "<td>" . $row['idMunicipio'] . "</td>";echo "<td>" . $row['idDepartamento'] . "</td>";echo "<td>" . $row['idCentroFormacion'] . "</td>";echo "<td>" . $row['idRolSistema'] . "</td>";echo "<td>" . $row['passwordSistema'] . "</td>";echo "<td>" . $row['estado'] . "</td>";echo "<td class='ocultar-columna'>" . $row['auditoria'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='usuarios-read.php?idUsuario=". $row['idUsuario'] ."' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                                            echo "<a href='usuarios-update.php?idUsuario=". $row['idUsuario'] ."' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                                            echo "<a href='usuarios-delete.php?idUsuario=". $row['idUsuario'] ."' title='Borrar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>