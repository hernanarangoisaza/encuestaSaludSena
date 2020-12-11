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
    <title>Encuesta de verificación de signos y síntomas diarios COVID-19 para control de ingreso. SENA Risaralda</title>
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

                    <div class="worko-tabs">
                      
                        <input class="state" type="radio" title="tab1" name="tabs-state" id="tab1" checked />
                        
                        <input class="state" type="radio" title="tab2" name="tabs-state" id="tab2" />
                        
                        <input class="state" type="radio" title="tab3" name="tabs-state" id="tab3" />
                        
                        <input class="state" type="radio" title="tab4" name="tabs-state" id="tab4" />

                        <input class="state" type="radio" title="tab5" name="tabs-state" id="tab5" />
                        
                        <input class="state" type="radio" title="tab6" name="tabs-state" id="tab6" />

                        <div class="tabs flex-tabs">
                            
                            <label for="tab1" id="tab1-label" class="tab">Consideraciones previas</label>
                            
                            <label for="tab2" id="tab2-label" class="tab">Registro sensible de datos</label>
                            
                            <label for="tab3" id="tab3-label" class="tab">Datos básicos</label>
                            
                            <label for="tab4" id="tab4-label" class="tab">Control de síntomas diarios</label>
                            
                            <label for="tab5" id="tab5-label" class="tab">Permiso de ingreso</label>

                                <!-- Paso 5 SI -->
                                <!-- Paso 6 NO -->
                            
                            <label for="tab6" id="tab6-label" class="tab">Observaciones</label>

                            <div id="tab1-panel" class="panel active">
                                11111111111111
                            </div>

                            <div id="tab2-panel" class="panel">
                                2222222222222
                            </div>

                            <div id="tab3-panel" class="panel">
                                3333333333333
                            </div>

                            <div id="tab4-panel" class="panel">
                                44444444444444
                            </div>

                            <div id="tab5-panel" class="panel">
                                5555555555555555
                            </div>

                            <div id="tab6-panel" class="panel">
                                6666666666666
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>

    <script src="../js/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

</body>
</html>