/*===========================================
=            SETEAR VALIDACIONES            =
===========================================*/
$.validator.setDefaults({
    errorPlacement: function (error, element) {
        // Add the `help-block` class to the error element
        error.addClass("invalid-feedback");
        if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
        } else {
            error.insertAfter(element);
        }
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("is-valid").removeClass("is-invalid");
    }
});
$.validator.addMethod("decimal", function (value, element) {
    return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
}, "Solo puede ingresa valores numéricos.");
/*=====  End of SETEAR VALIDACIONES  ======*/

jQuery(document).ready(function () {

    /*
        Fullscreen background
    */
    // $.backstretch("assets/images/fondo_bee.jpg");


    /*
        Form validation
    */
    $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function () {
        $(this).removeClass('input-error');
    });

    $('.login-form').on('submit', function (e) {

        $(this).find('input[type="text"], input[type="password"], textarea').each(function () {
            if ($(this).val() == "") {
                e.preventDefault();
                $(this).addClass('input-error');
            }
            else {
                $(this).removeClass('input-error');
            }
        });

    });


});

$('#FormRegistronewuser').validate({
    ignore: [],
    rules: {
        // ... tus reglas de validación
        newnombre: { required: true },
        newapellido: { required: true },
    },
    submit: function (form) {
        form.submit();
    },
    submitHandler: function () {
        enviarFormulario('#FormRegistronewuser', function (json) {
            if (json.success) {
                $('#FormRegistronewuser input[name=newapellido]').val('');
                $('#FormRegistronewuser input[name=newnombre]').val('');
                // ... Limpiar otros campos si es necesario

                // Mostrar mensaje de SweetAlert para éxito
                Swal.fire({
                    title: '¡Usuario registrado!',
                    text: json.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redireccionar o realizar otras acciones si es necesario
                        location.reload();
                    }
                });
            } else {
                // Mostrar mensaje de SweetAlert para error
                Swal.fire({
                    title: 'Error',
                    text: json.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
});

// Función enviarFormulario para realizar la solicitud AJAX
function enviarFormulario(formId, callback) {
    $.ajax({
        type: 'POST',
        url: 'auth/registrarnewusuario', // Reemplaza con tu URL
        data: $(formId).serialize(),
        dataType: 'json', // Especifica que esperamos una respuesta JSON
        success: function (response) {
            callback(response); // Llama a la función de callback con la respuesta JSON
        },
        error: function () {
            console.error('Error al enviar la solicitud');
            // Swal.fire({
            //     title: 'Error',
            //     text: "Error al enviar la solicitud",
            //     icon: 'error',
            //     confirmButtonText: 'OK'
            // });
        }
    });
}


function buscar_campos() {

    // $('#capa_load').html('<img src="<?= base_url_app() ?>assets/images/loading.gif" alt="" style="position: absolute;top: 10px;left: 46%;">');

    if ($('#tipo_documento').val() == "dni") {

        $('#capa_datos_dni').css('display', 'block');

        $('#capa_datos_ruc').css('display', 'none');

        $('#documento').val("");

        $('#capa_load').html("");

        $("#frm_consulta")[0].reset();

    } else if ($('#tipo_documento').val() == "ruc") {

        $('#capa_datos_ruc').css('display', 'block');

        $('#capa_datos_dni').css('display', 'none');

        $('#capa_load').html("");

        $('#documento').val("");

        $("#frm_consulta")[0].reset();

    }

}

function buscar() {

       tipo_doc = 1;

    if (tipo_doc == "") {

        alert("Debes seleccionar un tipo de documento.");

    } else {

        $('#capa_load').html('<img src="<?= base_url_app() ?>assets/images/loading.gif" alt="" style="position: absolute;top: 10px;left: 46%;">');

        $.post('../app/application/controllers/validardatos.php', {
            dni: $('#newruc').val(),
            tipo_doc: tipo_doc
        }, function(data) {



            if (tipo_doc == "2") {

                var datos = eval(data);

                if (datos == ",,,") {

                    alert("No existe este DNI.");

                    $('#capa_load').html("");

                    $("#FormVentaAgregarCliente")[0].reset();

                } else {

                    $('#txt_documento').val(datos[0]);

                    $('#txt_nombre').val(datos[5]);

                    $('#txt_direccion').val(datos[4]);

                    $('#fnacimiento').val(datos[6]);


                    $('#capa_load').html("");

                }

            } else {

                var datos = eval(data);

                var nada = 'nada';

                doc = $('#newruc').val();

                if (doc.length < 11) {
                    alert("ingrese ruc valido");

                }

                if (datos[0] == nada) {

                    alert('RUC no válido o no registrado');

                    $('#capa_load').html("");

                    $("#FormVentaAgregarCliente")[0].reset();

                } else {

                    $('#newruc').val(datos[0]);
                    $('#newrsocial').val(datos[1]);                  

                }

                $('#capa_load').html("");

            }



        });

    }

}


function soloNumeros(e) {

    var key = window.event ? e.which : e.keyCode;

    if (key < 48 || key > 57) {

        //Usando la definición del DOM level 2, "return" NO funciona.

        e.preventDefault();

    }

}
