<?php
require_once "../core/config.php";
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
if (!(strstr($_SESSION['permisosRolSistema'], "[usuario-encuestas]") != '')) {
    header("Location: ../core/menu.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuesta de verificación de signos y síntomas diarios COVID-19 para control de ingreso a sedes del SENA <?php echo $regionalSedes ?></title>
    <link rel="stylesheet" href="../css/bootstrap-4.5.3.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="../js/kit-fontawesome-6b773fe9e4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="icon" href="../imagenes/favicon.ico" type="image/png" />
</head>
<body>

    <!-- GESTIÓN DE BASES DE DATOS -->

    <?php

        $idPersona = $_SESSION['idPersona'];

        $sql1 = "SELECT PE.*, 
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
            WHERE idPersona = $idPersona";

            $result1 = mysqli_query($link, $sql1);

            if (mysqli_num_rows($result1) > 0) {

                $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);

            }

        $sql2 = "SELECT PR.* 
            FROM preguntas_encuesta PR
            WHERE estado = 1
            ORDER BY orden";

            $result2 = mysqli_query($link, $sql2);

            if (mysqli_num_rows($result2) > 0) {

                // Mensaje de error en caso de no haber preguntas disponibles

                // $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

            }

    ?>

    <form method="post" action="procesar-encuesta.php" id="frmEncuesta">

        <section class="pt-4">

            <div class="container-fluid index">

                <div class="row">

                    <div class="col-md-12">

                        <div class="titulo-encuesta">

                            Encuesta de verificación de signos y síntomas diarios COVID-19<br>para control de ingreso a sedes del SENA <?php echo $regionalSedes ?>
                        
                        </div>

                        <div class="worko-tabs">
                          
                            <input class="state" type="radio" title="tab1" name="tabs-state" id="tab1" checked />
                            
                            <input class="state" type="radio" title="tab2" name="tabs-state" id="tab2" />
                            
                            <input class="state" type="radio" title="tab3" name="tabs-state" id="tab3" />
                            
                            <input class="state" type="radio" title="tab4" name="tabs-state" id="tab4" />

                            <input class="state" type="radio" title="tab5" name="tabs-state" id="tab5" />
                            
                            <div class="tabs flex-tabs">
                                
                                <label for="tab1" id="tab1-label" class="tab">Consideraciones previas</label>
                                
                                <label for="tab2" id="tab2-label" class="tab">Registro sensible de datos</label>
                                
                                <label for="tab3" id="tab3-label" class="tab">Datos básicos</label>
                                
                                <label for="tab4" id="tab4-label" class="tab">Control de síntomas diarios</label>
                                
                                <label for="tab5" id="tab5-label" class="tab">Permiso de ingreso</label>

                                <div id="tab1-panel" class="panel active">

                                    <div class="texto-tab1">

                                        <div class="texto-tab1a">
                                            <b>CONSIDERACIONES A TENER EN CUENTA ANTES DE REGISTRAR SU INGRESO</b>
                                            
                                            <span class="sangria-numeracion"><b>1.</b> Para el SENA hay restricción de labores en instalaciones para personas mayores de (60) años y/o que presenten enfermedades preexistentes o en su entorno familiar (Diabetes, Enfermedad Cardiovascular - Hipertensión Arterial HTA -, Accidente Cerebrovascular - ACV -, VIH, Cáncer, Uso de Corticoides o Inmunosupresores, Enfermedad Pulmonar Obstructiva Crónica - EPOC -, mal nutrición (obesidad y desnutrición), Fumadores o con personal de servicios de salud).</span>
                                            <span class="sangria-numeracion"><b>2.</b> En caso de personal contratista externo, el supervisor de la obra o proyecto, previamente debe informar con el nivel respectivo del SENA, que dicho contratista cumple con el protocolo de bioseguridad (Resolución 0666 del 24 de abril de 2020 emitida por el Ministerio de Salud y Protección Social).</span>
                                            <span class="sangria-numeracion"><b>3.</b> Se le recomienda priorizar el uso de medios alternativos de transporte. En caso de vehículo particular, limpiar y desinfectar (timón, manijas, sillas, botones, palanca y freno) antes del inicio de los traslados. Uso obligatorio de tapabocas.</span>
                                        </div>

                                        <div class="texto-tab1b">
                                            <b>DURANTE LA ACTIVIDAD</b>

                                            <span class="sangria-numeracion"><b>4.</b> Al momento de verificar su acceso al SENA, si presenta síntomas de gripa no se autorizará su ingreso.</span>
                                            <span class="sangria-numeracion"><b>5.</b> El uso del tapabocas es permanente y obligatorio. Además debe ser ajustable y hacer un uso adecuado del mismo.</span>
                                            <span class="sangria-numeracion"><b>6.</b> De acuerdo a la jornada y tipo de labor, el personal tendrá una ropa exclusiva para uso en el trabajo.</span>
                                            <span class="sangria-numeracion"><b>7.</b> Lavar sus manos con agua y jabón al menos durante 40 a 60 segundos, cada 2 horas (apoyar el aseo con el gel antibacterial).</span>
                                            <span class="sangria-numeracion"><b>8.</b> Promover y mantener el distanciamiento social de 2 metros entre personas.</span>
                                            <span class="sangria-numeracion"><b>9.</b> Evitar al máximo reuniones, en caso de ser estrictamente necesarias, mantener distanciamiento social, protocolo de limpieza y desinfección pre y posterior a la reunión en espacio abierto y ventilado.</span>
                                        </div>

                                    </div>

                                </div>

                                <div id="tab2-panel" class="panel">

                                    <div class="texto-tab2">

                                        <div class="texto-tab2a">
                                            <b>AUTORIZACION EN EL REGISTRO SENSIBLE DE DATOS</b>

                                            Autorizo al SENA y al personal de apoyo el tratamiento de mis datos personales, incluyendo los de salud que son sensibles, con la finalidad de desarrollar acciones de promoción, prevención, tratamiento para la gestión de riesgo en salud y/o frente a la propagación, contagio y control de COVID-19, acorde con lo normado por el Ministerio de Salud y Protección Social y las demás autoridades competentes, de conformidad con la Ley 1581 de 2012 (Ley de Protección de Datos Personales).

                                            Será posible compartir información con distintas entidades públicas (del nivel central, descentralizadas y de vigilancia y control, y demás autoridades administrativas y jurisdiccionales), para tomar acciones frente a la propagación, contagio y control de COVID-19.

                                            Se entiende por tratamiento de datos, la facultad que tendrá el SENA y el personal de apoyo de almacenar, compartir, utilizar, procesar, recolectar, divulgar, transferir, transmitir, información relacionada con mi estado de salud, en cumplimiento de las disposiciones legales.
                                        </div>

                                        <div class="texto-tab2b">

                                            <br>
                                            <br>
                                            <br>

                                            <input type="radio" name="autorizacionTratamientoDatos" value="0">&nbsp;&nbsp;&nbsp;No autorizo.

                                            <br>
                                            <br>

                                            <input type="radio" name="autorizacionTratamientoDatos" value="1">&nbsp;&nbsp;&nbsp;Si autorizo.

                                            <br>
                                            <br>
                                            <br>

                                            <input type="checkbox" name="aceptacionConsideraciones">&nbsp;&nbsp;&nbsp;Acepto y declaro haber leído y entendido la sección de <b>"Consideraciones previas"</b>.
                                            
                                        </div>

                                    </div>

                                </div>

                                <div id="tab3-panel" class="panel">

                                    <div class="texto-tab3">

                                        <div class="texto-tab3a">

                                            <div class="texto-tab3-col1">

                                                <div class="ajuste-renglon1"></div>
                                                <label class="label-encuesta-tabla">Tipo de vinculación</label>
                                                <label class="label-encuesta-tabla">Nombre completo</label>
                                                <label class="label-encuesta-tabla">Tipo de identificación</label>
                                                <label class="label-encuesta-tabla">Identificación</label>
                                                <label class="label-encuesta-tabla">Correo electrónico</label>
                                                <label class="label-encuesta-tabla">Teléfono personal</label>
                                                <label class="label-encuesta-tabla">Teléfono del acudiente</label>
                                                <label class="label-encuesta-tabla">Fecha de nacimiento</label>
                                                <label class="label-encuesta-tabla">Edad actual</label>
                                                <label class="label-encuesta-tabla">Género</label>
                                                <label class="label-encuesta-tabla">Dirección de la residencia</label>
                                                <label class="label-encuesta-tabla">Departamento</label>
                                                <label class="label-encuesta-tabla">Municipio</label>
                                                <label class="label-encuesta-tabla">Centro de formación</label>
                                                <label class="label-encuesta-tabla">Ficha de formación</label>
                                                
                                            </div>

                                            <div class="texto-tab3-col2">

                                                <input type="text" name="idTipoVinculacion" class="form-control reducido campo-tabla" value="<?php echo $row1['nombreLargoVinculacion']; ?>" readonly>

                                                <input type="text" name="nombreCompleto" class="form-control reducido campo-tabla" value="<?php echo $row1['nombreCompleto']; ?>" readonly>

                                                <input type="text" name="idTipoIdentificacion" class="form-control reducido campo-tabla" value="<?php echo $row1['nombreLargoIdentificacion']; ?>" readonly>

                                                <input type="text" name="identificacion" class="form-control reducido campo-tabla" value="<?php echo $row1['identificacion']; ?>" readonly>

                                                <input type="text" name="email" class="form-control reducido campo-tabla" value="<?php echo $row1['email']; ?>" readonly>

                                                <input type="text" name="telefonoPersonal" class="form-control reducido campo-tabla" value="<?php echo $row1['telefonoPersonal']; ?>" readonly>

                                                <input type="text" name="telefonoAcudiente" class="form-control reducido campo-tabla" value="<?php echo $row1['telefonoAcudiente']; ?>" readonly>

                                                <input type="date" name="fechaNacimiento" class="form-control reducido campo-tabla" value="<?php echo $row1['fechaNacimiento']; ?>" readonly>

                                                <?php 

                                                    // date in Y-m-d format as MySQL stores it
                                                    $mysql_date = $row1['fechaNacimiento']; 
                                                    $date_obj = date_create_from_format('Y-m-d',$mysql_date);
                                                    $today = new DateTime();
                                                    $years = $today->diff($date_obj);
                                                    //echo $years->y;

                                                ?>

                                                <input type="text" name="edadActual" class="form-control reducido campo-tabla" value="<?php echo $years->y . ' años'; ?>" readonly>

                                                <input type="text" name="idTipoGenero" class="form-control reducido campo-tabla" value="<?php echo $row1['nombreLargoGenero']; ?>" readonly>

                                                <input type="text" name="direccionResidencia" class="form-control reducido campo-tabla" value="<?php echo $row1['direccionResidencia']; ?>" readonly>

                                                <input type="text" name="idDepartamento" class="form-control reducido campo-tabla" value="<?php echo $row1['nombreDepartamento']; ?>" readonly>

                                                <input type="text" name="idMunicipio" class="form-control reducido campo-tabla" value="<?php echo $row1['nombreMunicipio']; ?>" readonly>

                                                <input type="text" name="idCentroFormacion" class="form-control reducido campo-tabla" value="<?php echo $row1['nombreLargoCentroFormacion']; ?>" readonly>

                                                <input type="text" name="idFichaFormacion" class="form-control reducido campo-tabla" value="<?php echo $row1['codigoFichaFormacion']; ?>" readonly>
                                                
                                            </div>

                                        </div>

                                        <div class="texto-tab3b">

                                            <div class="texto-tab3-col1">
                                                
                                                <div class="ajuste-renglon1"></div>
                                                <label class="label-encuesta-tabla">Sede de ingreso</label>
                                                <label class="label-encuesta-tabla">Horario</label>

                                            </div>
                                            
                                            <div class="texto-tab3-col2">
                                            
                                                <?php
                                                    $sql_cb2 = "SELECT idCentroFormacion, nombreLargoCentroFormacion, nombreCorto FROM centros_formacion";
                                                    $result_cb2 = mysqli_query($link, $sql_cb2);
                                                    echo "<select name='idSedeIngreso' id='cb2' class='combo-box form-control campo-tabla'>";
                                                    while($row = mysqli_fetch_array($result_cb2)) {
                                                        echo "<option class='item-combo-box' value='" . $row['idCentroFormacion'] . "'>" . $row['nombreLargoCentroFormacion'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                ?>

                                                <?php
                                                    $sql_cb3 = "SELECT idHorario, nombreCorto FROM horarios ORDER BY horaInicial, horaFinal";
                                                    $result_cb3 = mysqli_query($link, $sql_cb3);
                                                    echo "<select name='idHorario' id='cb3' class='combo-box form-control campo-tabla'>";
                                                    while($row = mysqli_fetch_array($result_cb3)) {
                                                        if ($idHorario != $row['idHorario'])
                                                            echo "<option class='item-combo-box' value='" . $row['idHorario'] . "'>" . $row['nombreCorto'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                ?>

                                                <p class="aviso-datos-personales"><b>IMPORTANTE</b></p>

                                                <p class="aviso-datos-personales">La información acá presentada es la que el SENA posee sobre usted o la que usted ha brindado a nuestro personal para hacer uso de este sistema.</p>

                                                <p class="aviso-datos-personales">Por su seguridad y salud, así como por la nuestra, lo invitamos a revisarla detalladamente y solicitar alguna actualización, corrección o ajuste que nos permita aplicar el protocolo de la manera indicada.</p>
                                                
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div id="tab4-panel" class="panel">

                                    <div class="texto-tab4">

                                        <div class="texto-tab4a">

                                            <div class="texto-tab4-col1">
                                                
                                                <?php 

                                                    echo "<span class='inicio-preguntas'>¿Ha presentado alguno de los siguientes síntomas en las últimas 24 horas?</span><br>";

                                                    $cantidad = mysqli_num_rows($result2);

                                                    if ($cantidad % 2 == 0) {
                                                        $mitad = $cantidad / 2;
                                                    } else {
                                                        $mitad = intdiv($cantidad, 2) + 1;
                                                    }

                                                    $i = 1;
                                                    while ($i <= $mitad) {

                                                        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

                                                        $pregunta = $row2['textoPregunta'];
                                                        $identificadorSi = "idPregunta_" . $i . "_SI";
                                                        $identificadorNo = "idPregunta_" . $i . "_NO";
                                                        echo "<label class='label-encuesta-preguntas sangria-numeracion-preguntas'>$i. $pregunta</label>";
                                                        $idPregunta = "idPregunta_" . $row2['idPreguntaEncuesta'];
                                                        echo "<div class='bloqueSiNo'>";
                                                        echo "<input type='radio' name='$idPregunta' id='$identificadorNo' value='0'>&nbsp;&nbsp;&nbsp;No";
                                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
                                                        echo "<input type='radio' id='$identificadorSi' name='$idPregunta' value='1'>&nbsp;&nbsp;&nbsp;Si";
                                                        echo "</div>";

                                                        $i = $i + 1;

                                                    }

                                                ?> 

                                            </div>
                                            
                                        </div>

                                        <div class="texto-tab4b">

                                            <div class="texto-tab4-col2">
                                                
                                                <?php 

                                                    $i = $mitad + 1;
                                                    while ($i <= $cantidad) {

                                                        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

                                                        $pregunta = $row2['textoPregunta'];
                                                        $identificadorSi = "idPregunta_" . $i . "_SI";
                                                        $identificadorNo = "idPregunta_" . $i . "_NO";
                                                        echo "<label class='label-encuesta-preguntas sangria-numeracion-preguntas'>$i. $pregunta</label>";
                                                        $idPregunta = "idPregunta_" . $row2['idPreguntaEncuesta'];
                                                        echo "<div class='bloqueSiNo'>";
                                                        echo "<input type='radio' name='$idPregunta' id='$identificadorNo' value='0'> No";
                                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
                                                        echo "<input type='radio' id='$identificadorSi' name='$idPregunta' value='1'> Si";
                                                        echo "</div>";

                                                        $i = $i + 1;

                                                    }

                                                ?> 

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div id="tab5-panel" class="panel">

                                    <div class="texto-tab5">

                                        <div class="texto-tab5a">

                                           <div class="observaciones-encuesta">
                                                <textarea name="observacionAdicional" class="form-control txt-observaciones" rows="4" placeholder="Registre aquí alguna observación o consideración no tenida en cuenta en la encuesta."></textarea>
                                            </div>

                                            <p class="aviso-consideraciones">Su salud y la nuestra dependen de que usted y todas las personas que ingresan a las sedes del SENA tengan en cuenta las consideraciones antes mencionadas al inicio de la encuesta.</p>

                                            <p class="aviso-automatico">Conforme a sus respuestas, esta encuesta le indicará de manera automática su permiso de ingreso a las sedes del SENA. Independiente de si es aprobado o no, en el SENA siempre habrá alguien que le pueda orientar.</p>

                                            <br>

                                            <span class="texto-ingreso">
                                                 <input type="checkbox" name="aptoIngreso" id="aptoIngreso"value="1">&nbsp;&nbsp;&nbsp;APTO PARA INGRESO.
                                            </span>

                                            <br>

                                            <span class="texto-ingreso">
                                                <input type="checkbox" name="noAptoIngreso" id="noAptoIngreso"value="1">&nbsp;&nbsp;&nbsp;INGRESO NO APROBADO.
                                            </span>

                                            <br>
                                            <br>

                                            <input type="hidden" name="aceptacionRespuestaPositiva" id="aceptacionRespuestaPositiva" value="1">

                                            <button type="submit" name="login" value="login" class="btn btn-info btn-enviar-encuesta submit">ENVIAR ENCUESTA Y REGISTRAR SUS DATOS</button>

                                        </div>

                                        <div class="texto-tab5b">

                                            <p class="aviso-consideraciones">Según los síntomas registrados en esta encuesta, por su salud y la nuestra, nos reservamos el derecho de admisión a la sede del SENA y lo invitamos a seguir las siguientes recomendaciones.</p>

                                            <p class="aviso-recomendaciones">1. Terminar de diligenciar esta encuesta y enviar los resultados.</p>

                                            <p class="aviso-recomendaciones">2. Dar aviso inmediato a nuestro personal encargado para que le brinden orientación:</p>
                                            
                                            <p class="aviso-responsable-covid19">- Cargo: <b><?php echo $cargoResponsableCovid19; ?></b></p>
                                            
                                            <p class="aviso-responsable-covid19">- Nombre: <b><?php echo $nombreResponsableCovid19; ?></b></p>

                                            <p class="aviso-responsable-covid19">- Email: <b><?php echo $emailResponsableCovid19; ?></b></p>

                                            <p class="aviso-responsable-covid19">- Teléfono #1: <b><?php echo $telefono1ResponsableCovid19; ?></b></p>

                                            <p class="aviso-responsable-covid19">- Teléfono #2: <b><?php echo $telefono2ResponsableCovid19; ?></b></p>

                                            <p class="aviso-recomendaciones">3. Solicitar asesoría médica urgente con su EPS.</p>

                                            <p class="aviso-recomendaciones">4. Continuar con el uso permanente del tapabocas y lavado de manos.</p>

                                            <p class="aviso-recomendaciones">5. Permanecer en aislamiento preventivo hasta que reciba la atención pertinente.</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </form>   

    <?php

        /* liberar la serie de resultados */
        mysqli_free_result($result1);
        mysqli_free_result($result2);

        /* cerrar la conexión */
        mysqli_close($link);

    ?>

    <script src="../js/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="../js/bootstrap.bundle-4.5.3.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="../js/libreria.js" type="text/javascript"></script>   

    <script type="text/javascript">

        $(document).ready(function(){
            
             validarEncuesta("#frmEncuesta");

        });

    </script>

</body>
</html>