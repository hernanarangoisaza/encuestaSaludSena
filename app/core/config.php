<?php

$db_server = 'localhost';
$db_user = 'root'; // ticcom_saludsena
$db_password = ''; // Sena100%
$db_name = 'sena'; // ticcom_senabd
$linkMYSQLI = mysqli_connect($db_server, $db_user, $db_password, $db_name);

$linkMYSQLI -> set_charset("utf8");
date_default_timezone_set("America/Bogota");

$no_of_records_per_page = 15;

$temperaturaFiebre = 38;
$aforoJornada = 500;
$regionalSedes = "Risaralda";
$sedeUso = "SENA - CDITI";

$cargoResponsableCovid19 = "Enfermera Profesional";
$nombreResponsableCovid19 = "Carolina Ayala Castellanos";
$emailResponsableCovid19 = "jcayalac@sena.edu.co";
$telefono1ResponsableCovid19 = "";
$telefono2ResponsableCovid19 = "";

// MENSAJES DE ERROR QUE APARECERÁN CUANDO SE IMPLEMENTE LA VALIDACIÓN A NIVEL DE CAMPO DE FORMULARIO
// APLICA PARA CREATE Y UPDATE
// <span class="form-text">$idSedeIngreso_err</span>

$aceptacionConsideraciones_err = "";
$aceptacionRespuestaPositiva_err = "";
$auditoria_err = "";
$autorizacionIngreso_err = "";
$autorizacionTratamientoDatos_err = "";
$codigoDepartamento_err = "";
$codigoFichaFormacion_err = "";
$codigoMunicipio_err = "";
$departamento_err = "";
$descripcionRolSistema_err = "";
$direccion_err = "";
$direccionResidencia_err = "";
$email_err = "";
$emailContacto1_err = "";
$emailContacto2_err = "";
$estado_err = "";
$fechaHoraDiligenciamiento_err = "";
$fechaHoraTomaEntrada_err = "";
$fechaHoraTomaSalida_err = "";
$fechaNacimiento_err = "";
$horaFinal_err = "";
$horaInicial_err = "";
$idCentroFormacion_err = "";
$idDepartamento_err = "";
$idEncuesta_err = "";
$identificacion_err = "";
$idFichaFormacion_err = "";
$idHorario_err = "";
$idMunicipio_err = "";
$idPersona_err = "";
$idPreguntaEncuesta_err = "";
$idProgramaFormacion_err = "";
$idRolSistema_err = "";
$idSedeIngreso_err = "";
$idTipoGenero_err = "";
$idTipoIdentificacion_err = "";
$idTipoVinculacion_err = "";
$idUsuario_err = "";
$jornada_err = "";
$municipio_err = "";
$nombreCompleto_err = "";
$nombreCorto_err = "";
$nombreLargoCentroFormacion_err = "";
$nombreLargoGenero_err = "";
$nombreLargoIdentificacion_err = "";
$nombreLargoVinculacion_err = "";
$nombreProgramaFormacion_err = "";
$nombreRol_err = "";
$observacionAdicional_err = "";
$passwordSistema_err = "";
$permisos_err = "";
$respuestaSiNo_err = "";
$restricciones_err = "";
$telefono1_err = "";
$telefono2_err = "";
$telefonoAcudiente_err = "";
$telefonoPersonal_err = "";
$temperaturaEntrada_err = "";
$temperaturaSalida_err = "";
$textoPregunta_err = "";




?>
