$(function(){

/*==================================================
=            SERIALIZAR DATOS EN OBJETO            =
==================================================*/
$.fn.serializeObject = function()
{
		var o = {};
		var a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name] !== undefined) {
				if (!o[this.name].push) {
						o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
};
/*=====  End of SERIALIZAR DATOS EN OBJETO  ======*/

/*========================================
=            REDONDEAR NUMERO            =
========================================*/
function round(num, decimales = 2) {
	var signo = (num >= 0 ? 1 : -1);
	num = num * signo;
	if (decimales === 0) //con 0 decimales
			return signo * Math.round(num);
	// round(x * 10 ^ decimales)
	num = num.toString().split('e');
	num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
	// x * 10 ^ (-decimales)
	num = num.toString().split('e');
	signo = signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
	return signo.toFixed(2);
}

/*=====  End of REDONDEAR NUMERO  ======*/


$("[id^=Modal]").appendTo("body"); //ENVIAR MODAL AL FINAL DEL BODY


/*===========================================
=            SETEAR VALIDACIONES            =
===========================================*/
$.validator.setDefaults({
	errorPlacement: function ( error, element ) {
		// Add the `help-block` class to the error element
		error.addClass( "help-block" );
		if ( element.prop( "type" ) === "checkbox" ) {
			error.insertAfter( element.parent( "label" ) );
		} else {
			error.insertAfter( element );
		}
	},
	highlight: function ( element, errorClass, validClass ) {
		$( element ).parent().addClass( "has-error" ).removeClass( "has-success" );
	},
	unhighlight: function (element, errorClass, validClass) {
		$( element ).parent().addClass( "has-success" ).removeClass( "has-error" );
	}
});
/*=====  End of SETEAR VALIDACIONES  ======*/

/*===================================*/
$('#FormRegistrarUsuario').validate({
	ignore: [],
  rules: {
    apellidos:{required:true},
    nombres:{required:true},
    tipo_documento:{required:true},
    documento:{required:true},
    tipo_usuario:{required:true},
    usuario:{required:true},
    email:{
    	required:true,
    	email:true,
    	remote:path+'regpersona/validaEmail'
    },
    password:{
    	required:true,
    	minlength:[6],
			noEspacio:true
		},
    passconf:{
    	required:true,
    	minlength:[6],
			noEspacio:true,
    	equalTo:'#password'
    },
  },
  messages:{
  	email:{
  		remote:'Este email ya esta registrado'
  	}
  },
  submitHandler:function() {
    sendForm('#FormRegistrarUsuario',function(resp){
    	if (!resp['success']) {
    		swal(
				  'Error!',
				  'Algo salio mal vuelve a intentarlo mas tarde.',
				  'error'
				);
    	}
    });
  }
});
});