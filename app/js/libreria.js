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
  $(idForm + " button.submit").removeClass("btn-secondary");
  $(idForm + " button.submit").addClass("btn-info");
  $(idForm + " button.submit").text('ENVIAR ENCUESTA Y REGISTRAR SUS DATOS');
}
 
function disableSubmit (idForm) {
  $(idForm + " button.submit").attr("disabled", "disabled");
  $(idForm + " button.submit").removeClass("btn-info");
  $(idForm + " button.submit").addClass("btn-secondary"); 
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
      $(".textoNoAptoIngreso").removeClass("ocultar-elemento");
      $(".textoAptoIngreso").addClass("ocultar-elemento");
    } else {
      $("#noAptoIngreso").prop("checked", false);
      $("#aptoIngreso").prop("checked", true);
      $("#aceptacionRespuestaPositiva").val("0");
      $(".textoNoAptoIngreso").addClass("ocultar-elemento");
      $(".textoAptoIngreso").removeClass("ocultar-elemento");
   }
   return true;        
}

function contarRespuestasPositivas () {
   cantidad = $(".respuestaSi").length;
   if (cantidad > 0) {
      $("#aptoIngreso").prop("checked", false);
      $("#noAptoIngreso").prop("checked", true);
      $("#aceptacionRespuestaPositiva").val("1");
      $(".textoNoAptoIngreso").removeClass("ocultar-elemento");
      $(".textoAptoIngreso").addClass("ocultar-elemento");
    } else {
      $("#noAptoIngreso").prop("checked", false);
      $("#aptoIngreso").prop("checked", true);
      $("#aceptacionRespuestaPositiva").val("0");
      $(".textoNoAptoIngreso").addClass("ocultar-elemento");
      $(".textoAptoIngreso").removeClass("ocultar-elemento");
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

$(function () {

    /*
    $('#modalEnvioEncuesta').on('click', function(event) {
      var $button = $(event.target);
      alert('Hola');
    });
    */

    $("#modalEnvioEncuesta").on("click", function() {

        $("#frmEncuesta")[0].reset();
        $(location).attr('href', '../core/cerrar-encuesta.php');

    })

});

$(function () {

    var frm = $('#frmEncuesta');

    frm.submit(function (ev) {

        ev.preventDefault();

        $.ajax({
          type: frm.attr('method'),
          url:  frm.attr('action'),
          data: frm.serialize(),
          beforeSend: function(){

              /*
              * Esta función se ejecuta durante el envío de la petición al servidor. * 
              */

              // alert("beforeSend");

          },
          success: function(data){

              /*
              * Se ejecuta cuando termina la petición y esta ha sido correcta * 
              */
              
              // ENVIAR A PÁGINA DE AGRADECIMIENTO E INFORMANDO QUE DEBE CUMPLIR: SEDE, HORARIO, TOMA TEMPERATURA, LLEGAR CON TIEMPO.

              // alert("success");

          },
          error: function(data){

              /*
              * Se ejecuta si la peticón ha sido erronea * 
              */
              
              // NO PERMITIR BORRAR LA INFORMACIÓN DEL FORMULARIO E INFORMAR QUE LA TRANSMISIÓN FALLÓ.

              // alert("error");

          },
          complete:function(data){

              /*
              * Se ejecuta al termino de la petición * 
              */
              
              // ELIMINAR VARIABLES DE ENTORNO Y/O DEL FORMULARIO. REGRESAR A MENÚ Y DEJAR LIBRE ALLÍ AL USUARIO.


              // alert("complete");

              $("#btn-modal-enviar-encuesta").click();

          }

       });

    });

});
