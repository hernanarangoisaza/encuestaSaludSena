var namePattern = "^[a-z A-Z]{4,30}$";
var emailPattern = "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$";
 
function checkInput(idInput, pattern) {
  return $(idInput).val().match(pattern) ? true : false;
}

function checkTextarea(idText) {
  return $(idText).val().length > 12 ? true : false;    
}

function checkRadioBox(nameRadioBox) {
  return $(nameRadioBox).is(":checked") ? true : false;
}

function checkSelect(idSelect) {
  return $(idSelect).is(":checked") ? true : false;
}

function enableSubmit (idForm) {
  $(idForm + " button.submit").removeAttr("disabled");
  $(idForm + " button.submit").removeClass("btn-danger");
  $(idForm + " button.submit").addClass("btn-info");
  $(idForm + " button.submit").text('ENVIAR ENCUESTA Y REGISTRAR SUS DATOS');
}
 
function disableSubmit (idForm) {
  $(idForm + " button.submit").attr("disabled", "disabled");
  $(idForm + " button.submit").removeClass("btn-info");
  $(idForm + " button.submit").addClass("btn-danger"); 
  $(idForm + " button.submit").text('AÚN NO TERMINA DE DILIGENCIAR SU ENCUESTA');
}

function revisarRespuestasSintomas () {
    respuestas = $("[id^='idPregunta_']");
    cantidad = respuestas.length;
    conteo = 0;
    for(var i = 0; i < cantidad; i++)
    {
        if(respuestas[i].checked == true)
        {
            conteo++;
        } 
     } 
     return (conteo == (cantidad/2)) ? true : false;        
}

function confirmarRespuestasPositivas () {
   cantidad = $("[id$='_SI']:checked").length;
   if (cantidad > 0) {
      $("#aptoIngreso").prop("checked", false);
      $("#noAptoIngreso").prop("checked", true);
      $("#aceptacionRespuestaPositiva").val("1");
   } else {
      $("#noAptoIngreso").prop("checked", false);
      $("#aptoIngreso").prop("checked", true);
      $("#aceptacionRespuestaPositiva").val("0");
   }
   return true;        
}

function validarEncuesta (idForm) {
  $(idForm + " *").on("change keydown", function() {
    confirmarRespuestasPositivas()
    if (
        checkSelect("[name='aceptacionConsideraciones']") && 
        checkRadioBox("[name='autorizacionTratamientoDatos']") &&
        revisarRespuestasSintomas() 
       )
    {
      enableSubmit(idForm);
    } 
    else {
      disableSubmit(idForm);
    }
  });
}

$("#frmEncuesta").bind("submit",function(){

    // Capturamnos el boton de envío
    var btnEnviar = $(".btn-enviar-encuesta");

    $.ajax({
        type: $(this).attr("method"),
        url: $(this).attr("action"),
        data:$(this).serialize(),
        beforeSend: function(){

            /*
            * Esta función se ejecuta durante el envío de la petición al servidor. * 
            */

            $(".btn-enviar-encuesta").text('ENVIANDO ENCUESTA...');
            $(".btn-enviar-encuesta").removeClass("btn-info");
            $(".btn-enviar-encuesta").addClass("btn-success");
            $(".btn-enviar-encuesta").prop( "disabled", true );

        },
        success: function(data){

            /*
            * Se ejecuta cuando termina la petición y esta ha sido correcta * 
            */
            
            // ENVIAR A PÁGINA DE AGRADECIMIENTO E INFORMANDO QUE DEBE CUMPLIR: SEDE, HORARIO, TOMA TEMPERATURA, LLEGAR CON TIEMPO.

        },
        error: function(data){

            /*
            * Se ejecuta si la peticón ha sido erronea * 
            */
            
            // NO PERMITIR BORRAR LA INFORMACIÓN DEL FORMULARIO E INFORMAR QUE LA TRANSMISIÓN FALLÓ.

            $(".btn-enviar-encuesta").text('ENVIAR ENCUESTA Y REGISTRAR SUS DATOS');
            $(".btn-enviar-encuesta").removeClass("btn-success");
            $(".btn-enviar-encuesta").addClass("btn-info");
            $(".btn-enviar-encuesta").prop( "disabled", false );

        },
        complete:function(data){

            /*
            * Se ejecuta al termino de la petición * 
            */
            
            // ELIMINAR VARIABLES DE ENTORNO Y/O DEL FORMULARIO. REGRESAR A MENÚ Y DEJAR LIBRE ALLÍ AL USUARIO.

        }
    });

    // Nos permite cancelar el envio del formulario
    return false;

});
