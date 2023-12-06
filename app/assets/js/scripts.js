
jQuery(document).ready(function() {
	
    /*
        Fullscreen background
    */
    // $.backstretch("assets/images/fondo_bee.jpg");

    
    /*
        Form validation
    */
    $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    
    $('.login-form').on('submit', function(e) {
    	
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function(){
    		if( $(this).val() == "" ) {
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
    rules: {
        // ... tus reglas de validación
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
                        window.location.href = 'tu_pagina_destino.html';
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
        success: function(response) {
            callback(response); // Llama a la función de callback con la respuesta JSON
        },
        error: function() {
            console.error('Error al enviar la solicitud');
        }
    });
}

