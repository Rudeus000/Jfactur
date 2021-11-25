var path = $('body').data('path');
var path_app = $('body').data('path-app');
var movilexpert = $('body').data('movilexpert');
// $('body').addClass('enlarged');
// $('ul.collapse').removeClass('in');

/*==================================================
=            SERIALIZAR DATOS EN OBJETO            =
==================================================*/
$.fn.serializeObject = function () {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function () {
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


/*==================================================
=            BUSCAR DUPLICADOS EN ARRAY            =
==================================================*/
function buscar_duplicado_array(arra1) {
	var object = {};
	var result = [];

	arra1.forEach(function (item) {
		if(!object[item])
				object[item] = 0;
			object[item] += 1;
	})

	for (var prop in object) {
		 if(object[prop] >= 2) {
				 result.push(prop);
		 }
	}

	return result;

}
/*=====  End of BUSCAR DUPLICADOS EN ARRAY  ======*/


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



/*==================================
=            DATEPICKER            =
==================================*/
$('.datepicker').datepicker({
	autoclose: true,
	language: "es",
	format: "yyyy-mm-dd",
	todayHighlight: true
});

$('.timepicker').timepicker({
	showInputs: false,
	stepping: 15,
	showMeridian: false,
	icons: {
		up: 'fa fa-arrow-circle-up',
		down: 'fa fa-arrow-circle-down'
	}
});
/*=====  End of DATEPICKER  ======*/

/*===============================
=            SELECT2            =
===============================*/
$(".select2").select2();
/*=====  End of SELECT2  ======*/


/*===================================
=            HORA ACTUAL            =
===================================*/
function hora() {
	var f = new Date();
	cad = f.getHours() + ":" + f.getMinutes() + ":" + f.getSeconds();
	return window.status = cad;
}
/*=====  End of HORA ACTUAL  ======*/


/*===================================================
=            ENVIAR FORMULIARIO POR AJAX            =
===================================================*/
function enviarFormulario($form, callback) {
	var htmlSubmit = $($form).find('button:submit').html()
	$($form).ajaxSubmit({
		beforeSend: function () {			
			$($form).find('button:submit').prop('disabled', true).html('<i class="fa fa-sync fa-spin m-r-5"></i> Procesando');
		},
		success: function (data) {
			if (data.success) {
				if (typeof data.redirect != "undefined") {
					window.location.href = path + data.redirect;
				} else {
					Swal.fire({
						title: "Buen trabajo",
						text: "La solicitud ha sido procesada.",
						type: "success",
						timer: 2500
					});
					$($form)[0].reset();
					$($form).removeClass('has-success');
					$($form).find('.form-group').removeClass('has-success');
				}
				callback(data);
			} else {
				Swal.fire({
					title: "Error",
					text: "Ha ocurrido un error.",
					type: "error",
				});
			}
		},
		error: function (err) {
			Swal.fire({
				title: "Error",
				text: "Ha ocurrido un error.",
				type: "error",
			});
		},
		dataType: 'JSON',
		complete: function () {
			$($form).find('button:submit').prop('disabled', false).html(htmlSubmit);
		}
	});

}
/*=====  End of ENVIAR FORMULIARIO POR AJAX  ======*/


/*===================================================================
=            CONFIRMAR IR A METODO QUE ELIMINAR REGISTRO            =
===================================================================*/
$('tbody').on('click', 'a.confirm', function (e) {
	e.preventDefault(); // Prevent the href from redirecting directly
	var linkURL = $(this).attr("href");
	warnBeforeRedirect(linkURL);
});

function warnBeforeRedirect(linkURL) {
	Swal.fire({
		title: "Confirmar Eliminar",
		type: "warning",
		cancelButtonText: 'Cancelar',
		showCancelButton: true,
		confirmButtonColor: "#007AFF",
		cancelButtonColor: "#d43f3a",
		html: "Si confirmas eliminar el registro has click en el boton OK. <br /> <span style='font-size:12px' class='text-warning'> <i class='fa fa-asterisk'></i>Los registros que están relacionados también se eliminarán.</span>"
	}).then((result) => {
		if (result.value) {
			window.location.href = linkURL;
		}
	})
}
/*=====  End of CONFIRMAR IR A METODO QUE ELIMINAR REGISTRO  ======*/

$(function () {
	/*========================================
	=            GRUPO USUARIO - LISTADO       =
	===========================================*/

	var TableMantenimientoGrupo = $('#TableMantenimientoGrupo').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regrupo/jsonGrupo',
			"type": "GET",
			"data": function (d) {
				d.tb_grupo = $("input[name=tb_grupo]").val();

			}
		}
	});

	$('#GrupoFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoGrupo').DataTable().ajax.reload();
		}
	});


	/*==========================================
			 GRUPO USUARIO - VALIDAR Y REGISTRAR
	===========================================*/

	$('#FormRegistrarGrupo').validate({
		ignore: [],
		rules: {

			descripcion: { required: true },
			estado: { required: true },
		},
		submit: function (form) {
			form.submit();
		}
	});

	/*==========================================
			 GRUPO USUARIO - EDITAR
	===========================================*/

	$('#TableMantenimientoGrupo').on('click', '.editar-grupo', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regrupo/getGrupo', { id }, function (json, textStatus) {
			$('#FormEditarGrupo input[name=id]').val(json.cod_grupo);
			$('#FormEditarGrupo input[name=descripcion]').val(json.nombre_grupo);
		});
	});

	$('#FormEditarGrupo').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarGrupo', function (json) {
				if (json.success) {
					$('#TableMantenimientoGrupo').DataTable().ajax.reload();
				}
				$('#ModalEditarGrupo').modal('hide');
				$('#FormEditarGrupo input[name=descripcion]').val('');

			})
		}
	});


	/*==========================================
			 GRUPO USUARIO - ANULAR
	===========================================*/

	$('#TableMantenimientoGrupo').on('click', '.anular-grupo', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular grupo?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regrupo/anularGrupo', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoGrupo').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});

	/*========================================
	=            PERFIL USUARIO - LISTADO       =
	===========================================*/

	var TableMantenimientoPerfil = $('#TableMantenimientoPerfil').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regperfil/jsonPerfil',
			"type": "GET",
			"data": function (d) {
				d.tb_perfil = $("input[name=tb_perfil]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#PerfilFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoPerfil').DataTable().ajax.reload();
		}
	});

	/*==========================================
			 GRUPO USUARIO - VALIDAR Y REGISTRAR
	===========================================*/

	$('#FormRegistrarPerfil').validate({
		ignore: [],
		rules: {

			descripcion: { required: true },
			estado: { required: true },
		},
		submit: function (form) {
			form.submit();
		}
	});



	/*==========================================
			 PERFIL USUARIO - EDITAR
	===========================================*/

	$('#TableMantenimientoPerfil').on('click', '.editar-perfil', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regperfil/getPerfil', { id }, function (json, textStatus) {
			$('#FormEditarPerfil input[name=id]').val(json.cod_perfil);
			$('#FormEditarPerfil input[name=descripcion]').val(json.nomb_perfil);
		});
	});

	$('#FormEditarPerfil').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarPerfil', function (json) {
				if (json.success) {
					$('#TableMantenimientoPerfil').DataTable().ajax.reload();
				}
				$('#ModalEditarPerfil').modal('hide');
				$('#FormEditarPerfil input[name=descripcion]').val('');

			})
		}
	});


	/*==========================================
			 PERFIL USUARIO - ANULAR
	===========================================*/

	$('#TableMantenimientoPerfil').on('click', '.anular-perfil', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular grupo?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regperfil/anularPerfil', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoPerfil').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*===========================================
	=            USUARIO - LISTADO            =
	===========================================*/
	var TableMantenimientoUsuario = $('#TableMantenimientoUsuario').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regusuario/jsonUsuario',
			"type": "GET",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_usuario = $("input[name=tb_usuario]").val();
				d.tb_grupo = $("select[name=tb_grupo]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },

		]
	});

	$('#UsuarioFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoUsuario').DataTable().ajax.reload();
		}
	});





	$('#TableMantenimientoUsuario tbody').on('click', '.asignar-cajadocumento', function (event) {
		event.preventDefault();

		var id = $(this).data('id');
		var punto = $(this).data('punto');
		var usuario = $(this).parent().parent().parent().find('td').eq(1).text();
		$('#ButtonAgregarCajaDocumento').show();
		$('#FormAgregarCajaDocumento').hide();
		$('#FormAgregarCajaDocumento input[name=usuario]').val(id);
		$('#FormAgregarCajaDocumento input[name=punto]').val(punto);
		$('#NombreDeUsuario').text(usuario);
		pintarAsignacionCajaDocumento(id);
	});


	function pintarAsignacionCajaDocumento(id) {
		$('#TableAsignarCajaPuntoVenta tbody').empty();
		$.getJSON(path + 'administrador/regusuario/getCajaDocumento', { id }, function (json, textStatus) {
			var body = '';
			$.each(json, function (index, val) {
				body += `
				<tr>
					<td>${val.nomb_caja}</td>
					<td>${val.nom_tipdocumento}</td>
					<td>${val.serie_usudoc}</td>
					<td>${val.type_formt}</td>
					<td>
						<button data-formato="${val.type_formt}" data-serie="${val.serie_usudoc}" data-id="${val.cod_usudoc}" class="editar btn btn-warning btn-icon pm-button"><i class="fas fa-pencil-alt"></i></button>
						<button data-id="${val.cod_usudoc}" class="quitar btn btn-danger btn-icon pm-button"><i class="fa fa-trash"></i></button>
					</td>			
				</tr>
			`;
			});
			$('#TableAsignarCajaDocumento tbody').html(body);
		});
	}

	$('#ButtonAgregarCajaDocumento').click(function (event) {
		$(this).hide();
		$('#FormAgregarCajaDocumento').show();
		var usuario = $('#FormAgregarCajaDocumento input[name=usuario]').val();
		var punto = $('#FormAgregarCajaDocumento input[name=punto]').val();
		$.getJSON(path + 'administrador/regusuario/getCajas', { usuario, punto }, function (json, textStatus) {
			var option = '<option value="">Seleccione</option>';
			$.each(json, function (index, val) {
				option += `
				<option value="${val.cod_caja}">${val.nomb_caja}</option>
			`;
			});
			$('#FormAgregarCajaDocumento select[name=caja]').html(option);
		});
	});

	$('#FormAgregarCajaDocumento select[name=caja]').change(function (event) {
		var caja = $(this).val();
		var usuario = $('#FormAgregarCajaDocumento input[name=usuario]').val();
		var punto = $('#FormAgregarCajaDocumento input[name=punto]').val();
		$.getJSON(path + 'administrador/regusuario/getTalonarios', { caja, usuario, punto }, function (json, textStatus) {
			var option = '<option></option>';
			$.each(json, function (index, val) {
				option += `
				<option data-serie="${val.serie}" value="${val.cod_tipdocu}">${val.nom_tipdocumento}</option>
			`;
			});
			$('#FormAgregarCajaDocumento select[name=documento]').html(option);
		});
	});

	$('#FormAgregarCajaDocumento select[name=documento]').change(function (event) {
		var serie = $(this).find(':selected').data('serie');
		$('#FormAgregarCajaDocumento input[name=serie]').val(serie);
	});

	$('#FormAgregarCajaDocumento').validate({
		ignore: [],
		rules: {
			caja: { required: true },
			documento: { required: true },
			serie: { required: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormAgregarCajaDocumento', () => {
				$('#ButtonAgregarCajaDocumento').trigger('click');
				$('#FormAgregarCajaDocumento select[name=documento]').empty();
				pintarAsignacionCajaDocumento($('#FormAgregarCajaDocumento input[name=usuario]').val());
			})
		}
	});

	// $('#TableAsignarCajaDocumento tbody').on('click', '.editar', function (event) {
	// 	event.preventDefault();
	// 	var serie = $(this).data('serie');
	// 	var id = $(this).data('id');
	// 	var input = `<input  name="serie" data-id="${id}" class="form-control cambiarSerie" value="${serie}" autocomplete="off" />`;
	// 	$(this).parent().parent().find('td').eq(2).html(input);
	// 	$(this).parent().parent().find('input').focus();
	// });
		$('#TableAsignarCajaDocumento tbody').on('click', '.editar', function (event) {
		event.preventDefault();
		var serie = $(this).data('serie');
		var id = $(this).data('id');
		var formato = $(this).data('formato');
		var input = `<input  name="serie" data-id="${id}" data-serie="${serie}" class="form-control cambiarSerie" value="${serie}" autocomplete="off" />`;
		var select = `<input name="formato" data-id="${id}" data-formato="${formato}" class="form-control cambiarSerie" value="${formato}" autocomplete="off" />`;
		$(this).parent().parent().find('td').eq(2).html(input);
		$(this).parent().parent().find('td').eq(3).html(select);
		//$(this).parent().parent().find('input[name="serie"]').focus();
	});


	// $('#TableAsignarCajaDocumento tbody').on('focusout', '.cambiarSerie', function (event) {
	// 	event.preventDefault();
	// 	var serie = $(this).val();
	// 	var id = $(this).data('id');
	// 	$.getJSON(path + 'administrador/regusuario/updateSerie', { id, serie }, function (json, textStatus) {
	// 		if (json.success) {
	// 			pintarAsignacionCajaDocumento($('#FormAgregarCajaDocumento input[name=usuario]').val());
	// 		} else {
	// 			Swal.fire({
	// 				title: "Error",
	// 				text: "Ocurrio un error, vuelva a intentarlo.",
	// 				type: "error"
	// 			});
	// 		}
	// 	});
	// });

		$('#TableAsignarCajaDocumento tbody').on('focusout', '.cambiarSerie', function (event) {
		event.preventDefault();
		var data = $(this).val();
		var id = $(this).data('id');
		var formato = $(this).data('formato');
		var serie = $(this).data('serie');

		(formato !== undefined) ? formato = data : serie = data;

		$.getJSON(path + 'administrador/regusuario/updateSerie', { id, serie, formato }, function (json, textStatus) {
			if (json.success) {
				pintarAsignacionCajaDocumento($('#FormAgregarCajaDocumento input[name=usuario]').val());
			} else {
				Swal.fire({
					title: "Error",
					text: "Ocurrio un error, vuelva a intentarlo.",
					type: "error"
				});
			}
		});
	});

	$('#TableAsignarCajaDocumento tbody').on('click', '.quitar', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Confirma que desea quitar este registro?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regusuario/quitarCajaDocumento', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se quito correctamente.",
							type: "success"
						});
						pintarAsignacionCajaDocumento($('#FormAgregarCajaDocumento input[name=usuario]').val());
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});




	/*==========================================
				USUARIO - AGREGAR
	===========================================*/


	$('#FormUsuario').validate({
		rules: {
			apellido: { required: true },
			nombre: { required: true },
			direccion: { required: true },
			telefono: { required: true },
			documento: { required: true },
			email: { required: true },
			login: { required: true },
			passwoord: { required: true },
			fecharegistro: { required: true },
			grupo: { required: true },
			perfil: { required: true },

		},
		submitHandler: function () {
			$('#ModalAgregarUsuario').modal('hide');
			enviarFormulario('#FormUsuario', function (json) {
				if (json.success) {
					$('#TableMantenimientoUsuario').DataTable().ajax.reload();
					$('#FormUsuario input[name=apellido]').val('');
					$('#FormUsuario input[name=nombre]').val('');					
					$('#FormUsuario input[name=direccion]').val('');
					$('#FormUsuario input[name=telefono]').val('');
					$('#FormUsuario input[name=documento]').val('');
					$('#FormUsuario input[name=email]').val('');
					$('#FormUsuario input[name=login]').val('');
					$('#FormUsuario input[name=passwoord]').val('');
					$('#FormUsuario select[name=grupo]').select2('val', '');
					$('#FormUsuario select[name=perfil]').select2('val', '');
					$('#FormUsuario select[name=estado]').val('');
					$('#FormUsuario input[name=fnacimiento]').val('');



				}


			})
		}
	});



	/*==========================================
				USUARIO - EDITAR
	===========================================*/

	$('#TableMantenimientoUsuario').on('click', '.editar-usuario', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regusuario/getUsuario', { id }, function (json, textStatus) {
			$('#FormEditarUsuario input[name=id]').val(json.cod_usu);
			$('#FormEditarUsuario input[name=apellido]').val(json.apell_usu);
			$('#FormEditarUsuario input[name=nombre]').val(json.nomb_usu);
			$('#FormEditarUsuario input[name=fnacimiento]').val(json.fena_usu);
			$('#FormEditarUsuario input[name=direccion]').val(json.direcc_usu);
			$('#FormEditarUsuario input[name=telefono]').val(json.telf_usu);
			$('#FormEditarUsuario input[name=documento]').val(json.docum_usu);
			$('#FormEditarUsuario input[name=email]').val(json.email_usu);
			$('#FormEditarUsuario input[name=login]').val(json.login_usu);
			// $('#FormEditarUsuario input[name=passwoord]').val(json.passwoord_usu);
			$('#FormEditarUsuario input[name=fecharegistro]').val(json.fecha_registro);			
			$('#FormEditarUsuario select[name=grupo]').val(json.cod_grupo);
			$('#FormEditarUsuario select[name=perfil]').val(json.cod_perfil);
			$('#FormEditarUsuario select[name=estado]').val(json.estado_usuario);
		});
	});

	$('#cambiarPassword').click(function (event) {

		if ($(this).is(":checked")) {
			$('#FormEditarUsuario input[name=passwoord]').prop('disabled', false);
		} else {
			$('#FormEditarUsuario input[name=passwoord]').prop('disabled', true);
		}
	});

	$('#FormEditarUsuario').validate({
		ignore: [],
		rules: {
			apellido: { required: true },
			nombre: { required: true },
			direccion: { required: true },
			telefono: { required: true },
			documento: { required: true },
			email: { required: true },
			login: { required: true },
			passwoord: { required: true },
			fecharegistro: { required: true },
			grupo: { required: true },
			perfil: { required: true },
			estado: { required: true }

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarUsuario', function (json) {
				if (json.success) {
					$('#TableMantenimientoUsuario').DataTable().ajax.reload();
				}
				$('#ModalEditarUsuario').modal('hide');
				$('#FormEditarUsuario input[name=apellido]').val('');
				$('#FormEditarUsuario input[name=nombre]').val('');
				$('#FormEditarUsuario input[name=direccion]').val('');
				$('#FormEditarUsuario input[name=telefono]').val('');
				$('#FormEditarUsuario input[name=documento]').val('');
				$('#FormEditarUsuario input[name=email]').val('');
				$('#FormEditarUsuario input[name=login]').val('');
				$('#FormEditarUsuario input[name=passwoord]').val('');
				$('#FormEditarUsuario input[name=fecharegistro]').datepicker('setDate', null);				
				$('#FormEditarUsuario select[name=grupo]').select('val', '');
				$('#FormEditarUsuario select[name=perfil]').select('val', '');
				$('#FormEditarUsuario select[name=estado]').select('val', '');
			})
		}
	});

	/*==========================================
			 USUARIO - ANULAR
	===========================================*/

	$('#TableMantenimientoUsuario').on('click', '.anular-usuario', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular usuario?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regusuario/anularUsuario', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoUsuario').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});



	/*========================================
	=            ALMACEN - LISTADO       =
	===========================================*/

	var TableMantenimientoAlmacen = $('#TableMantenimientoAlmacen').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regalmacen/jsonAlmacen',
			"type": "GET",
			"data": function (d) {
				d.tb_almacen = $("input[name=tb_almacen]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#AlmacenFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoAlmacen').DataTable().ajax.reload();
		}
	});


	/*==========================================
			 EDITAR ALMACEN - EDITAR
	===========================================*/

	$('#TableMantenimientoAlmacen').on('click', '.editar-almacen', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regalmacen/getAlmacen', { id }, function (json, textStatus) {
			$('#FormEditarAlmacen input[name=id]').val(json.cod_almacen);
			$('#FormEditarAlmacen input[name=descripcion]').val(json.nomb_almacen);
			$('#FormEditarAlmacen select[name=estadoventa]').val(json.disp_venta);
		});
	});

	$('#FormEditarAlmacen').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },
			estadoventa: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarAlmacen', function (json) {
				if (json.success) {
					$('#TableMantenimientoAlmacen').DataTable().ajax.reload();
				}
				$('#ModalEditarAlmacen').modal('hide');
				$('#FormEditarAlmacen input[name=descripcion]').val('');
				$('#FormEditarAlmacen select[name=estadoventa]').select('val', '');

			})
		}
	});

	/*===================================*/


	/*==========================================
				ALMACEN - AGREGAR
	===========================================*/


	$('#FormAlmacen').validate({
		rules: {
			descripcion: { required: true },
			estadoventa: { required: true },

		},
		submitHandler: function () {
			$('#ModalAgregarAlmacen').modal('hide');
			enviarFormulario('#FormAlmacen', function (json) {
				if (json.success) {
					$('#TableMantenimientoAlmacen').DataTable().ajax.reload();
					$('#FormAlmacen input[name=descripcion]').val('');
					$('#FormAlmacen select[name=estadoventa]').select2('val', '');




				}


			})
		}
	});


	/*==========================================
			 ALMACEN - ANULAR
	===========================================*/

	$('#TableMantenimientoAlmacen').on('click', '.anular-almacen', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular almacen?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regalmacen/anularAlmacen', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoAlmacen').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*========================================
	=            TIPO ALMACEN - LISTADO       =
	===========================================*/

	var TableMantenimientoTipAlmacen = $('#TableMantenimientoTipAlmacen').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regtipalmacen/jsonTipAlmacen',
			"type": "GET",
			"data": function (d) {
				d.tb_tipoalmacen = $("input[name=tb_tipoalmacen]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#TipAlmacenFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoTipAlmacen').DataTable().ajax.reload();
		}
	});


	/*==========================================
				TIPO ALMACEN - AGREGAR
	===========================================*/


	$('#FormTipoAlmacen').validate({
		rules: {
			descripcion: { required: true },
			tipo: { required: true },

		},
		submitHandler: function () {
			$('#ModalAgregarTipoAlmacen').modal('hide');
			enviarFormulario('#FormTipoAlmacen', function (json) {
				if (json.success) {
					$('#TableMantenimientoTipAlmacen').DataTable().ajax.reload();
					$('#FormTipoAlmacen input[name=descripcion]').val('');
					$('#FormTipoAlmacen select[name=estadoventa]').select2('val', '');




				}


			})
		}
	});


	/*==========================================
			 TIPO NOTA ALMACEN - EDITAR
	===========================================*/

	$('#TableMantenimientoTipAlmacen').on('click', '.editar-tipo', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regtipalmacen/getTipAlmacen', { id }, function (json, textStatus) {
			$('#FormEditarTipo input[name=id]').val(json.cod_tipoalm);
			$('#FormEditarTipo input[name=descripcion]').val(json.nomb_tipoalm);
			$('#FormEditarTipo select[name=tipo]').val(json.tip_tipoalm);
		});
	});

	$('#FormEditarTipo').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarTipo', function (json) {
				if (json.success) {
					$('#TableMantenimientoTipAlmacen').DataTable().ajax.reload();
				}
				$('#ModalEditarTipAlmacen').modal('hide');
				$('#FormEditarTipo input[name=descripcion]').val('');
				$('#FormEditarTipo select[name=tipo]').select('val', '');
			})
		}
	});



	/*==========================================
			 TIPO ALMACEN - ANULAR
	===========================================*/

	$('#TableMantenimientoTipAlmacen').on('click', '.anular-tipalmacen', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular tipo almacen?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regtipalmacen/anularTipAlmacen', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoTipAlmacen').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*========================================
	=            CAJA - LISTADO       =
	===========================================*/

	var TableMantenimientoCaja = $('#TableMantenimientoCaja').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcaja/jsonCaja',
			"type": "GET",
			"data": function (d) {
				d.tb_caja = $("input[name=tb_caja]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#CajaFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoCaja').DataTable().ajax.reload();
		}
	});



	/*==========================================
				CAJA - AGREGAR
	===========================================*/


	$('#FormCaja').validate({
		rules: {
			descripcion: { required: true },
			tipo: { required: true },

		},
		submitHandler: function () {
			$('#ModalAgregarCaja').modal('hide');
			enviarFormulario('#FormCaja', function (json) {
				if (json.success) {
					$('#TableMantenimientoCaja').DataTable().ajax.reload();
					$('#FormCaja input[name=descripcion]').val('');
					$('#FormCaja select[name=estadoventa]').select2('val', '');




				}


			})
		}
	});


	/*==========================================
			 CAJA - EDITAR
	===========================================*/

	$('#TableMantenimientoCaja').on('click', '.editar-caja', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regcaja/getCaja', { id }, function (json, textStatus) {
			$('#FormEditarCaja input[name=id]').val(json.cod_caja);
			$('#FormEditarCaja input[name=descripcion]').val(json.nomb_caja);
			$('#FormEditarCaja select[name=tipo]').val(json.tipo_caja);
		});
	});

	$('#FormEditarCaja').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarCaja', function (json) {
				if (json.success) {
					$('#TableMantenimientoCaja').DataTable().ajax.reload();
				}
				$('#ModalEditarCaja').modal('hide');
				$('#FormEditarCaja input[name=descripcion]').val('');
				$('#FormEditarCaja select[name=tipo]').select('val', '');
			})
		}
	});

	/*==========================================
			 CAJA - ANULAR
	===========================================*/

	$('#TableMantenimientoCaja').on('click', '.anular-caja', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular caja?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regcaja/anularCaja', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoCaja').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});



	/*========================================
	=            MONEDA - LISTADO       =
	===========================================*/

	var TableMantenimientoMoneda = $('#TableMantenimientoMoneda').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regmoneda/jsonMoneda',
			"type": "GET",
			"data": function (d) {
				d.tipo_moneda = $("input[name=tipo_moneda]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#MonedaFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoMoneda').DataTable().ajax.reload();
		}
	});



	/*==========================================
				MONEDA - AGREGAR
	===========================================*/


	$('#FormMoneda').validate({
		rules: {
			simbolo: { required: true },
			descripcion: { required: true },
			tipo: { required: true },

		},
		submitHandler: function () {
			$('#ModalAgregarMoneda').modal('hide');
			enviarFormulario('#FormMoneda', function (json) {
				if (json.success) {
					$('#TableMantenimientoMoneda').DataTable().ajax.reload();
					$('#FormCaja input[name=simbolo]').val('');
					$('#FormCaja input[name=descripcion]').val('');
					$('#FormCaja select[name=tipo]').select2('val', '');




				}


			})
		}
	});


	/*==========================================
				MONEDA - EDITAR
	===========================================*/

	$('#TableMantenimientoMoneda').on('click', '.editar-moneda', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regmoneda/getMon', { id }, function (json, textStatus) {
			$('#FormEditarMoneda input[name=id]').val(json.id_moneda);
			$('#FormEditarMoneda input[name=simbolo]').val(json.mon_simbolo);
			$('#FormEditarMoneda input[name=descripcion]').val(json.mon_moneda);
			$('#FormEditarMoneda select[name=tipo]').val(json.tipo_moneda);
		});
	});

	$('#FormEditarMoneda').validate({
		ignore: [],
		rules: {
			simbolo: { required: true },
			descripcion: { required: true },
			tipo: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarMoneda', function (json) {
				if (json.success) {
					$('#TableMantenimientoMoneda').DataTable().ajax.reload();
				}
				$('#ModalEditarMoneda').modal('hide');
				$('#FormEditarMoneda input[name=simbolo]').val('');
				$('#FormEditarMoneda input[name=descripcion]').val('');
				$('#FormEditarMoneda select[name=tipo]').select('val', '');
			})
		}
	});

	/*==========================================
			 MONEDA - ANULAR
	===========================================*/

	$('#TableMantenimientoMoneda').on('click', '.anular-moneda', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Moneda?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regmoneda/anularmoneda', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoMoneda').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});



	/*========================================
	=            TIPO CUENTA - LISTADO       =
	===========================================*/

	var TableMantenimientoTipcuenta = $('#TableMantenimientoTipcuenta').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regtipcuenta/jsonTipcuenta',
			"type": "GET",
			"data": function (d) {
				d.tipo_cuenta = $("input[name=tipo_cuenta]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#TipocuentaFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoTipcuenta').DataTable().ajax.reload();
		}
	});



	/*==========================================
				TIPO CUENTA - AGREGAR
	===========================================*/


	$('#FormTipoCuenta').validate({
		rules: {
			simbolo: { required: true },
			descripcion: { required: true },
			orden: { required: true },

		},
		submitHandler: function () {
			$('#ModalAgregarTipoCuenta').modal('hide');
			enviarFormulario('#FormTipoCuenta', function (json) {
				if (json.success) {
					$('#TableMantenimientoTipcuenta').DataTable().ajax.reload();
					$('#FormTipoCuenta input[name=simbolo]').val('');
					$('#FormTipoCuenta input[name=descripcion]').val('');
					$('#FormTipoCuenta input[name=orden]').val('');




				}


			})
		}
	});


	/*==========================================
				TIPO CUENTA - EDITAR
	===========================================*/

	$('#TableMantenimientoTipcuenta').on('click', '.editar-tipocuenta', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regtipcuenta/getipcuenta', { id }, function (json, textStatus) {
			$('#FormEditarTipocuenta input[name=id]').val(json.id_tipcuenta);
			$('#FormEditarTipocuenta input[name=simbolo]').val(json.abrev_tipcuenta);
			$('#FormEditarTipocuenta input[name=descripcion]').val(json.nomb_tipcuenta);
			$('#FormEditarTipocuenta input[name=orden]').val(json.orden_tipcuenta);
		});
	});

	$('#FormEditarTipocuenta').validate({
		ignore: [],
		rules: {
			simbolo: { required: true },
			descripcion: { required: true },
			orden: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarTipocuenta', function (json) {
				if (json.success) {
					$('#TableMantenimientoTipcuenta').DataTable().ajax.reload();
				}
				$('#ModalEditarTipoCuenta').modal('hide');
				$('#FormEditarTipocuenta input[name=simbolo]').val('');
				$('#FormEditarTipocuenta input[name=descripcion]').val('');
				$('#FormEditarTipocuenta input[name=orden]').val('');
			})
		}
	});

	/*==========================================
			 TIPO CUENTA - ANULAR
	===========================================*/

	$('#TableMantenimientoTipcuenta').on('click', '.anular-tipocuenta', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Tipo Cuenta?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regtipcuenta/anularCuenta', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoTipcuenta').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});



	/*==============================================
	=            CUENTA LISTADO                    =
	===============================================*/
	var TableCuentListado = $('#TableCuentListado').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcuenta/jsonCuentaasignar',
			"type": "POST",
			"data": function (d) {
				d.tipo_cuenta = $("select[name=tipo_cuenta]").val();
				d.tb_cuenta = $("input[name=tb_cuenta]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },

			{ "orderable": false }
		]
	});

	$('#FormCuentaAsignar select[name=tipo_cuenta]').change(function (event) {
		$('#TableCuentListado').DataTable().ajax.reload();
	});

	$('#FormCuentaAsignar').validate({
		submitHandler: function () {
			$('#TableCuentListado').DataTable().ajax.reload();
		}
	});

	// $('#FormAgregarPuntoVentaUsuario').validate({
	// 	ignore: [],
	// 	rules:{
	// 		usuario:{required:true},
	// 		punto:{required:true}
	// 	},
	// 	submitHandler:function() {
	// 		enviarFormulario('#FormAgregarPuntoVentaUsuario',()=>{
	// 			$('#ButtonAgregarPuntoVenta').trigger('click');
	// 			pintarPuntoVenta($('#FormAgregarPuntoVentaUsuario input[name=usuario]').val());
	// 			$('#TableCuentaListado').DataTable().ajax.reload();
	// 		})
	// 	}
	// });

	/*==========================================
				CUENTA - AGREGAR
	===========================================*/


	$('#FormAgregarcuentas').validate({
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			orden: { required: true },

		},
		submitHandler: function () {
			$('#ModalAsignarCuenta').modal('hide');
			enviarFormulario('#FormAgregarcuentas', function (json) {
				if (json.success) {
					$('#TableCuentListado').DataTable().ajax.reload();
					$('#FormAgregarcuentas select[name=tipo]').select2('val', '');
					$('#FormAgregarcuentas input[name=nombre]').val('');
					$('#FormAgregarcuentas input[name=orden]').val('');




				}


			})
		}
	});



	/*==========================================
			 CUENTA - EDITAR
	===========================================*/

	$('#TableCuentListado').on('click', '.editar-cuenta', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regcuenta/getCuentas', { id }, function (json, textStatus) {
			$('#FormEditarCuenta input[name=id]').val(json.id_cuenta);
			$('#FormEditarCuenta select[name=tipo]').val(json.id_tipcuenta);
			$('#FormEditarCuenta input[name=nombre]').val(json.nomb_cuenta);
			$('#FormEditarCuenta input[name=orden]').val(json.orden_cuenta);

		});
	});

	$('#FormEditarCuenta').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			orden: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarCuenta', function (json) {
				if (json.success) {
					$('#TableCuentListado').DataTable().ajax.reload();
				}
				$('#ModalEditarCuenta').modal('hide');
				$('#FormEditarCuenta select[name=tipo]').select('val', '');
				$('#FormEditarCuenta input[name=nombre]').val('');
				$('#FormEditarCuenta input[name=orden]').val('val');

			})
		}
	});

	/*==========================================
			 CUENTA - ANULAR
	===========================================*/

	$('#TableCuentListado').on('click', '.anular-cuenta', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Cuenta?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regcuenta/anularCuenta', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableCuentListado').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});






	/*==============================================
	=            BANCO LISTADO                    =
	===============================================*/
	var TableBancoListado = $('#TableBancoListado').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regbanco/jsonbanco',
			"type": "POST",
			"data": function (d) {
				d.tb_banco = $("input[name=tb_banco]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false }
		]
	});


	$('#FormBanco').validate({
		submitHandler: function () {
			$('#TableBancoListado').DataTable().ajax.reload();
		}
	});


	/*==========================================
				BANCO - AGREGAR
	===========================================*/


	$('#FormAgregarBanco').validate({
		rules: {

			nombre: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarBanco').modal('hide');
			enviarFormulario('#FormAgregarBanco', function (json) {
				if (json.success) {
					$('#TableBancoListado').DataTable().ajax.reload();
					$('#FormAgregarBanco input[name=nombre]').val('');

				}


			})
		}
	});


	/*==========================================
			 BANCO - EDITAR
	===========================================*/

	$('#TableBancoListado').on('click', '.editar-banco', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regbanco/getBan', { id }, function (json, textStatus) {
			$('#FormEditarBanco input[name=id]').val(json.cod_ban);
			$('#FormEditarBanco input[name=nombre]').val(json.nomb_ban);

		});
	});

	$('#FormEditarBanco').validate({
		ignore: [],
		rules: {

			nombre: { required: true },


		},
		submitHandler: function () {
			enviarFormulario('#FormEditarBanco', function (json) {
				if (json.success) {
					$('#TableBancoListado').DataTable().ajax.reload();
				}
				$('#ModalEditarBanco').modal('hide');
				$('#FormEditarBanco input[name=nombre]').val('');

			})
		}
	});


	/*==========================================
			 BANCO - ANULAR
	===========================================*/

	$('#TableBancoListado').on('click', '.anular-banco', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Banco?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regbanco/anularBanco', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableBancoListado').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});





	/*==============================================
	=            TARJETA LISTADO                    =
	===============================================*/
	var TableTarjetaListado = $('#TableTarjetaListado').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regtarjeta/jsontarjeta',
			"type": "POST",
			"data": function (d) {
				d.tb_tarjeta = $("input[name=tb_tarjeta]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false }
		]
	});


	$('#FormTarjeta').validate({
		submitHandler: function () {
			$('#TableTarjetaListado').DataTable().ajax.reload();
		}
	});



	/*==========================================
				TARJETA - AGREGAR
	===========================================*/


	$('#FormAgregarTarjeta').validate({
		rules: {

			nombre: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarTarjeta').modal('hide');
			enviarFormulario('#FormAgregarTarjeta', function (json) {
				if (json.success) {
					$('#TableTarjetaListado').DataTable().ajax.reload();
					$('#FormAgregarTarjeta input[name=nombre]').val('');

				}


			})
		}
	});


	/*==========================================
			 TARJETA - EDITAR
	===========================================*/

	$('#TableTarjetaListado').on('click', '.editar-tarjeta', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regtarjeta/getarj', { id }, function (json, textStatus) {
			$('#FormEditarTarjeta input[name=id]').val(json.cod_tarj);
			$('#FormEditarTarjeta input[name=nombre]').val(json.nomb_tarj);

		});
	});

	$('#FormEditarTarjeta').validate({
		ignore: [],
		rules: {

			nombre: { required: true },


		},
		submitHandler: function () {
			enviarFormulario('#FormEditarTarjeta', function (json) {
				if (json.success) {
					$('#TableTarjetaListado').DataTable().ajax.reload();
				}
				$('#ModalEditarTarjeta').modal('hide');
				$('#FormEditarTarjeta input[name=nombre]').val('');

			})
		}
	});



	/*==========================================
			 TARJETA - ANULAR
	===========================================*/

	$('#TableTarjetaListado').on('click', '.anular-tarjeta', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Tarjeta?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regtarjeta/anularTarjeta', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableTarjetaListado').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*=====================================
	=            CAJA APERTURA            =
	=====================================*/
	var TableApertura = $('#TableApertura').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcajaapertura/jsonApertura',
			"type": "GET",
			"data": function (d) {
				d.caja = $("#FormAperturaFiltro input[name=caja]").val();
				d.usuario = $("#FormAperturaFiltro input[name=usuario]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false }
		]
	});


	$('#FormAperturaFiltro').validate({
		rules: {
		},
		submitHandler: function () {
			$('#TableApertura').DataTable().ajax.reload();
		}
	});

	$('#AgregarApertura').click(function (event) {		
		$('input[name=contrasena]').val('');
		$('#ModalAgregarConfirmar').modal();
		$('#FormApertura').find('button:submit').prop('disabled', false).html('Guardar');
	});

	$('#FormConfirmarAgregar').validate({
		rules: {
			contrasena: { required: true }
		},
		submitHandler: function () {
			var contrasena = $('input[name=contrasena]').val();
			$.post(path+"administrador/regcajaapertura/verificaContrasena", {contrasena},
				function (data, textStatus, jqXHR) {
					if(data['success'] == true){
						$('#ModalAgregarApertura').modal();
						$('#ModalAgregarConfirmar').modal('hide');
					}else{
						$('#ModalAgregarConfirmar').modal('hide');
						Swal.fire({
							title: "Error",
							text: "La contraseña es incorrecta.",
							type: "error"
						});
					}
				},
				"JSON"
			);
		}
	});

	function verificaDisponibilidadApertura(callback) {
		var form = $('#FormApertura').serializeObject();
		$.getJSON(path + 'administrador/regcajaapertura/verificarDisponibilidadApertura', form, function (json, textStatus) {
			callback(json);
		});
	}

	function verificaCierreCaja(callback) {
		var form = $('#FormApertura').serializeObject();
		$.getJSON(path + 'administrador/regcajaapertura/verificarCierreCaja', form, function (json, textStatus) {
			callback(json);
		});
	}

	$('#FormApertura').validate({
		rules: {
			caja: { required: true },
			usuario: { required: true },
			monto: { required: true },
			fecha: { required: true },
			inicio: { required: true },
			fin: { required: true },
		},
		submitHandler: function () {
			var btnGuardar = $('#FormApertura').find('button:submit');
			btnGuardar.prop('disabled', true).html('<i class="fa fa-refresh fa-spin"></i> Procesando');
			verificaDisponibilidadApertura(function (resp) {
				if (resp.success == false) {
					Swal.fire({
						title: "Error",
						text: resp.mensaje,
						type: "error"
					});
					btnGuardar.prop('disabled', false).html('Guardar');
					return;
				}

				verificaCierreCaja(function (respCierreCaja) {
					if (respCierreCaja.success == false) {
						Swal.fire({
							title: "Error",
							text: respCierreCaja.mensaje,
							type: "error"
						});
						btnGuardar.prop('disabled', false).html('Guardar');
						return;
					}
					enviarFormulario('#FormApertura', function (json) {
						btnGuardar.prop('disabled', false).html('Guardar');
						$('#ModalAgregarApertura').modal('hide');
						$('#TableApertura').DataTable().ajax.reload();
					})
				});
			});
		}
	});

	$('#FormApertura select[name=caja]').change(function (event) {
		var caja = $(this).val();
		$.getJSON(path + 'administrador/regcajaapertura/getUsuarios', { caja }, function (json, textStatus) {
			var option = '<option></option>';
			$.each(json, function (index, val) {
				option += `
					<option value="${val.cod_usu}">${val.apell_usu + ' ' + val.nomb_usu}</option>
			`;
			});
			$('#FormApertura select[name=usuario]').html(option);
		});
	});

	$('#FormAperturaEditar select[name=caja]').change(function (event) {
		var caja = $(this).val();
		$.getJSON(path + 'administrador/regcajaapertura/getUsuarios', { caja }, function (json, textStatus) {
			var option = '<option></option>';
			$.each(json, function (index, val) {
				option += `
					<option value="${val.cod_usu}">${val.apell_usu + ' ' + val.nomb_usu}</option>
			`;
			});
			$('#FormAperturaEditar select[name=usuario]').html(option);
		});
	});


	$('#TableApertura tbody').on('click', '.editarApertura ', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$('#ModalEditarApertura').modal();
		$.getJSON(path + 'administrador/regcajaapertura/getApertura', { id }, function (json, textStatus) {
			$('#FormAperturaEditar input[name=id]').val(json.apertura.cod_apertura);
			$('#FormAperturaEditar select[name=caja]').val(json.apertura.cod_caja);
			$('#FormAperturaEditar select[name=usuario]').html(json.usuarios);
			$('#FormAperturaEditar input[name=monto]').val(json.apertura.monto_apertura);
			$('#FormAperturaEditar input[name=fecha]').val(json.apertura.fecha_apertura);
			$('#FormAperturaEditar input[name=inicio]').val(json.apertura.horainicio_apertura);
			$('#FormAperturaEditar input[name=fin]').val(json.apertura.horafin_apertura);
			$('#FormAperturaEditar select[name=turno]').val(json.apertura.turno_apertura);
		});
	});


	$('#FormAperturaEditar').validate({
		rules: {
			caja: { required: true },
			usuario: { required: true },
			monto: { required: true },
			fecha: { required: true },
			inicio: { required: true },
			fin: { required: true },
		},
		submitHandler: function () {
			enviarFormulario('#FormAperturaEditar', function (json) {
				$('#ModalEditarApertura').modal('hide');
				$('#TableApertura').DataTable().ajax.reload();
			})
		}
	});

	$('#TableApertura').on('click', '.eliminar', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Eliminar registro?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regcajaapertura/eliminar', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableApertura').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});
	/*=====  End of CAJA APERTURA  ======*/


	/*===================================
	=            CAJA CIERRE            =
	===================================*/
	var TableCierre = $('#TableCierre').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcajacierre/jsonCierre',
			"type": "GET",
			"data": function (d) {
				d.caja = $("#FormCierreFiltro input[name=caja]").val();
				d.usuario = $("#FormCierreFiltro input[name=usuario]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false }
		]
	});

	$('#FormCierreFiltro').validate({
		rules: {
		},
		submitHandler: function () {
			$('#TableCierre').DataTable().ajax.reload();
		}
	});

	$('#FormCierre select[name=caja]').change(function (event) {
		var caja = $(this).val();
		$('input[name=totalCierre], input[name=credito],input[name=tarjeta],input[name=efectivo]').val('');
		$.getJSON(path + 'administrador/regcajacierre/getAperturas', { caja }, function (json, textStatus) {
			var option = '<option value="">Seleccione</option>';
			$.each(json, function (index, val) {
				option += `
				<option value="${val.cod_apertura}">${val.fecha_apertura + ' / ' + val.turno_apertura + ' / ' + val.horainicio_apertura + ' - ' + val.horafin_apertura}</option>
			`;
			});
			$('#FormCierre select[name=apertura]').html(option);
		});
	});

	function datosCierreCaja() {
		$('input[name=totalCierre], input[name=credito],input[name=tarjeta],input[name=efectivo]').val('');
		var apertura = $('select[name=apertura]').val();
		$.getJSON(path + 'administrador/regcajacierre/datosCajaApertura', { apertura }, function (json, textStatus) {
			if (json != null) {
				$('input[name=totalCierre]').val(parseFloat(json.tarjeta) + parseFloat(json.efectivo) + parseFloat(json.bonos_cobrados));
				$('input[name=credito]').val(json.credito);
				$('input[name=tarjeta]').val(json.tarjeta);
				$('input[name=efectivo]').val(json.efectivo);
				$('input[name=bonos_cobrados]').val(json.bonos_cobrados);
			} else {
				$('input[name=totalCierre]').val(0);
				$('input[name=credito]').val(0);
				$('input[name=tarjeta]').val(0);
				$('input[name=efectivo]').val(0);
			}
		});
	}

	$('#FormCierre select[name=apertura]').change(function (event) {
		datosCierreCaja();
	});


	$('#FormCierre').validate({
		rules: {
			fecha: { required: true },
			caja: { required: true },
			apertura: { required: true },
			destino: { required: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormCierre', function (json) {
				$('#ModalAgregarCierre').modal('hide');
				$('#TableCierre').DataTable().ajax.reload();
			})
		}
	});

	/*=====  End of CAJA CIERRE  ======*/



	/*========================================
	=            IMPRESORA - LISTADO       =
	===========================================*/

	var TableMantenimientoImpresora = $('#TableMantenimientoImpresora').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regimpresora/jsonImpresora',
			"type": "GET",
			"data": function (d) {
				d.tb_impresora = $("input[name=tb_impresora]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#ImpresoraFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoImpresora').DataTable().ajax.reload();
		}
	});


	/*==========================================
				IMPRESORA - AGREGAR
	===========================================*/


	$('#FormImpresora').validate({
		rules: {
			impresora: { required: true },
			nombrelocal: { required: true },
			ip: { required: true },

		},
		submitHandler: function () {
			$('#ModalAgregarImpresora').modal('hide');
			enviarFormulario('#FormImpresora', function (json) {
				if (json.success) {
					$('#TableMantenimientoImpresora').DataTable().ajax.reload();
					$('#FormImpresora input[name=impresora]').val('');
					$('#FormImpresora input[name=nombrelocal]').val('');
					$('#FormImpresora input[name=serie]').val('');
					$('#FormImpresora input[name=url]').val('');
					$('#FormImpresora input[name=ip]').val('');

				}


			})
		}
	});


	/*==========================================
			 IMPRESORA - EDITAR
	===========================================*/

	$('#TableMantenimientoImpresora').on('click', '.editar-impresora', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regimpresora/getImpresora', { id }, function (json, textStatus) {
			$('#FormEditarImpresora input[name=id]').val(json.cod_impresora);
			$('#FormEditarImpresora input[name=impresora]').val(json.nom_impresora);
			$('#FormEditarImpresora input[name=nombrelocal]').val(json.nomlocal_impresora);
			$('#FormEditarImpresora input[name=serie]').val(json.serie_impresora);
			$('#FormEditarImpresora input[name=url]').val(json.url_impresora);
			$('#FormEditarImpresora input[name=ip]').val(json.ip_impresora);
		});
	});

	$('#FormEditarImpresora').validate({
		ignore: [],
		rules: {
			impresora: { required: true },
			nombrelocal: { required: true },
			ip: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarImpresora', function (json) {
				if (json.success) {
					$('#TableMantenimientoImpresora').DataTable().ajax.reload();
				}
				$('#ModalEditarImpresora').modal('hide');
				$('#FormEditarImpresora input[name=impresora]').val('');
				$('#FormEditarImpresora input[name=nombrelocal]').val('');
				$('#FormEditarImpresora input[name=serie]').val('');
				$('#FormEditarImpresora input[name=url]').val('');
				$('#FormEditarImpresora input[name=ip]').val('');

			})
		}
	});

	/*==========================================
			 IMPRESORA - ANULAR
	===========================================*/

	$('#TableMantenimientoImpresora').on('click', '.anular-impresora', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular impresora?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regimpresora/anularImpresora', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoImpresora').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*===========================================
	=            PUNTO DE VENTA - LISTADO            =
	===========================================*/
	var TableMantenimientoPventa = $('#TableMantenimientoPventa').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'administrador/regpventa/jsonPventa',
			"type": "GET",
			"data": function (d) {
				d.tb_puntoventa = $("input[name=tb_puntoventa]").val();
				d.tb_almacen = $("select[name=tb_almacen]").val();
				d.sede = $("select[name=sede]").val();
			}
		},
		"columns": [
			{ "orderable": false, "className": 'details-control' },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },

		]
	});

	$('#TableMantenimientoPventa tbody').on('click', '.asignar-cajas', function (event) {
		event.preventDefault();

		var id = $(this).data('id');
		$('#ModalAsignarCaja').modal();
		$('#ButtonAgregarCaja').show();
		$('#FormAgregarCajaPuntoVenta').hide();
		var caja = $(this).parent().parent().parent().find('td').eq(1).text();
		$('#FormAgregarCajaPuntoVenta input[name=puntoVenta]').val(id);
		$('#NombreDePuntoVenta').text(caja);
		pintarAsignacionCajas(id);
	});


	function pintarAsignacionCajas(id) {
		$('#TableAsignarCajaPuntoVenta tbody').empty();
		$.getJSON(path + 'administrador/regpventa/getCajas', { id }, function (json, textStatus) {
			var tr = '';
			$.each(json, function (index, val) {
				tr += `
				<tr>
					<td>${val.nomb_caja}</td>
					<td><button type="button" data-punto="${val.cod_puntoventa}" data-caja="${val.cod_caja}" class="btn btn-danger btn-sm quitar">Quitar</button></td>
				</tr>
			`;
			});
			$('#TableAsignarCajaPuntoVenta tbody').html(tr);
		});
	}

	$('#ButtonAgregarCaja').click(function (event) {
		$(this).hide();
		$('#FormAgregarCajaPuntoVenta').show();
		var punto = $('#FormAgregarCajaPuntoVenta input[name=puntoVenta]').val();
		$.getJSON(path + 'administrador/regpventa/getCajasParaAgregar', { punto }, function (json, textStatus) {
			var option = '<option value="">Seleccione</option>';
			$.each(json, function (index, val) {
				option += `
				<option value="${val.cod_caja}">${val.nomb_caja}</option>
			`;
			});
			$('#FormAgregarCajaPuntoVenta select[name=caja]').html(option);
		});
	});

	$('#FormAgregarCajaPuntoVenta').validate({
		ignore: [],
		rules: {
			puntoVenta: { required: true },
			caja: { required: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormAgregarCajaPuntoVenta', () => {
				$('#ButtonAgregarCaja').trigger('click');
				pintarAsignacionCajas($('#FormAgregarCajaPuntoVenta input[name=puntoVenta]').val());
			})
		}
	});

	$('#TableAsignarCajaPuntoVenta tbody').on('click', '.habilitarPorDefecto', function (event) {
		event.preventDefault();
		var caja = $(this).data('caja');
		var punto = $(this).data('punto');
		$.getJSON(path + 'administrador/regpventa/cambiarCajaDefecto', { caja, punto }, function (json, textStatus) {
			if (json.success) {
				pintarAsignacionCajas(punto);
			}
		});
	});

	$('#TableAsignarCajaPuntoVenta tbody').on('click', '.quitar', function (event) {
		event.preventDefault();
		var caja = $(this).data('caja');
		var punto = $(this).data('punto');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Confirma que desea quitar esta caja?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regpventa/quitarCaja', { caja, punto }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se quito correctamente.",
							type: "success"
						});
						pintarAsignacionCajas(punto);
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	$('#TableMantenimientoPventa tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = TableMantenimientoPventa.row(tr);

		if (row.child.isShown()) {
			row.child.hide();
			tr.removeClass('shown');
			$(this).find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
		} else {
			row.child(formatTratamientosDetalle(row.data())).show();
			tr.addClass('shown');
			$(this).find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
		}
	});

	function formatTratamientosDetalle(d) {
		var usuarios = jQuery.parseJSON(d[7]);
		var table = `
	
		<table class="table">
			<thead>
				<tr>
					<th>Usuario</th>
					<th>Perfil</th>
				</tr>
			</thead>
			<tbody>
		`;
		var tr = '';
		$.each(usuarios, function (index, val) {
			tr += `
			<tr>
				<td>${val['apell_usu'] + ' ' + val['nomb_usu']}</td>
				<td>${val['nomb_perfil']}</td>
			</tr>
		 `;
		});
		console.log(tr);

		table += tr;
		table += `
			</tbody>
		</table>`;
		return table;
	}





	$('#TableMantenimientoPventa tbody').on('click', '.asignar-almacen', function (event) {
		event.preventDefault();

		var id = $(this).data('id');
		$('#ModalAsignarAlmacen').modal();
		$('#ButtonAgregarAlmacen').show();
		$('#FormAgregarAlmacenPuntoVenta').hide();
		var almacen = $(this).parent().parent().parent().find('td').eq(1).text();
		$('#FormAgregarAlmacenPuntoVenta input[name=puntoVenta]').val(id);
		$('#NombreDePuntoVenta').text(almacen);
		pintarAsignacionAlmacenes(id);
	});

	function pintarAsignacionAlmacenes(id) {
		$('#TableAsignarAlmacen tbody').empty();
		$.getJSON(path + 'administrador/regpventa/getAlmacenes', { id }, function (json, textStatus) {
			var tr = '';
			$.each(json, function (index, val) {

				if (val.pordefecto == 1) {
					var defecto = '<button class="btn btn-sm btn-info"><i class="fa fa-star"></i></button> Por Defecto';
					var quitar = '';
				} else {
					var defecto = `<button data-almacen="${val.cod_almacen}" data-punto="${val.cod_puntoventa}" class="btn btn-sm btn-primary habilitarPorDefecto">Habilitar</button>`;
					var quitar = `<button type="button" data-almacen="${val.cod_almacen}" data-punto="${val.cod_puntoventa}" class="btn btn-danger btn-sm quitar">Quitar</button>`;
				}
				tr += `
				<tr>
					<td>${val.nomb_almacen}</td>
					<td>${defecto + quitar}</td>
				</tr>
			`;
			});
			$('#TableAsignarAlmacenPuntoVenta tbody').html(tr);
		});
	}

	$('#ButtonAgregarAlmacen').click(function (event) {
		$(this).hide();
		$('#FormAgregarAlmacenPuntoVenta').show();
		var punto = $('#FormAgregarAlmacenPuntoVenta input[name=puntoVenta]').val();
		$.getJSON(path + 'administrador/regpventa/getAlmacenesParaAgregar', { punto }, function (json, textStatus) {
			var option = '<option value="">Seleccione</option>';
			$.each(json, function (index, val) {
				option += `
				<option value="${val.cod_almacen}">${val.nomb_almacen}</option>
			`;
			});
			$('#FormAgregarAlmacenPuntoVenta select[name=almacen]').html(option);
		});
	});

	$('#FormAgregarAlmacenPuntoVenta').validate({
		ignore: [],
		rules: {
			puntoVenta: { required: true },
			almacen: { required: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormAgregarAlmacenPuntoVenta', () => {
				$('#ButtonAgregarAlmacen').trigger('click');
				pintarAsignacionAlmacenes($('#FormAgregarAlmacenPuntoVenta input[name=puntoVenta]').val());
			})
		}
	});

	$('#TableAsignarAlmacenPuntoVenta tbody').on('click', '.habilitarPorDefecto', function (event) {
		event.preventDefault();
		var almacen = $(this).data('almacen');
		var punto = $(this).data('punto');
		$.getJSON(path + 'administrador/regpventa/cambiarAlmacenDefecto', { almacen, punto }, function (json, textStatus) {
			if (json.success) {
				pintarAsignacionAlmacenes(punto);
			}
		});
	});

	$('#TableAsignarAlmacenPuntoVenta tbody').on('click', '.quitar', function (event) {
		event.preventDefault();
		var almacen = $(this).data('almacen');
		var punto = $(this).data('punto');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Confirma que desea quitar este almacen?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regpventa/quitarAlmacen', { almacen, punto }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se quito correctamente.",
							type: "success"
						});
						pintarAsignacionAlmacenes(punto);
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});




	$('#PventaFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoPventa').DataTable().ajax.reload();
		}
	});

	$('#TableMantenimientoPventa').on('click', '.asignarPorDefecto', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regpventa/asignarPorDefecto', { id }, function (json, textStatus) {
			if (json.success) {
				$('#TableMantenimientoPventa').DataTable().ajax.reload();
				Swal.fire({
					title: "Buen trabajo",
					text: "Se cambio el punto de venta por defecto.",
					type: "success"
				});
			}
		});
	});
	/*==========================================
				PUNTO DE VENTA - AGREGAR
	===========================================*/


	$('#FormPuntoVenta').validate({
		rules: {
			punto: { required: true },
			almacen: { required: true },
			caja: { required: true },
			impresora: { required: true },
			sede: { required: true },
			ubigeo:{required:true},
			telefono:{required:true},
			direccion:{required:true},
			email:{required:true, email:true},
			codigo:{required:true},

		},
		submitHandler: function () {
			$('#ModalAgregarPunto').modal('hide');
			enviarFormulario('#FormPuntoVenta', function (json) {
				if (json.success) {
					$('#TableMantenimientoPventa').DataTable().ajax.reload();
					$('#FormPuntoVenta input[name=punto]').val('');
					$('#FormPuntoVenta select[name=almacen]').select2('val', '');
					$('#FormPuntoVenta select[name=almacen]').select2().trigger('change');
					$('#FormPuntoVenta select[name=caja]').select2('val', '');
					$('#FormPuntoVenta select[name=caja]').select2().trigger('change');
					$('#FormPuntoVenta select[name=impresora]').select2('val', '');
					$('#FormPuntoVenta select[name=impresora]').select2().trigger('change');
					$('#FormPuntoVenta select[name=sede]').select2('val', '');
					$('#FormPuntoVenta select[name=sede]').select2().trigger('change');
					$('#FormPuntoVenta select[name=ubigeo]').select2('val', '');
					$('#FormPuntoVenta select[name=ubigeo]').select2().trigger('change');
					$('#FormPuntoVenta input[name=telefono]').val('');
					$('#FormPuntoVenta input[name=direccion]').val('');
					$('#FormPuntoVenta input[name=email]').val('');
					$('#FormPuntoVenta input[name=codigo]').val('');

				}


			})
		}
	});


	/*==========================================
			 PUNTO DE VENTA - EDITAR
	===========================================*/

	$('#TableMantenimientoPventa').on('click', '.editar-punto', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regpventa/getPventa', { id }, function (json, textStatus) {
			$('#FormEditarPventa input[name=id]').val(json.cod_puntoventa);
			$('#FormEditarPventa input[name=punto]').val(json.nomb_puntoventa);
			$('#FormEditarPventa select[name=almacen]').val(json.cod_almacen);
			$('#FormEditarPventa select[name=caja]').val(json.cod_caja);
			$('#FormEditarPventa select[name=impresora]').val(json.cod_impresora);
			$('#FormEditarPventa select[name=sede]').val(json.cod_sede);
			$('#FormEditarPventa select[name=ubigeo]').val(json.ubigeo_puntoventa);
			$('#FormEditarPventa select[name=ubigeo]').select2().trigger('change');
			$('#FormEditarPventa input[name=telefono]').val(json.telefono_puntoventa);
			$('#FormEditarPventa input[name=direccion]').val(json.direccion_puntoventa);
			$('#FormEditarPventa input[name=email]').val(json.email_puntoventa);
			$('#FormEditarPventa input[name=codigo]').val(json.codigosunat_puntoventa);
		});
	});

	$('#FormEditarPventa').validate({
		ignore: [],
		rules: {
			punto: { required: true },
			almacen: { required: true },
			caja: { required: true },
			impresora: { required: true },
			sede: { required: true },
			ubigeo:{required:true},
			telefono:{required:true},
			direccion:{required:true},
			email:{required:true, email:true},
			codigo:{required:true},


		},
		submitHandler: function () {
			enviarFormulario('#FormEditarPventa', function (json) {
				if (json.success) {
					$('#TableMantenimientoPventa').DataTable().ajax.reload();
				}
				$('#ModalEditarPventa').modal('hide');
				$('#FormEditarPventa input[name=punto]').val('');
				$('#FormEditarPventa select[name=almacen]').select('val', '');
				$('#FormEditarPventa select[name=caja]').select('val', '');
				$('#FormEditarPventa select[name=impresora]').select('val', '');
				$('#FormEditarPventa select[name=sede]').select('val', '');

			})
		}
	});


	/*==========================================
			 PUNTO DE VENTA - ANULAR
	===========================================*/

	$('#TableMantenimientoPventa').on('click', '.anular-punto', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Punto de Venta?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regpventa/anularPventa', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoPventa').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*===========================================
	=            TIPO DE DOCUMENTO - LISTADO            =
	===========================================*/
	var TableMantenimientoDocum = $('#TableMantenimientoDocum').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regtipodocum/jsonDocumento',
			"type": "GET",
			"data": function (d) {
				d.tb_tipodocumento = $("input[name=tb_tipodocumento]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#DocumentoFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoDocum').DataTable().ajax.reload();
		}
	});

	/*==========================================
				TIPO DOCUMENTO - AGREGAR
	===========================================*/


	$('#FormDocumento').validate({
		rules: {
			descripcion: { required: true },
		},
		submitHandler: function () {
			$('#ModalAgregarDocum').modal('hide');
			enviarFormulario('#FormDocumento', function (json) {
				if (json.success) {
					$('#TableMantenimientoDocum').DataTable().ajax.reload();
					$('#FormDocumento input[name=descripcion]').val('');

				}


			})
		}
	});


	/*==========================================
			 TIPO DOCUMENTO - EDITAR
	===========================================*/

	$('#TableMantenimientoDocum').on('click', '.editar-documento', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regtipodocum/getDocumento', { id }, function (json, textStatus) {
			$('#FormEditarDocumento input[name=id]').val(json.cod_tipdocu);
			$('#FormEditarDocumento input[name=descripcion]').val(json.nom_tipdocumento);
			$('#FormEditarDocumento select[name=estado]').val(json.est_tipdocum);
		});
	});

	$('#FormEditarDocumento').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },
			estado: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarDocumento', function (json) {
				if (json.success) {
					$('#TableMantenimientoDocum').DataTable().ajax.reload();
				}
				$('#ModalEditarDocumento').modal('hide');
				$('#FormEditarDocumento input[name=descripcion]').val('');
				$('#FormEditarDocumento select[name=estado]').select('val', '');

			})
		}
	});


	/*==========================================
			 TIPO DOCUMENTO - ANULAR
	===========================================*/

	$('#TableMantenimientoDocum').on('click', '.anular-documento', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Documento?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regtipodocum/anularDocumento', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoDocum').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*===========================================
	=            TALONARIO - LISTADO            =
	===========================================*/
	var TableMantenimientoTalonario = $('#TableMantenimientoTalonario').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regtalonario/jsonTalonario',
			"type": "GET",
			"data": function (d) {
				d.tb_tipodocumento = $("select[name=tb_tipodocumento]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#TalonarioFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoTalonario').DataTable().ajax.reload();
		}
	});

	/*==========================================
				TALONARIO - AGREGAR
	===========================================*/


	$('#FormTalonario').validate({
		rules: {
			documento: { required: true },
			punto: { required: true },
			impresora: { required: true },
			serie: { required: true },
			siglas: { required: true },

		},
		submitHandler: function () {
			$('#ModalAgregarTalonario').modal('hide');
			enviarFormulario('#FormTalonario', function (json) {
				if (json.success) {
					$('#TableMantenimientoTalonario').DataTable().ajax.reload();
					$('#FormTalonario select[name=documento]').select2('val', '');
					$('#FormTalonario select[name=punto]').select2('val', '');
					$('#FormTalonario select[name=impresora]').select2('val', '');
					$('#FormTalonario input[name=serie]').val('');
					$('#FormTalonario input[name=inicio]').val('');
					$('#FormTalonario input[name=fin]').val('');
					$('#FormTalonario input[name=actual]').val('');
					$('#FormTalonario select[name=siglas]').select2('val', '');




				}


			})
		}
	});


	/*==========================================
			 TALONARIO - EDITAR
	===========================================*/

	$('#TableMantenimientoTalonario').on('click', '.editar-talonario', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regtalonario/getTalonario', { id }, function (json, textStatus) {
			$('#FormEditarTalonario input[name=id]').val(json.cod_talonario);
			$('#FormEditarTalonario select[name=documento]').val(json.cod_tipdocu);
			$('#FormEditarTalonario input[name=serie]').val(json.serie);
			$('#FormEditarTalonario select[name=punto]').val(json.cod_puntoventa);
			$('#FormEditarTalonario select[name=impresora]').val(json.cod_impresora);
			$('#FormEditarTalonario input[name=inicio]').val(json.talonario_ini);
			$('#FormEditarTalonario input[name=fin]').val(json.talonario_fin);
			$('#FormEditarTalonario input[name=actual]').val(json.correlativo_actual);
			$('#FormEditarTalonario select[name=siglas]').val(json.siglas_talonario);
			$('#FormEditarTalonario select[name=estado]').val(json.est_talonario);
			$('input[name=doccli_dni]').prop('checked',false);
			$('input[name=doccli_ruc]').prop('checked',false);
			if (json.docclidni_talonario==1) {
				$('#FormEditarTalonario input[name=doccli_dni]').prop('checked',true);
			}
			if (json.doccliruc_talonario==1) {
				$('#FormEditarTalonario input[name=doccli_ruc]').prop('checked',true);
			}
		});
	});

	$('#FormEditarTalonario').validate({
		ignore: [],
		rules: {
			documento: { required: true },
			punto: { required: true },
			impresora: { required: true },
			serie: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarTalonario', function (json) {
				if (json.success) {
					$('#TableMantenimientoTalonario').DataTable().ajax.reload();
				}
				$('#ModalEditarTalonario').modal('hide');
				$('#FormEditarTalonario select[name=documento]').select('val', '');
				$('#FormEditarTalonario input[name=serie]').val('');
				$('#FormEditarTalonario select[name=punto]').select('val', '');
				$('#FormEditarTalonario select[name=impresora]').select('val', '');
				$('#FormEditarTalonario input[name=inicio]').val('');
				$('#FormEditarTalonario input[name=fin]').val('');
				$('#FormEditarTalonario input[name=actual]').val('');
				$('#FormEditarTalonario select[name=siglas]').select('val', '');
				$('#FormEditarTalonario select[name=estado]').select('val', '');

			})
		}
	});


	/*==========================================
			 TALONARIO - ANULAR
	===========================================*/

	$('#TableMantenimientoTalonario').on('click', '.anular-talonario', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Administracion de Talonario?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regtalonario/anularTalonario', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoTalonario').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*===========================================
	=            TIPO ARTICULO - LISTADO            =
	===========================================*/
	var TableMantenimientoTipo = $('#TableMantenimientoTipo').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regtiparticulo/jsonTiparticulo',
			"type": "GET",
			"data": function (d) {
				d.tb_tiparticulo = $("input[name=tb_tiparticulo]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#TipoFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoTipo').DataTable().ajax.reload();
		}
	});

	/*==========================================
				TIPO ARTICULO - AGREGAR
	===========================================*/


	$('#FormTipo').validate({
		rules: {
			descripcion: { required: true },
			stock: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarTiparticulo').modal('hide');
			enviarFormulario('#FormTipo', function (json) {
				if (json.success) {
					$('#TableMantenimientoTipo').DataTable().ajax.reload();
					$('#FormTalonario input[name=descripcion]').val('');
					$('#FormTalonario select[name=stock]').select2('val', '');

				}


			})
		}
	});


	/*==========================================
			 TIPO ARTICULO - EDITAR
	===========================================*/

	$('#TableMantenimientoTipo').on('click', '.editar-tipo', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regtiparticulo/getTiparticulo', { id }, function (json, textStatus) {
			$('#FormEditarTiparticulo input[name=id]').val(json.cod_tiparticulo);
			$('#FormEditarTiparticulo input[name=descripcion]').val(json.nomb_tiparticulo);
			$('#FormEditarTiparticulo select[name=stock]').val(json.stock_tiparticulo);
			$('#FormEditarTiparticulo select[name=estado]').val(json.est_tiparticulo);
		});
	});

	$('#FormEditarTiparticulo').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarTiparticulo', function (json) {
				if (json.success) {
					$('#TableMantenimientoTipo').DataTable().ajax.reload();
				}
				$('#ModalEditarTiparticulo').modal('hide');
				$('#FormEditarTiparticulo input[name=descripcion]').val('');
				$('#FormEditarTiparticulo select[name=stock]').select('val', '');
				$('#FormEditarTiparticulo select[name=estado]').select('val', '');
			})
		}
	});


	/*==========================================
			 TIPO ARTICULO - ANULAR
	===========================================*/

	$('#TableMantenimientoTipo').on('click', '.anular-tipo', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Tipo de Articulo?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regtiparticulo/anularTiparticulo', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoTipo').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});



	/*===========================================
	=            CATEGORIA - LISTADO            =
	===========================================*/
	var TableMantenimientoCategoria = $('#TableMantenimientoCategoria').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcategoria/jsonCategoria',
			"type": "GET",
			"data": function (d) {
				d.tb_categoria = $("input[name=tb_categoria]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#CategoriaFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoCategoria').DataTable().ajax.reload();
		}
	});


	/*==========================================
				CATEGORIA - AGREGAR
	===========================================*/


	$('#FormCategoria').validate({
		rules: {
			descripcion: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarCategoria').modal('hide');
			enviarFormulario('#FormCategoria', function (json) {
				if (json.success) {
					$('#TableMantenimientoCategoria').DataTable().ajax.reload();
					$('#FormCategoria input[name=descripcion]').val('');


				}


			})
		}
	});


	/*==========================================
			 CATEGORIA - EDITAR
	===========================================*/

	$('#TableMantenimientoCategoria').on('click', '.editar-categoria', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regcategoria/getCategoria', { id }, function (json, textStatus) {
			$('#FormEditarCategoria input[name=id]').val(json.cod_categoria);
			$('#FormEditarCategoria input[name=descripcion]').val(json.nomb_categoria);
			$('#FormEditarCategoria select[name=estado]').val(json.est_categoria);
		});
	});

	$('#FormEditarCategoria').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarCategoria', function (json) {
				if (json.success) {
					$('#TableMantenimientoCategoria').DataTable().ajax.reload();
				}
				$('#ModalEditarCategoria').modal('hide');
				$('#FormEditarCategoria input[name=descripcion]').val('');
				$('#FormEditarCategoria select[name=estado]').select('val', '');
			})
		}
	});


	/*==========================================
			 CATEGORIA - ANULAR
	===========================================*/

	$('#TableMantenimientoCategoria').on('click', '.anular-categoria', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Categoria?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regcategoria/anularCategoria', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoCategoria').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	// sede
		/*===========================================
	=            SEDE LISTADO            =
	===========================================*/
	var TableMantenimientoSede = $('#TableMantenimientoSede').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regsede/jsonsede',
			"type": "GET",
			"data": function (d) {
				d.sede = $("input[name=sede]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#SedeFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoSede').DataTable().ajax.reload();
		}
	});
	/*==========================================
				SEDE - AGREGAR
	===========================================*/


	$('#FormSede').validate({
		rules: {
			descripcion: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarSede').modal('hide');
			enviarFormulario('#FormSede', function (json) {
				if (json.success) {
					$('#TableMantenimientoSede').DataTable().ajax.reload();
					$('#FormSede input[name=descripcion]').val('');


				}


			})
		}
	});


	/*==========================================
			 CATEGORIA - EDITAR
	===========================================*/

	$('#TableMantenimientoSede').on('click', '.editar-sede', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regsede/getsede', { id }, function (json, textStatus) {
			$('#FormEditarSede input[name=id]').val(json.cod_sede);
			$('#FormEditarSede input[name=descripcion]').val(json.sede_nombre);
			$('#FormEditarSede select[name=estado]').val(json.sede_estado);
		});
	});

	$('#FormEditarSede').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarSede', function (json) {
				if (json.success) {
					$('#TableMantenimientoSede').DataTable().ajax.reload();
				}
				$('#ModalEditarSede').modal('hide');
				$('#FormEditarSede input[name=descripcion]').val('');
				$('#FormEditarSede select[name=estado]').select('val', '');
			})
		}
	});


	/*==========================================
			 CATEGORIA - ANULAR
	===========================================*/

	$('#TableMantenimientoSede').on('click', '.anular-sede', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular sede?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regsede/anularsede', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoSede').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*===========================================
	=            MARCA - LISTADO            =
	===========================================*/
	var TableMantenimientoMarca = $('#TableMantenimientoMarca').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regmarca/jsonMarca',
			"type": "GET",
			"data": function (d) {
				d.tb_marca = $("input[name=tb_marca]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#MarcaFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoMarca').DataTable().ajax.reload();
		}
	});


	/*==========================================
				MARCA - AGREGAR
	===========================================*/


	$('#FormMarca').validate({
		rules: {
			descripcion: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarMarca').modal('hide');
			enviarFormulario('#FormMarca', function (json) {
				if (json.success) {
					$('#TableMantenimientoMarca').DataTable().ajax.reload();
					$('#FormMarca input[name=descripcion]').val('');


				}


			})
		}
	});



	/*==========================================
			 MARCA - EDITAR
	===========================================*/

	$('#TableMantenimientoMarca').on('click', '.editar-marca', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regmarca/getMarca', { id }, function (json, textStatus) {
			$('#FormEditarMarca input[name=id]').val(json.cod_marca);
			$('#FormEditarMarca input[name=descripcion]').val(json.nomb_marca);
			$('#FormEditarMarca select[name=estado]').val(json.est_marca);
		});
	});

	$('#FormEditarMarca').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarMarca', function (json) {
				if (json.success) {
					$('#TableMantenimientoMarca').DataTable().ajax.reload();
				}
				$('#ModalEditarMarca').modal('hide');
				$('#FormEditarMarca input[name=descripcion]').val('');
				$('#FormEditarMarca select[name=estado]').select('val', '');
			})
		}
	});


	/*==========================================
			 MARCA - ANULAR
	===========================================*/

	$('#TableMantenimientoMarca').on('click', '.anular-marca', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Marca?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regmarca/anularMarca', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoMarca').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});



	/*===========================================
	=            UNIDAD MEDIDA - LISTADO            =
	===========================================*/
	var TableMantenimientoUmedida = $('#TableMantenimientoUmedida').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regunidad/jsonUnidad',
			"type": "GET",
			"data": function (d) {
				d.tb_unidades = $("input[name=tb_unidades]").val();
				d.tb_tipounidad = $("select[name=tb_tipounidad]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#UmedidaFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoUmedida').DataTable().ajax.reload();
		}
	});


	/*==========================================
				UNIDAD DE MEDIDA - AGREGAR
	===========================================*/


	$('#FormUmedida').validate({
		rules: {
			abreviatura: { required: true },
			descripcion: { required: true },
			factor: { required: true },
			tipounidad: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarUmedida').modal('hide');
			enviarFormulario('#FormUmedida', function (json) {
				if (json.success) {
					$('#TableMantenimientoUmedida').DataTable().ajax.reload();
					$('#FormUmedida input[name=abreviatura]').val('');
					$('#FormUmedida input[name=descripcion]').val('');
					$('#FormUmedida input[name=factor]').val('');
					$('#FormUmedida select[name=tipounidad]').select2('val', '');

				}


			})
		}
	});


	/*==========================================
			 UNIDAD DE MEDIDA - EDITAR
	===========================================*/

	$('#TableMantenimientoUmedida').on('click', '.editar-umedida', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regunidad/getUmedida', { id }, function (json, textStatus) {
			$('#FormEditarUmedida input[name=id]').val(json.cod_unid);
			$('#FormEditarUmedida input[name=abreviatura]').val(json.abreviatura_unid);
			$('#FormEditarUmedida input[name=descripcion]').val(json.nomb_unid);
			$('#FormEditarUmedida input[name=factor]').val(json.fact_unid);
			$('#FormEditarUmedida select[name=tipounidad]').val(json.cod_tipunidad);
			$('#FormEditarUmedida select[name=estado]').val(json.est_unidad);
		});
	});

	$('#FormEditarUmedida').validate({
		ignore: [],
		rules: {
			abreviatura: { required: true },
			descripcion: { required: true },
			factor: { required: true },
			tipounidad: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarUmedida', function (json) {
				if (json.success) {
					$('#TableMantenimientoUmedida').DataTable().ajax.reload();
				}
				$('#ModalEditarUmedida').modal('hide');
				$('#FormEditarUmedida input[name=abreviatura]').val('');
				$('#FormEditarUmedida input[name=descripcion]').val('');
				$('#FormEditarUmedida input[name=factor]').val('');
				$('#FormEditarUmedida select[name=tipounidad]').select('val', '');
				$('#FormEditarUmedida select[name=estado]').select('val', '');
			})
		}
	});


	/*==========================================
			 UNIDAD MEDIDA - ANULAR
	===========================================*/

	$('#TableMantenimientoUmedida').on('click', '.anular-umedida', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Unidad de Medida?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regunidad/anularUmedida', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoUmedida').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*===========================================
	=            PRODUCTOS - LISTADO            =
	===========================================*/
	var TableMantenimientoProducto = $('#TableMantenimientoProducto').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regproducto/jsonProducto',
			"type": "GET",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_producto = $("input[name=tb_producto]").val();
				//	d.tb_producto = $("input[name=tb_producto]").val();
				d.tb_categoria = $("select[name=tb_categoria]").val();
				d.tb_marca = $("select[name=tb_marca]").val();
				d.tb_tiparticulo = $("select[name=tb_tiparticulo]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#ProductoFormBusqueda select[name=tb_categoria]').change(function (event) {
		$('#TableMantenimientoProducto').DataTable().ajax.reload();
	});

	$('#ProductoFormBusqueda select[name=tb_marca]').change(function (event) {
		$('#TableMantenimientoProducto').DataTable().ajax.reload();
	});
	$('#ProductoFormBusqueda select[name=tb_tiparticulo]').change(function (event) {
		$('#TableMantenimientoProducto').DataTable().ajax.reload();
	});

	$('#ProductoFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoProducto').DataTable().ajax.reload();
		}
	});


	/*==========================================
				PRODUCTO - AGREGAR
	===========================================*/


	$('#FormProducto').validate({
		ignore: [],
		rules: {
			tipoarticulo: { required: true },
			nombre: { required: true },
			marca: { required: true },
			categoria: { required: true },
			unidad: { required: true },
			linea: { required: true },
			sublinea: { required: true },
			talla: { required: true },
			presentacion: { required: true },
			preciocosto: { required: true },
			precioventa: { required: true },
			stock: { required: true },
			dispventa: { required: true },
			dispcompra: { required: true },
			parametros: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarProducto').modal('hide');
			enviarFormulario('#FormProducto', function (json) {
				if (json.success) {
					$('#TableMantenimientoProducto').DataTable().ajax.reload();
					$('#FormProducto select[name=tipoarticulo]').val('');
					$('#FormProducto input[name=nombre]').val('');
					$('#FormProducto select[name=marca]').select('val', '');
					$('#FormProducto select[name=categoria]').select('val', '');
					$('#FormProducto select[name=unidad]').select('val', '');
					$('#FormProducto select[name=linea]').select('val', '');
					$('#FormProducto select[name=sublinea]').select('val', '');
					$('#FormProducto select[name=talla]').select('val', '');
					$('#FormProducto select[name=presentacion]').select('val', '');
					$('#FormProducto input[name=codigobarra]').val('');
					$('#FormProducto input[name=preciocosto]').val('');
					$('#FormProducto input[name=precioventa]').val('');
					$('#FormProducto input[name=stock]').val('');
					$('#FormProducto select[name=dispventa]').select('val', '');
					$('#FormProducto select[name=dispcompra]').select('val', '');
					$('#FormProducto select[name=parametros]').select('val', '');
					
				}
					
					


		


			})
		}
	});

	/*==========================================
				PRODUCTO - EDITAR
	===========================================*/

	$('#TableMantenimientoProducto').on('click', '.editar-producto', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regproducto/getProducto', { id }, function (json, textStatus) {
			$('#FormEditarProducto input[name=id]').val(json.cod_producto);
			$('#FormEditarProducto select[name=tipoarticulo]').val(json.cod_tiparticulo);
			$('#FormEditarProducto input[name=nombre]').val(json.nomb_product);
			switch(json.typeAssignmentProduct) {
				case 'P':
					$("#FormEditarProducto input[name='editproductAssignmentDad']").prop('checked', true);
					$("#FormEditarProducto input[name='editproductAssignmentSon']").prop('checked', false);
				 break;
				 case 'H':
					$("#FormEditarProducto input[name='editproductAssignmentSon']").prop('checked', true);
					$("#FormEditarProducto input[name='editproductAssignmentDad']").prop('checked', false);
				 break;
				default:
					$("#FormEditarProducto input[name='editproductAssignmentDad']").prop('checked', false);
					$("#FormEditarProducto input[name='editproductAssignmentSon']").prop('checked', false);
			   }  
			$('#FormEditarProducto select[name=editselectAssignmentDad]').val(json.idTypeAssignmentProduct);
			$('#FormEditarProducto select[name=marca]').val(json.cod_marca);
			$('#FormEditarProducto select[name=categoria]').val(json.cod_categoria);
			$('#FormEditarProducto select[name=unidad]').val(json.cod_unid);
			$('#FormEditarProducto select[name=linea]').val(json.cod_linea);
			$('#FormEditarProducto select[name=sublinea]').val(json.cod_sublinea);
			$('#FormEditarProducto select[name=talla]').val(json.cod_talla);
			$('#FormEditarProducto select[name=presentacion]').val(json.cod_present);
			$('#FormEditarProducto input[name=codigobarra]').val(json.barra_product);
			$('#FormEditarProducto input[name=preciocosto]').val(json.prec_costo);
			$('#FormEditarProducto input[name=precioventa]').val(json.prec_venta);
			$('#FormEditarProducto input[name=precioventa_mayor]').val(json.prec_mayor_venta);
			$('#FormEditarProducto input[name=precioventa_especial]').val(json.prec_especial_venta);
			$('#FormEditarProducto input[name=stock]').val(json.stockmin_product);
			$('#FormEditarProducto input[name=fecharegistro]').val(json.fecha_registro);
			$('#FormEditarProducto select[name=dispventa]').val(json.dispo_venta);
			$('#FormEditarProducto select[name=dispcompra]').val(json.dispo_compra);
			$('#FormEditarProducto select[name=parametros]').val(json.cod_parametros);
			$('#FormEditarProducto select[name=estado]').val(json.est_product);
		});
	});

	// $('#cambiarPassword').click(function(event) {

	// 	if ($(this).is(":checked")) {
	// 		$('#FormEditarUsuario input[name=passwoord]').prop('disabled', false);
	// 	}else{
	// 		$('#FormEditarUsuario input[name=passwoord]').prop('disabled', true);
	// 	}
	// });

	$('#FormEditarProducto').validate({
		ignore: [],
		rules: {
			tipoarticulo: { required: true },
			nombre: { required: true },
			marca: { required: true },
			categoria: { required: true },
			unidad: { required: true },
			linea: { required: true },
			sublinea: { required: true },
			talla: { required: true },
			presentacion: { required: true },
			preciocosto: { required: true },
			precioventa: { required: true },
			stock: { required: true },
			dispventa: { required: true },
			dispcompra: { required: true },
			parametros: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarProducto', function (json) {
				if (json.success) {
					$('#TableMantenimientoProducto').DataTable().ajax.reload();
				}
				$('#ModalEditarProducto').modal('hide');
				$('#FormEditarProducto select[name=tipoarticulo]').select('val', '');
				$('#FormEditarProducto input[name=nombre]').val('');
				$('#FormEditarProducto select[name=marca]').select('val', '');
				$('#FormEditarProducto select[name=categoria]').select('val', '');
				$('#FormEditarProducto select[name=unidad]').select('val', '');
				$('#FormEditarProducto select[name=linea]').select('val', '');
				$('#FormEditarProducto select[name=sublinea]').select('val', '');
				$('#FormEditarProducto select[name=talla]').select('val', '');
				$('#FormEditarProducto select[name=presentacion]').select('val', '');
				$('#FormEditarProducto input[name=codigobarra]').val('');
				$('#FormEditarProducto input[name=preciocosto]').val('');
				$('#FormEditarProducto input[name=precioventa]').val('');
				$('#FormEditarProducto input[name=stock]').val('');
				$('#FormEditarProducto input[name=fecharegistro]').val('');
				$('#FormEditarProducto select[name=dispventa]').select('val', '');
				$('#FormEditarProducto select[name=dispcompra]').select('val', '');
				$('#FormEditarProducto select[name=parametros]').select('val', '');
				$('#FormEditarProducto select[name=estado]').select('val', '');
			})
		}
	});

	/*==========================================
			 PRODUCTO - ANULAR
	===========================================*/

	$('#TableMantenimientoProducto').on('click', '.anular-producto', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Deshabilitar producto?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regproducto/anularProducto', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoProducto').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});

	/*==========================================
	=            INVENTARIO INICIAL            =
	==========================================*/
	var TableAlmacenInventarioInicial = $('#TableAlmacenInventarioInicial').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'administrador/reginventarioinicial/jsonInventarioInicial',
			"type": "GET",
			"data": function (d) {
				d.almacen = $("select[name=almacen]").val();
				d.producto = $("input[name=producto]").val();
				d.categoria = $("select[name=categoria]").val();
				d.marca = $("select[name=marca]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },

		],
		"columnDefs": [
			{ "width": "20%", "targets": 7 }
		]
	});

	$('#InventarioInicialReportePdf').click(function (event) {
		let form = $('#FormAlmacenInventarioInicialFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/reginventarioinicial/reportePdf?' + params);
	});

	$('#InventarioInicialReporteExcel').click(function (event) {
		let form = $('#FormAlmacenInventarioInicialFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/reginventarioinicial/reporteExcel?' + params);
	});

		$('#InventarioInicialReporteExcelSeries').click(function (event) {
		let form = $('#FormAlmacenInventarioInicialFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/reginventarioinicial/reporteExcelSeries?' + params);
	});

	$('#FormAlmacenInventarioInicialFiltro select[name=almacen], #FormAlmacenInventarioInicialFiltro select[name=categoria], #FormAlmacenInventarioInicialFiltro select[name=marca]').change(function (event) {
		$('#TableAlmacenInventarioInicial').DataTable().ajax.reload();
	});

	/*$('#FormAlmacenInventarioInicialFiltro input[name=producto]').focusout(function(event) {
		$('#TableAlmacenInventarioInicial').DataTable().ajax.reload();
	});*/

	$('#TableAlmacenInventarioInicial tbody').on('click', '.agregarStockAlmacenInicial', function (event) {
		event.preventDefault();
		var producto = $(this).data('producto');
		var almacen = $(this).data('almacen');
		var stock = $('#cantidad-producto-'+producto).val();
		var series = $('input[name^="producto-'+producto+'"]').serializeObject();
		var array_series = [];
		$('input[name="producto-'+producto+'"]').each(function(index,elem) {
			if($(elem).val()!=''){
				array_series.push($(elem).val());
			}
		});

		var duplicados = buscar_duplicado_array(array_series);
		
		if(duplicados.length > 0){

			
			Swal.fire({
				title: "Error",
				text: "Las series "+duplicados.toString()+" son duplicados",
				type: "error"
			});

			return;
		}

		$.getJSON(path + 'administrador/reginventarioinicial/guardarStockInicial', { producto, almacen, stock,series }, function (json, textStatus) {
			if (json.success) {
				Swal.fire({
					title: "Buen trabajo",
					text: "Se agrego el stock inicial.",
					type: "success"
				});
				$('#TableAlmacenInventarioInicial').DataTable().ajax.reload();
			} else {

				Swal.fire({
					title: "Error",
					text: "Ocurrio un error, vuelva a intentarlo.",
					type: "error"
				});
			}
		});
	});

		$('#TableAlmacenInventarioInicial tbody').on('click', '.agregar-series', function (event) {
		let producto = $(this).data('producto');
		$('#cantidad-producto-'+producto).prop('disabled',true);
		$('#agregar-producto-'+producto).prop('disabled',true);
		var numero = window.prompt('¿Cuantas series deseas agregar?');
		var series = [];
		for (let index = 1; index <= numero; index++) {			
			var valor = window.prompt('Ingrese la serie N° '+index);
			series.push(valor);
		}
		// var lista = '';
		
		for (let index = 0; index < series.length; index++) {
		var serie = series[index];
			$.ajax({
				type: "POST",
				url: path+"administrador/reginventarioinicial/verificarSerieUnico",
				data: {serie},
				dataType: "JSON",
				success: function (response) {
					if(response.success){
						var texto = '';
						var corregir = '';
					}else{
						var corregir = `<button title="Corregir" class="btn btn-info corregir-duplicado" data-producto="${producto}" data-serie="${series[index]}"><i class="fa fa-sync"></i></button>`;
						var texto = `<span id="duplicado-${series[index]}" class="text-danger">Duplicado</span>`;
					}
					var lista = `
					<div>
						${texto}
						<div class="input-group mb-3">
							<input name="producto-${producto}" type="text" value="${series[index]}" class="form-control">
							<div class="input-group-append">
								<button data-producto="${producto}" class="btn btn-outline-danger eliminar-serie" type="button"><i class="fa fa-trash"></i></button>
								${corregir}
							</div>
						</div>
					</div>
					`;
					$('#producto-'+producto).append(lista);
					if($('#producto-'+producto).find('.fa-sync').length == 0){
						$('#agregar-producto-'+producto).prop('disabled',false);
					}
				}
			});
		}
		$('#cantidad-producto-'+producto).val(series.length);
	});
// validacion serie input


// end validacion serie input
	$('#TableAlmacenInventarioInicial tbody').on('click', '.corregir-duplicado', function (event) {
		var button = $(this);
		var producto = $(this).data('producto');
		var identificador = $(this).data('serie');
		var serie = $(this).parent().parent().find('input').val();
		
		$.post(path+"administrador/reginventarioinicial/verificarSerieUnico", {serie},
			function (data, textStatus, jqXHR) {
				if(data.success){
					button.remove();
					$('#duplicado-'+identificador).remove();
					if($('#producto-'+producto).find('.fa-sync').length == 0){
						$('#agregar-producto-'+producto).prop('disabled',false);
					}
					Swal.fire({
						title: "Corregido",
						text: "Serie verificada correctamente.",
						type: "success"
					});
				}else{
					Swal.fire({
						title: "Error",
						text: "No se reparo la duplicidad",
						type: "error"
					});
				}
			},
			"JSON"
		);
		});
	$('#TableAlmacenInventarioInicial tbody').on('click', '.eliminar-serie', function (event) {
		var producto = $(this).data('producto');		
		$(this).parent().parent().parent().remove();
		var cant = $('input[name^="producto-'+producto+'"]').length;
		$('#cantidad-producto-'+producto).val(cant);
	});

	$('#TableAlmacenInventarioInicial tbody').on('click', '.obtener-series', function (event) {
		var producto = $(this).data('producto');
		var almacen = $(this).data('almacen');
		$('#ModalInventarioSeries').modal();
		$('#TableInventarioSeries tbody').html('');
		$.getJSON(path+"administrador/reginventarioinicial/getSeriesInventario",{producto,almacen},
			function (data, textStatus, jqXHR) {
				var tr = '';
				$.each(data, function (index, value) { 
					tr += `
						<tr>
							<td>${value.serie_descripcion}</td>
							<td>${(value.serie_estado=='D')?'Disponible':'Vendido'}</td>
						</tr>
					`;
				});
				$('#TableInventarioSeries tbody').html(tr);
			}
		);
	});

	$('#FormAlmacenInventarioInicialFiltro').validate({
		submitHandler: function () {
			$('#TableAlmacenInventarioInicial').DataTable().ajax.reload();
		}
	});
	/*=====  End of INVENTARIO INICIAL  ======*/


	/*===============================
	=            COMPRAS            =
	===============================*/
	var TableCompras = $('#TableCompras').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcompras/jsonCompras',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.almacen = $("select[name=almacen]").val();
				d.proveedor = $("input[name=proveedor]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [
			{ "orderable": false, "className": 'details-control' },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
		]
	});

	$('#TableCompras tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = TableCompras.row(tr);

		if (row.child.isShown()) {
			row.child.hide();
			tr.removeClass('shown');
			$(this).find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
		} else {
			row.child(formatCompraDetalle(row.data())).show();
			tr.addClass('shown');
			$(this).find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
		}
	});

	function formatCompraDetalle(d) {
		var query = jQuery.parseJSON(d[13]);

		var table = `
	
		<table class="table table-bordered">
			<thead>
				<tr class="table-danger">
					<th style="text-align: center;">Cod</th>
					<th style="text-align: center;">Artículo</th>
					<th style="text-align: center;">Marca</th>
					<th style="text-align: center;">Unidad</th>
					<th style="text-align: center;">Cant.</th>
					<th style="text-align: center;">P. Unit</th>
					<th style="text-align: center;">IGV</th>
					<th style="text-align: center;">P. Venta</th>
					<th style="text-align: center;">Subtotal</th>
				</tr>
			</thead>
			<tbody>
		`;
		var tr = '';

		$.each(query, function (index, val) {
			tr += `
			<tr>
				<td>${val.cod_producto}</td>
				<td>${val.nomb_product}</td>
				<td>${val.nomb_marca}</td>
				<td>${val.abreviatura_unid}</td>
				<td>${val.cant_compdet}</td>
				<td>${val.precunit_compdet}</td>
				<td>${val.igv_compdet}</td>
				<td>${val.precventa_compdet}</td>
				<td>${val.subtotal_compdet}</td>
			</tr>
		 `;
		});

		table += tr;
		table += `
			</tbody>
		</table>`;
		return table;
	}


	$('#FormComprasFiltro').validate({
		ignore: [],
		rules: {
			desde: { required: true },
			hasta: { required: true },
			estado: { required: true }
		},
		submitHandler: function () {
			$('#TableCompras').DataTable().ajax.reload();
		}
	});

	$('#ComprasReportePdf').click(function (event) {
		let form = $('#FormComprasFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regcompras/reportePdf?' + params);
	});

	$('#ComprasReporteExcel').click(function (event) {
		let form = $('#FormComprasFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regcompras/reporteExcel?' + params);
	});


	$('#TableCompras').on('click', '.anular', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular compra?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regcompras/anularCompra', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableCompras').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	$("#ProveedorAutocomplete").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			return path + "administrador/regcompras/getProveedores?q=" + query
		},
		getValue: function (element) {
			return element.nombre;
		},
		list: {
			onSelectItemEvent: function () {
				var ruc = $("#ProveedorAutocomplete").getSelectedItemData().ruc;
				var id = $("#ProveedorAutocomplete").getSelectedItemData().id;
				$("#RUCAutocomplete").val(ruc);
				$('input[name=proveedor]').val(id);
			},
			onClickEvent: function () {
			}
		}
	});	
	// $('#FormComprarAgregarProveedor').validate({
	// 	ignore: [],
	// 	rules: {
	// 		tipo: { required: true },
	// 		nombre: { required: true },
	// 		documento: { required: true
	// 		// remote:{
	// 		// 	url: path+"administrador/regcliente/validaProveedorUnico",
	// 		// 	type: "POST",
	// 		// 	data: {
	// 		// 		documento: function() {
	// 		// 			return $("#FormComprarAgregarProveedor input[name=documento]").val();
	// 		// 		},
	// 		// 		id: function(){
	// 		// 			return $("input[name=codigo]").val();
	// 		// 		}
	// 		// 	}
	// 		// }
	//  },
	// 		// telefono: { required: true },
	// 		direccion: { required: true }
	// 				},
	// 	// messages:{
	// 	// documento:{
	// 	// 	remote:'Este número de documento ya existe'					

	// 	// }

	// 	// },
	// 	submitHandler: function () {
	// 		enviarFormulario('#FormComprarAgregarProveedor', function (json) {
	// 			// if (json.success) {
	// 			// 	$('#TableListarClientes').DataTable().ajax.reload();
	// 			// }
	// 			$('#ModalAgregarProveedor').modal('hide');
	// 			$('#FormComprarAgregarProveedor select[name=tipo]').select('val', '');
	// 			$('#FormComprarAgregarProveedor input[name=documento]').val('');
	// 			$('input[name=cliente]').val(json.proveedor.tb_proveedor_id);
	// 			$('#RUCAutocomplete').val(json.proveedor.tb_proveedor_doc);
	// 			$('#ProveedorAutocomplete').val(json.proveedor.tb_proveedor_nom);				
	// 			// $('#DireccionCliente').val(json.cliente.tb_proveedor_dir);
	// 			// $('input[name=precioCliente]').val(json.cliente.precio_cliente);									
	// 		})
	// 	}
	// });


	$("#nombreProductoAutocomplete").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			return path + "administrador/regcompras/getProductoBusqueda?producto=" + query
		},
		getValue: function (element) {
			return element.nombre;
		},
		requestDelay: 500,
		list: {
			onSelectItemEvent: function () {
				var selectedItemValue = $("#nombreProductoAutocomplete").getSelectedItemData();
				$('input[name=unidadProducto]').val(selectedItemValue.unidad);
				$('input[name=precioProducto]').val(selectedItemValue.costo);
				$('input[name=producto]').val(selectedItemValue.id);
			},
			
		}
	});



	$('#FormComprasAgregar').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			documento: { required: true },
			numDocumento: { required: true },
			rucdni: { required: true },
			nombreProveedor: { required: true },
			efectivo: { required: true, number: true }
		},
		submitHandler: function () {
			if ($('#TableComprasProductos tbody tr').length == 0) {
				$('#FormComprasAgregarProducto').valid();
				return;
			}

			var efectivo = parseFloat($('input[name=efectivo]').val());
			var credito = parseFloat($('input[name=credito]').val());
			var total = parseFloat($('#CompraTotal').text());
			if ((efectivo + credito) < total) {
				Swal.fire({
					title: "Error",
					text: "La suma de pagos no es igual al monto total de venta.",
					type: "error"
				});
				return;
			}

			var compras = $('#FormComprasAgregar').serializeObject();
			var productos = $('#FormComprasAgregarProducto').serializeObject();
			jQuery.extend(compras, productos);

			$.ajax({
				url: path + 'administrador/regcompras/agregarCompra',
				type: 'POST',
				dataType: 'JSON',
				data: compras
			})
				.done(function (resp) {
					if (resp.success) {
						window.location.href = path + 'administrador/regcompras';
					}
				});
		}
	});

	
	$('#FormComprasAgregarProducto').validate({
		ignore: [],
		rules: {
			producto: { required: true },
			nombreProducto: { required: true },
			cantidadProducto: { required: true, number: true }
		},
		submitHandler: function () {
			if(!$('input[name=seriesProducto]').prop('checked')){
				guardarProducto();
			}else{
				var series = [];
				var producto = $('input[name=producto]').val();
				if (producto == ''){
					return;
				}
	
				var cantidad = parseInt($('#FormComprasAgregarProducto input[name=cantidadProducto]').val());
				$('#FormSeriesVerificar input[name=prodseri]').val($('input[name=producto]').val());
				$('#FormSeriesVerificar input[name=almseri]').val($('select[name=almacen]').val());
				$('#ModalSeries .modal-body .inputSeries').html('');
				for (i = 1; i <= cantidad; i++) {
					var serie = `
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Serie ${i}</label>
							<input type="text" name="serie[${i}]" class="form-control" validate>
						</div>
					</div>
					`;
					$('#ModalSeries .modal-body .inputSeries').append(serie);
					$('#FormSeriesVerificar input[name="serie['+i+']"]').rules("add", "required");
				}
				$('#ModalSeries').modal();
			}
		}
	});

	$('#FormSeriesVerificar').validate({
		ignore: [],
		rules: {
			producto: { required: true },
			almacen: { required: true },
		},
		submitHandler: function () {
			var array_series = [];
			$('#FormSeriesVerificar input[name^="serie"]').each(function(index,elem) {
				if($(elem).val()!=''){
					array_series.push($(elem).val());
				}
			});

			var duplicados = buscar_duplicado_array(array_series);
			if(duplicados.length > 0){
				Swal.fire({
					title: "Error",
					text: "Las series "+duplicados.toString()+" son duplicados",
					type: "error"
				});
				return;
			}

			$('#FormSeriesVerificar button[type=submit]').prop('disabled',true).html('Procesando');
			guardarProducto();
		}
	});


	$('#FormSeriesVerificar').on('focusout','input[name^="serie"]', function () {
		var $this = $(this);
		var serie = $this.val();
		var producto = $('#FormSeriesVerificar input[name=prodseri]').val();
		
		$.post(path+'administrador/regcompras/verificaSerie', {serie,producto},
			function (data, textStatus, jqXHR) {
				if(!data.success){
					Swal.fire({
						title: "Error",
						text: "La serie "+serie+" del producto: "+data.producto+" ya fue ingresada en la compra: "+data.compra,
						type: "error"
					});
					$this.val('');
					return;
				}
			},
			"JSON"
		);
	});


var id_array = [];
function guardarProducto()
{
	var producto = $('#FormComprasAgregarProducto input[name=producto]').val();
	var cantidad = $('#FormComprasAgregarProducto input[name=cantidadProducto]').val();
	var series = null;
	if($('input[name=seriesProducto]').prop('checked')){
		var producto = $('#FormSeriesVerificar input[name=prodseri]').val();
		var series = $('#FormSeriesVerificar input[name^="serie"]');
		var cantidad = series.length;
	}

	$.getJSON(path + 'administrador/regcompras/getProducto', { producto }, function (resp, textStatus) {
		
		if ($('#TableComprasProductos tbody tr').length > 0) {
			$('#TableComprasProductos tr').each(function () {
				var data_id = parseInt($(this).data('id'));
				id_array.push(data_id);
			});
			if (id_array.includes(parseInt(resp.cod_producto))) {
				$('#prod-' + resp.cod_producto).next().remove();
				$('#prod-' + resp.cod_producto).remove();
			}
		}
		
		var tr = `
				<tr class="fila-producto hide" id="prod-${resp.cod_producto}" data-id="${resp.cod_producto}">
					<input type="hidden" name="id_prod[]" value="${resp.cod_producto}"/>
					<td class="details-control">
					${(series!=null)?'<button type="button" class="btn btn-icon waves-effect waves-light btn-success"><span class="fa fa-caret-right"></span></button>':''}
						
					</td>
					<td>${resp.cod_producto}</td>
					<td>${resp.nomb_product}</td>
					<td>${resp.nomb_marca}</td>
					<td>${resp.nomb_unid}</td>
					<td style="width:120px;"><input type="${(series==null?'number':'hidden')}" class="cant form-control" name="cant_prod[]" value="${cantidad}"  />${(series!=null)?cantidad:''}</td>
					<td style="width:120px;"><input type="text" class="prec form-control" name="prec_prod[]" value="${($('input[name=precioProducto]').val())}"></td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<div class="btn-group btn-group-justified m-b-10">
							<button data-id="${resp.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
						</div>
					</td>
				</tr>
		`;
		
		if(series!=null){
			var trSeries = '';
			$.each(series, function (index, elem) {
				trSeries += `
					<tr>
						<input type="hidden" name="series[${producto}][]" value="${$(elem).val()}"/>
						<td>${$(elem).val()}</td>
						<td><button data-producto="${producto}" type="button" class="btn btn-danger btn-sm delete-serie"><i class="fa fa-trash"></i></button></td>
					</tr>
				`;
			});
			
	
			tr += `
				<tr class="fila-detalle" style="display:none">
					<td colspan="4">
						<table id="table-serie-producto-${producto}" class="table table-bordered">
							<thead>
								<tr class="table-warning">
									<th>Serie</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								${ trSeries }
							</tbody>
							<tfoot>
								<tr>
									<td><input type="text" name="val-agregar-${producto}" class="form-control" /></td>
									<td><button type="button" data-producto="${producto}" class="agregar-serie btn btn-sm btn-info">Agregar</button></td>
								</tr>
							</tfoot>
						<table>
					</td>
				</tr>
			`;
		}

		if($('#TableComprasProductos tbody>tr.fila-producto')[0] == undefined){
			$('#TableComprasProductos tbody').append(tr);
		}else{
			if($('#TableComprasProductos tbody .fila-producto').last().next()[0] != undefined){
				$($('#TableComprasProductos tbody .fila-producto').last().next()).after(tr);
			}else{
				$($('#TableComprasProductos tbody .fila-producto').last()).after(tr);
			}	
		}

		$('#FormComprasAgregarProducto')[0].reset();
		$('input[name=producto]').val('');
		
		calcularTotalCompra();
		$('#FormSeriesVerificar button[type=submit]').prop('disabled',false).html('<i class="fa fa-save"></i> Guardar');
		$('#ModalSeries').modal('hide');
	});
}

	$('#TableComprasProductos').on('click','.delete-serie', function () {
		var producto = $(this).data('producto');
		$(this).parent().parent().remove();
		var cantidad = $('#table-serie-producto-'+producto+' tbody input[name^="series"]').length;
		cantidad = parseInt(cantidad);
		var td = `
			<input type="hidden" class="cant" name="cant_prod[]" value="${cantidad}">${cantidad}
		`;
		$('#prod-'+producto+' td').eq(5).html(td);
		calcularTotalCompra();
	});

	$('#TableComprasProductos').on('click','.agregar-serie', function () {
		var producto = $(this).data('producto');
		var serie = $('#table-serie-producto-'+producto+' input[name=val-agregar-'+producto+']');
		
		if(serie.val()==''){
			Swal.fire({
				title: "Error",
				text: "La serie no puede ser vacia.",
				type: "error"
			});
			return;
		}
	
		var seriesElementos = $('#table-serie-producto-'+producto+' tbody input[name^="series"]');
		$.each(seriesElementos, function (indexSerie, elemSerie) { 

			if($(elemSerie).val()==serie.val()){
				Swal.fire({
					title: "Error",
					text: "No pueden haber duplicados",
					type: "error"
				});
				return;
			}
		});

		$.post(path+'administrador/regcompras/verificaSerie', {serie:serie.val(),producto},
			function (data, textStatus, jqXHR) {
				if(!data.success){
					Swal.fire({
						title: "Error",
						text: "La serie "+serie+" del producto: "+data.producto+" ya fue ingresada en la compra: "+data.compra,
						type: "error"
					});
					$this.val('');
					return;
				}else{
					var fila =`
						<tr>
							<input type="hidden" name="series[${producto}][]" value="${serie.val()}"/>
							<td>${serie.val()}</td>
							<td><button type="button" class="btn btn-danger btn-sm delete-serie"><i class="fa fa-trash"></i></button></td>
						</tr>
					`;
					serie.val('');
					$('#table-serie-producto-'+producto+' tbody').append(fila);
					var cantidad = $('#table-serie-producto-'+producto+' tbody input[name^="series"]').length;
					$('#prod-'+producto+' td').eq(5).html(`<input type="hidden" class="cant" name="cant_prod[]" value="${cantidad}">${cantidad}`);
					calcularTotalCompra();
				}
			},
			"JSON"
		);
		
		
	});

	$('#TableComprasProductos tbody').on('click', '.removerProducto', function (event) {
		event.preventDefault();
		if($('#prod-' + $(this).data('id')).next('.fila-detalle')[0]!=undefined){
			$('#prod-' + $(this).data('id')).next().remove();
		}
		$('#prod-' + $(this).data('id')).remove();
		calcularTotalCompra();
	});

	$("#TableComprasProductos").on('focusout', 'input[name="cant_prod[]"], input[name="prec_prod[]"]', function () {
		calcularTotalCompra();
	});

	$('#TableComprasProductos').on('focusout', '.prec', function (event) {
		event.preventDefault();
		calcularTotalCompra();
	});


	$('#TableComprasProductos tbody').on('click','.details-control', function () {
		var fila = $(this).parent();
		if(fila.hasClass('hide')){
			fila.find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
			fila.removeClass('hide').addClass('show');
			fila.next().show();
		}else{
			fila.find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
			fila.removeClass('show').addClass('hide');
			fila.next().hide();
		}
	});

	function calcularTotalCompra() {
		var total = 0;
		var igv = 18;
		var igv_porcentaje = (igv / 100);
		if ($('#TableComprasProductos tbody tr.fila-producto').length > 0){
			$('#TableComprasProductos tbody tr.fila-producto').each(function () {
				var cant = parseFloat($(this).find('.cant').val());
				
				if(isNaN(cant)){
					cant = parseFloat($(this).find('.cant').text());
				}
				let prec = parseFloat($(this).find('.prec').val());
				var subTotalProd = round((cant * prec), 2);
				var igvProd = subTotalProd / (igv_porcentaje + 1);
				igvProd = round(igvProd * igv_porcentaje, 2);
				let valorVentaProd = round(subTotalProd - igvProd, 2);
				$(this).find('td').eq(7).html(igvProd);
				$(this).find('td').eq(8).html(valorVentaProd);
				$(this).find('td').eq(9).html(subTotalProd);

				total += parseFloat(subTotalProd);
			});
		
			var IGV = total / (igv_porcentaje + 1);
			IGV = round(IGV * igv_porcentaje, 2);
			let valorVenta = round(total - IGV, 2);

			$('#CompraValorVenta').html(valorVenta);
			$('#CompraIGV').html(IGV);
			$('#CompraTotal').html(round(total, 2));
			$('input[name=efectivo]').val(round(total, 2));
		}
	}


	$('#FormComprasAgregar select[name=pago]').change(function (event) {
		var tipo = $(this).val();
		if (tipo == 'CO') {
			var total = $('#CompraTotal').html();
			$('input[name=dias]').prop('disabled', true).parent().parent().hide();
			$('input[name=fecVenc]').parent().parent().hide();
			$('input[name=credito]').parent().parent().hide();
			$('input[name=efectivo]').val(total).prop('readonly', true);
		} else {
			$('input[name=dias]').prop('disabled', false).parent().parent().show();
			$('input[name=fecVenc]').parent().parent().show();
			$('input[name=credito]').parent().parent().show();
			$('input[name=efectivo]').val(0).prop('readonly', false);
		}
	});

	$('#FormComprasAgregar input[name=efectivo]').focusout(function (event) {
		var efectivo = parseFloat($(this).val());
		if (isNaN(efectivo)) {
			return;
		}
		var total = parseFloat($('#CompraTotal').text());
		var credito = parseFloat($('input[name=credito]').val());
		$('#ComprasDiferencia').text(round(total - efectivo - credito, 2));
		if (efectivo > total) {
			$(this).val(total);
			Swal.fire({
				title: "Error",
				text: "El efectivo no puede ser mayor que " + total,
				type: "error",
			});
		}
	});

	$('#FormComprasAgregar input[name=dias]').focusout(function (event) {
		var dias = parseInt($(this).val()) + 1;
		var fecha = $('input[name=fecha]').val();
		fecha = new Date(fecha);
		fecha.setDate(fecha.getDate() + dias);
		fecha = moment(fecha).format("YYYY-MM-DD");
		$('input[name=fecVenc]').val(fecha);
	});

	$('input[name=credito]').focusout(function (event) {
		var credito = parseFloat($(this).val());
		var efectivo = parseFloat($('input[name=efectivo]').val());
		var total = parseFloat($('#CompraTotal').text());


		$('#ComprasDiferencia').text(round(total - efectivo - credito, 2));
		if ((credito + efectivo) > total) {
			var monto = total - efectivo;
			$(this).val(monto);
			Swal.fire({
				title: "Error",
				text: "El monto no puede ser mayor que " + monto,
				type: "error",
			});
		}
	});

	$('#FormComprarAgregarProveedor').validate({
		ignore: [],
		rules: {
			nombre: { required: true },
			documento: { required: true },
			telefono: { required: true }

		},
		submitHandler: function () {
			enviarFormulario('#FormComprarAgregarProveedor', function (json) {
				if (json.success) {
					$('#ModalAgregarProveedor').modal('hide');
					$('#FormComprarAgregarProveedor')[0].reset();
				}
			})
		}
	});

	$('#CompraEditarProveedor').click(function (event) {
		var id = $('input[name=proveedor]').val();
		if (id == '')
			return;

		$('#ModalEditarProveedor').modal();
		$.getJSON(path + 'administrador/regcompras/getProveedor', { id }, function (json, textStatus) {
			$('#FormComprarEditarProveedor input[name=id]').val(json.tb_proveedor_id);
			$('#FormComprarEditarProveedor input[name=nombre]').val(json.tb_proveedor_nom);
			$('#FormComprarEditarProveedor input[name=documento]').val(json.tb_proveedor_doc);
			$('#FormComprarEditarProveedor input[name=telefono]').val(json.tb_proveedor_tel);
			$('#FormComprarEditarProveedor input[name=direccion]').val(json.tb_proveedor_dir);
			$('#FormComprarEditarProveedor input[name=email]').val(json.tb_proveedor_ema);
		});


	});


	$('#FormComprarEditarProveedor').validate({
		ignore: [],
		rules: {
			id: { required: true },
			nombre: { required: true },
			documento: { required: true },
			telefono: { required: true }

		},
		submitHandler: function () {
			enviarFormulario('#FormComprarEditarProveedor', function (json) {
				if (json.success) {
					$('#ModalEditarProveedor').modal('hide');
					$('#FormComprarEditarProveedor')[0].reset();
				}
			})
		}
	});

	$('#FormComprasEditar').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			documento: { required: true },
			numDocumento: { required: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormComprasEditar', () => {

			})
		}
	});
	/*=====  End of COMPRAS  ======*/


	/*==================================
	=            COTIZACION            =
	==================================*/
	var TableCotizacion = $('#TableCotizacion').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcotizacion/jsonCotizacion',
			"type": "GET",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.estado = $("select[name=estado]").val();
				d.pago = $("select[name=pago]").val();
			}
		},
		"columns": [
			{ "orderable": false, "className": 'details-control' },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false }
		],
		"columnDefs": [
			{ "width": "5%", "targets": 0 }
		]
	});

	$('#TableCotizacion tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = TableCotizacion.row(tr);

		if (row.child.isShown()) {
			row.child.hide();
			tr.removeClass('shown');
			$(this).find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
		} else {
			row.child(formatCotizacionDetalle(row.data())).show();
			tr.addClass('shown');
			$(this).find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
		}
	});

	function formatCotizacionDetalle(d) {
		var query = jQuery.parseJSON(d[9]);

		var table = `
	
		<table class="table table-bordered">
			<thead>
				<tr class="table-danger">
					<th>Cod</th>
					<th>Artículo</th>
					<th>Marca</th>
					<th>Unidad</th>
					<th>Cant.</th>
					<th>P. Unit</th>
					<th>Desc.</th>
					<th>IGV</th>
					<th>Precio</th>
					<th>Subtotal</th>
				</tr>
			</thead>
			<tbody>
		`;
		var tr = '';
		$.each(query, function (index, val) {
			tr += `
			<tr>
				<td>${val.cod_cot}</td>
				<td>${val.nomb_product}</td>
				<td>${val.nomb_marca}</td>
				<td>${val.abreviatura_unid}</td>
				<td>${val.cant_cotdet}</td>
				<td>${val.precunit_cotdet}</td>
				<td>${val.descuento_cotdet}</td>
				<td>${val.igv_cotdet}</td>
				<td>${val.prec_cotdet}</td>
				<td>${val.subtotal_cotdet}</td>
			</tr>
		 `;
		});

		table += tr;
		table += `
			</tbody>
		</table>`;
		return table;
	}
		var id_array = [];

	$('#TableCotizacion').on('click','.procesar-cotizacion-venta',function(){
		var id = $(this).data('id');
		$('#ModalProcesarCotizacion').modal();
		$.getJSON(path+"administrador/regcotizacion/getCotizar", {id},
		function (data, textStatus, jqXHR) {
			$('input[name=moneda]').val(data.cotizacion.moneda_cat);
			$('input[name=cliente]').val(data.cotizacion.id_cliente);
			$('input[name=cliente_nombre]').val(data.cotizacion.nomb_cliente);
			$('input[name=ruc_dni]').val(data.cotizacion.doc_cliente);
			$('input[name=precioCliente]').val(data.cotizacion.precio_cliente);
			$('input[name=tipoCambio]').val(data.cotizacion.cambio_cat);


			var optionDocumentos = '<option value="">Seleccione</option>';
			$.each(data.documentos, function (index, value) { 
				optionDocumentos += `<option value="${value.cod_talonario}" >${value.nom_tipdocumento}</option>`;
			});
			$('#FormProcesarCotizacion select[name=tipo_documento]').html(optionDocumentos);

			var tr = '';
			$.each(data.cotizacion.detalle, function (index, value) {
				id_array.push(value.cod_producto);

				var producto = value.cod_producto;
				tr += `
									<tr class="fila-producto hide" id="prod-${producto}" data-id="${producto}">
									<input type="hidden" name="id_prod[${producto}]" value="${producto}"/>
									<input type="hidden" name="id_almacen[${producto}]" value=""/>
									<input type="hidden" name="unidad_prod[${producto}]" value="${value.nomb_unid}" />
									<input type="hidden" name="peso_prod[${producto}]" value="${value.peso_product}" />
									<td class="details-control">
										
									</td>
									<td>${value.cod_producto}</td>
									<td><input name="nombre_prod[${producto}]" class="form-control" value="${value.nomb_product}"></td>									
									<td>${value.nomb_marca}</td>
									<td>${value.nomb_unid}</td>
									<td>
										<input min="1" type="number" class="cant form-control" name="cant_prod[${producto}]" value="${value.cant_cotdet}" />
									</td>
									<td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[${producto}]" value="${value.precunit_cotdet}"></td>
									<td>
										<input type="hidden" class="desc" name="desc_prod[${producto}]" value="${value.descuento_cotdet}" />
										${value.descuento_cotdet}
									</td>
									<td></td>
									<td></td>
									<td></td>
									<td>
										<div class="btn-group btn-group-justified m-b-10">
                      <button data-id="${value.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></
                      button>
										</div>
									</td>
								</tr>
				`;
			});
			$('#TableProcesarCotizacionProductos tbody').html(tr);
			calcularTotalVentaCotizacion();
		});
	});

	
	$('#FormCotizacionProcesarAgregarProducto').validate({
		ignore: [],
		rules: {
			producto: { required: true },
			nombreProducto: { required: true },
			'seriesProducto[]':{required:true},
			precioProducto:{required:true,decimal:true},
			cantidadProducto:{required:true,number:true}

		},
		submitHandler: function () {
			if (!$('#FormProcesarCotizacion').valid()) {
				return;
			}

			if($('#servicioCheck').prop('checked')){
				var servicioCheckBox = true;
			}else{
				var servicioCheckBox = false;
      }
      
			if($('input[name=serieCheckProducto]').prop('checked')){
				var seriesCheckBox = true;
			}else{
				var seriesCheckBox = false;
			}
			var producto = $('input[name=producto]').val();
			if (producto == '')
				return;
			var almacen = $('select[name=almacen]').val();
			var cantidad = $('#select2-series').find(':selected').length;
			if(!seriesCheckBox){
				var cantidad = $('input[name=cantidadProducto]').val();
			}
			var cambio = $('input[name=tipoCambio]').val();
			var seriesSelect = [];
			$('#select2-series').find(':selected').map(function(index,elem){
				seriesSelect.push($(elem).val());
			});
			
			var unidad = $('input[name=unidadProducto]').val();
			var peso = $('input[name=pesoProducto]').val();
			
      $('#FormCotizacionProcesarAgregarProducto button[type=submit]').prop('disabled',true);
      
      if(servicioCheckBox==false){
        $.getJSON(path + 'administrador/regventas/getProducto', { series:seriesSelect,producto, cantidad, cambio, almacen }, function (resp) {
          if (resp.tipo == 1 && !resp.estado) {
            Swal.fire({
              title: "Error",
              text: "El stock máximo disponible es " + resp.response.stock_disponible,
              type: "error",
            });
            return;
          }
          if ($('#TableProcesarCotizacionProductos tbody tr.fila-producto').length > 0) {
            $('#TableProcesarCotizacionProductos tr.fila-producto').each(function () {
              var data_id = parseInt($(this).data('id'));
              id_array.push(data_id);
            });
            if (id_array.includes(parseInt(resp.response.cod_producto))) {
							if($('#prod-' + resp.response.cod_producto).next('.fila-detalle')[0]!=undefined){
								$('#prod-' + resp.response.cod_producto).next().remove();
              }
              $('#prod-' + resp.response.cod_producto).remove();
            }
          }
					
					var almacen = $('select[name=almacen]').val();
          var tr = `
          <tr class="fila-producto hide" id="prod-${producto}" data-id="${producto}">
            <input type="hidden" name="id_prod[${producto}]" value="${producto}"/>
						<input type="hidden" name="id_almacen[${almacen}]" value="${almacen}"/>
						<input type="hidden" name="unidad_prod[${producto}]" value="${unidad}" />
						<input type="hidden" name="peso_prod[${producto}]" value="${peso}" />
            <td class="details-control">
              ${(seriesCheckBox)?'<button type="button" class="btn btn-icon waves-effect waves-light btn-success"><span class="fa fa-caret-right"></span></button>':''}
            </td>
            <td>${resp.response.cod_producto}</td>
            <td><input name="nombre_prod[${producto}]" class="form-control" value="${resp.response.nomb_product}"></td>
						<td>${resp.response.nomb_marca}</td>
            <td>${resp.response.nomb_unid}</td>
            <td style="width:110px"><input min="1" type="${(seriesCheckBox)?'hidden':'number'}" class="cant form-control" name="cant_prod[${producto}]" value="${cantidad}" />${(seriesCheckBox)?cantidad:''}</td>
            <td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[${producto}]" value="${round($('input[name=precioProducto]').val(), 2)}"></td>
            <td>
              <input type="hidden" class="desc" name="desc_prod[${producto}]" value="${ $('input[name=descuentoProducto]').val()}" />
              ${round($('input[name=descuentoProducto]').val(), 2)}
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <div class="btn-group btn-group-justified m-b-10">
                <button data-id="${resp.response.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
              </div>
            </td>
          </tr>
          `;
          if(seriesCheckBox){
            tr += `
            <tr class="fila-detalle" style="display:none">
            <td></td>
            <td><b>Series:</b></td>
            <td colspan="8">
            <select data-producto="${producto}" id="serieprod-${producto}" name="series[${producto}]" class="series-producto form-control" multiple="multiple"></select>
            </td>
            <td colspan="8"></td>
            </tr>
            `;
          }
  
          if($('#TableProcesarCotizacionProductos tbody>tr.fila-producto')[0] == undefined){
            $('#TableProcesarCotizacionProductos tbody').append(tr);
          }else{
            if($('#TableProcesarCotizacionProductos tbody .fila-producto').last().next()[0] != undefined){
              $($('#TableProcesarCotizacionProductos tbody .fila-producto').last().next()).after(tr);
            }else{
              $($('#TableProcesarCotizacionProductos tbody .fila-producto').last()).after(tr);
            }
          }
  
          var dataSeries = resp.series;
          $('#serieprod-'+producto).select2({
            data:dataSeries,
            multiple:true
          });
          
          setTimeout(() => {
            $.each($('.series-producto'), function (index, element) {
              var select = $(element);
              var producto = select.data('producto');
              var array = [];					
              var choice = $(element).next().find('.select2-selection__choice');					
              $.each(choice,function(indexLi,elementLi){
                var title = $(this).attr('title');
                array.push(title);
              })
              $('#serieprod-'+producto).val(array);
            });
          }, 1000);
        
  
          $('#FormCotizacionProcesarAgregarProducto')[0].reset();
          $('input[name=producto]').val('');
					$('#select2-series').empty().trigger("change");
					$('#nombre-servicio').prop('disabled',true).hide();
					$('#VentaProductoAutocomplete').prop('disabled',false).show();
					$('#FormCotizacionProcesarAgregarProducto input[name=producto]').val('');
          $('#FormCotizacionProcesarAgregarProducto button[type=submit]').prop('disabled',false);
          calcularTotalVentaCotizacion();
        });
      }else{
				var random = Math.floor(Math.random() * (100 - 10) + 10);
				producto = 'ser-'+$('#nombre-servicio').val().trim().substr(0,3)+$('#nombre-servicio').val().trim().substr(-3);
        var tr = `
        <tr class="fila-producto hide" id="prod-${producto}" data-id="${producto}">
          <input type="hidden" name="id_prod[${producto}]" value="${producto}"/>
					<input type="hidden" name="id_almacen[${almacen}]" value="${almacen}"/>
					<input type="hidden" name="unidad_prod[${producto}]" value="${unidad}" />
					<input type="hidden" name="peso_prod[${producto}]" value="${peso}" />					
          <td class="details-control"></td>
          <td>${producto}</td>
          <td><input name="nombre_prod[${producto}]" class="form-control" value="${$('#nombre-servicio').val()}"></td>
					<td></td>
					<input name="unidad_prod[${producto}]" value="${unidad}">
					<input name="peso_prod[${producto}]" value="${peso}">
          <td>${unidad}</td>
          <td style="width:110px"><input min="1" type="number" class="cant form-control" name="cant_prod[${producto}]" value="${cantidad}" /></td>
          <td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[${producto}]" value="${round($('input[name=precioProducto]').val(), 2)}"></td>
          <td>
            <input type="hidden" class="desc" name="desc_prod[${producto}]" value="${ $('input[name=descuentoProducto]').val()}" />
            ${round($('input[name=descuentoProducto]').val(), 2)}
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td>
            <div class="btn-group btn-group-justified m-b-10">
              <button data-id="${producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
            </div>
          </td>
				</tr>`;
				
				if($('#TableProcesarCotizacionProductos tbody>tr.fila-producto')[0] == undefined){
					$('#TableProcesarCotizacionProductos tbody').append(tr);
				}else{
					if($('#TableProcesarCotizacionProductos tbody .fila-producto').last().next()[0] != undefined){
						$($('#TableProcesarCotizacionProductos tbody .fila-producto').last().next()).after(tr);
					}else{
						$($('#TableProcesarCotizacionProductos tbody .fila-producto').last()).after(tr);
					}
				}
				$('#FormCotizacionProcesarAgregarProducto')[0].reset();
				$('input[name=producto]').val('');
				$('#select2-series').empty().trigger("change");
				$('#FormCotizacionProcesarAgregarProducto button[type=submit]').prop('disabled',false);

				$('#nombre-servicio').prop('disabled',true).hide();
				$('#VentaProductoAutocomplete').prop('disabled',false).show();
				$('#FormCotizacionProcesarAgregarProducto input[name=producto]').val('');
				calcularTotalVentaCotizacion();
      }
		}
	});

	$('#TableProcesarCotizacionProductos tbody').on('click','.details-control', function () {
		var fila = $(this).parent();
		if(fila.hasClass('hide')){
			fila.find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
			fila.removeClass('hide').addClass('show');
			fila.next().show();
		}else{
			fila.find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
			fila.removeClass('show').addClass('hide');
			fila.next().hide();
		}
	});

	$('#TableProcesarCotizacionProductos').on('change','.series-producto', function () {
		var producto = $(this).data('producto');
		var cantidad = $('#serieprod-'+producto).find(':selected').length;
		var td = `
		<input type="hidden" class="cant" name="cant_prod[${producto}]" value="${cantidad}" />${cantidad}
		`;
		$('#prod-'+producto+' td').eq(5).html(td);
		calcularTotalVentaCotizacion();
	});

	$('#TableProcesarCotizacionProductos tbody').on('click', '.removerProducto', function (event) {
		event.preventDefault();
		if($('#prod-' + $(this).data('id')).next('.fila-detalle')[0]!=undefined){
			$('#prod-' + $(this).data('id')).next().remove();
		}
		$('#prod-' + $(this).data('id')).remove();
		calcularTotalVentaCotizacion();
	});

	$("#TableProcesarCotizacionProductos").on('focusout', 'input[name="cant_prod[]"], input[name="prec_prod[]"]', function () {
		calcularTotalVentaCotizacion();
	});

	$('#TableProcesarCotizacionProductos').on('focusout', '.cant', function (event) {
		event.preventDefault();
		calcularTotalVentaCotizacion();
	});

	$('#TableProcesarCotizacionProductos').on('focusout', '.prec', function (event) {
		event.preventDefault();
		calcularTotalVentaCotizacion();
	});

	$('#FormProcesarCotizacion select[name=pago]').change(function (event) {
		var total = parseFloat($('#VentaTotal').html());
		if ($(this).val() == 'CRE') {
			$('input[name=dias]').prop('disabled', false).parent().parent().show();
			$('input[name=fecVenc]').parent().parent().show();
			$('input[name=saldo]').parent().parent().show();
			$('input[name=saldo]').val(total);
			$('input[name=monto]').val(0).prop('readonly', false);
		} else {
			$('input[name=dias]').prop('disabled', true).parent().parent().hide();
			$('input[name=fecVenc]').parent().parent().hide();
			$('input[name=saldo]').parent().parent().hide();
			$('input[name=saldo]').val(0);
			$('input[name=monto]').val(total).prop('readonly', true);
		}
	});

	function calcularTotalVentaCotizacion() {
		var total = 0;
		var igv = 18;
		var igv_porcentaje = (igv / 100);
		if ($('#TableProcesarCotizacionProductos tbody .fila-producto').length > 0)
			$('#TableProcesarCotizacionProductos tbody .fila-producto').each(function () {
				var cant = parseFloat($(this).find('.cant').val());
				
				if (isNaN(cant)) {
					var cant = parseFloat($(this).find('.cant').text());
				}
				var prec = parseFloat($(this).find('.prec').val());

				var descuento = parseFloat($(this).find('.desc').val());
				if (isNaN(descuento)) {
					descuento = 0;
				}

				prec -= descuento;
				var subTotalProd = round((cant * prec), 2);
				var igvProd = subTotalProd / (igv_porcentaje + 1);
				igvProd = round(igvProd * igv_porcentaje, 2);
				let valorVentaProd = round(subTotalProd - igvProd, 2);
				$(this).find('td').eq(8).html(igvProd);
				$(this).find('td').eq(9).html(valorVentaProd);

				$(this).find('td').eq(10).html(round(subTotalProd, 2));
				total += parseFloat(subTotalProd);
			});

		var IGV = total / (igv_porcentaje + 1);
		IGV = round(IGV * igv_porcentaje, 2);

		let valorVenta = round(total - IGV, 2);

		$('#VentaValorVenta').html(valorVenta);
		$('#VentaIGV').html(IGV);
		$('#VentaTotal').html(round(total, 2));
		$('input[name=monto]').val(round(total, 2));
		$('input[name=total]').val(round(total, 2));

	}


	$('#FormProcesarCotizacion').on('change','select[name=tipo_documento]', function () {
		var id = $(this).val();
		$.getJSON(path+"administrador/regcotizacion/numeracion", {id},
			function (data, textStatus, jqXHR) {
				$('input[name=serie]').val(data.serie);
				$('input[name=correlativo]').val(data.correlativo_actual);
			}
		);

	});

	$('#FormProcesarCotizacion input[name=monto]').focusout(function (event) {
		var total = parseFloat($('#VentaTotal').text());
		var monto = parseFloat($('input[name=monto]').val());
		if (monto > total) {
			$(this).val(total);
			Swal.fire({
				title: "Error",
				text: "El monto no puede ser mayor que " + total,
				type: "error",
			});
		}
		var saldo = total - monto;
		$('input[name=saldo]').val(saldo.toFixed(2));
	});

	$('#FormProcesarCotizacion input[name=dias]').focusout(function (event) {
		var dias = parseInt($(this).val()) + 1;
		var fecha = $('input[name=fecha]').val();
		fecha = new Date(fecha);
		fecha.setDate(fecha.getDate() + dias);
		fecha = moment(fecha).format("YYYY-MM-DD");
		$('input[name=fecVenc]').val(fecha);
	});

	$('#FormProcesarCotizacion input[name=montoRecibido]').focusout(function (event) {
		let montoRecibido = parseFloat($(this).val());
		let monto = parseFloat($('#FormProcesarCotizacion input[name=monto]').val());
		if (!isNaN(monto)) {
			if (montoRecibido > 0) {
				let vuelto = montoRecibido - monto;
				$('input[name=vuelto]').val(round(vuelto, 2));
			}
		}
	});

	$('#FormProcesarCotizacion select[name=tipoPago]').change(function (event) {
		var tipo = $(this).val();
		if (tipo == 2) {
			$('select[name=tipoTarjeta]').prop('disabled', false);
			$('input[name=operacion]').prop('disabled', false);
		} else {
			$('select[name=tipoTarjeta]').prop('disabled', true);
			$('input[name=operacion]').prop('disabled', true);
		}
	});

	$('#FormProcesarCotizacion').validate({
		ignore: [],
		rules: {
			tipo_documento: { required: true },
			nombreCliente: { required: true },
			monto: { required: true, number: true },
			dias: { required: true, number: true, min: 1 },
			montoRecibido: { required: true },
			nombreCliente:{required:true}
		},
		submitHandler: function () {
			if ($('#TableProcesarCotizacionProductos tbody tr').length == 0) {
				$('#FormCotizacionProcesarAgregarProducto').valid();
				return;
			}

			var monto = parseFloat($('input[name=monto]').val());
			var saldo = parseFloat($('input[name=saldo]').val());
			var total = parseFloat($('#VentaTotal').text());
			var montoRecibido = parseFloat($('input[name=montoRecibido]').val());
			if ((monto + saldo) != total) {
				Swal.fire({
					title: "Error",
					text: "La suma de pago y saldo no es igual al monto total de la venta.",
					type: "error"
				});
				return;
			}

			if (montoRecibido < monto) {
				Swal.fire({
					title: "Error",
					text: "El monto recibido no puede ser menor que el monto.",
					type: "error"
				});
				return;
			}

			var cotizacion = $('#FormProcesarCotizacion').serializeObject();
			var productos = $('#FormCotizacionProcesarAgregarProducto').serializeObject();
			jQuery.extend(cotizacion, productos);

			$.ajax({
				url: path + 'administrador/regcotizacion/procesarCotizacion',
				type: 'POST',
				dataType: 'JSON',
				data: cotizacion,
				beforeSend: function () {
					$('#CotizacionProcesarContenedorGuardar').find('button:submit').prop('disabled', true).html('Procesando');
				}
			})
				.done(function (resp) {
					if (resp.success) {
						$('#FormProcesarCotizacion')[0].reset();
						$('#FormProcesarCotizacion').removeClass('has-success');
						$('#FormProcesarCotizacion').find('.form-group').removeClass('has-success');
						$('#TableProcesarCotizacionProductos tbody').remove();
						$('#VentaValorVenta').html('00.00');
						$('#VentaIGV').html('00.00');
						$('#VentaTotal').html('00.00');

						$('#CotizacionProcesarContenedorGuardar').find('button:submit').prop('disabled', false).html('Guardar');
						$('#VentaImprimirA4').attr('href', path + 'administrador/regventas/imprimirVenta/' + resp.xml.archivo);
						$('#VentaImprimirTicket').attr('href', path + 'administrador/regventas/imprimirticketVenta/' + resp.xml.archivo);
						$('#ModalProcesarCotizacion').modal('hide');
						$('#ModalAccionesDespuesGuardar').modal();
					}
				});

		}
	});
	

	$('#CotizacionReportePdf').click(function (event) {
		let form = $('#FormCotizacionFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regcotizacion/reportePdf?' + params);
	});

	$('#CotizacionReporteExcel').click(function (event) {
		let form = $('#FormCotizacionFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regcotizacion/reporteExcel?' + params);
	});


	$('#FormCotizacionFiltro').validate({
		ignore: [],
		rules: {
			desde: { required: true },
			hasta: { required: true },
			estado: { required: true },
		},
		submitHandler: function () {
			$('#TableCotizacion').DataTable().ajax.reload();
		}
	});

	$("#ClienteAutocomplete").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			return path + "administrador/regcotizacion/getClientes?q=" + query
		},
		getValue: function (element) {
			return element.nombre;
		},
		list: {
			onSelectItemEvent: function () {
				var ruc = $("#ClienteAutocomplete").getSelectedItemData().ruc;
				var direccion = $("#ClienteAutocomplete").getSelectedItemData().direccion;
				var id = $("#ClienteAutocomplete").getSelectedItemData().id;
				var precio = $("#ClienteAutocomplete").getSelectedItemData().precio_cliente;
				$("#RUCAutocomplete").val(ruc);
				$("#DireccionCliente").val(direccion);
				$('input[name=cliente]').val(id);
				$('input[name=precioCliente]').val(precio);
			},
			onClickEvent: function () {
			}
		}
	});

	$("#CotizacionProductoAutocomplete").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			var cambio = $('select[name=moneda]').children('option:selected').data('valor');
			var precio = $('input[name=precioCliente]').val();
			return path + "administrador/regcotizacion/getProductoBusqueda?cambio=" + cambio + "&producto=" + query + "&precio_cliente=" + precio;
		},
		getValue: function (element) {
			return element.nombre;
		},
		list: {
			onSelectItemEvent: function () {
				var selectedItemValue = $("#CotizacionProductoAutocomplete").getSelectedItemData();
				$('input[name=unidadProducto]').val(selectedItemValue.unidad);
				$('input[name=precioProducto]').val(round(selectedItemValue.venta, 2));
				$('input[name=producto]').val(selectedItemValue.id);
			}
		}
	});

	$('.FormCotizacion select[name=moneda]').change(function (event) {
		var cambio = $('select[name=moneda]').children('option:selected').data('valor');
		$('input[name=tipoCambio]').val(cambio);
		$('#TableCotizacionProductos tbody').empty();
		$('#CotizacionValorVenta').html('00.00');
		$('#CotizacionIGV').html('00.00');
		$('#CotizacionTotal').html('00.00');
		$('input[name=monto]').val(0);
		$('input[name=saldo]').val(0);
	});

	$('#FormCotizacionAgregar select[name=tipoPedido]').change(function (event) {
		var id = $(this).val();
		$.getJSON(path + 'administrador/regcotizacion/numeracion', { id }, function (json, textStatus) {
			$('input[name=numero]').val(json.correlativo_actual);
		});
	});


	$('#FormCotizacionAgregar').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			tipoPedido: { required: true },
			nombreCliente: { required: true },
			monto: { required: true, number: true },
			dias: { required: true, number: true, min: 1 }
		},
		submitHandler: function () {
			if ($('#TableCotizacionProductos tbody tr').length == 0) {
				$('#FormCotizacionAgregarProducto').valid();
				return;
			}

			var monto = parseFloat($('input[name=monto]').val());
			var saldo = parseFloat($('input[name=saldo]').val());
			var total = parseFloat($('#CotizacionTotal').text());
			if ((monto + saldo) < total) {
				Swal.fire({
					title: "Error",
					text: "La suma de pago y saldo no es igual al monto total de cotización.",
					type: "error"
				});
				return;
			}

			var cotizacion = $('#FormCotizacionAgregar').serializeObject();
			var productos = $('#FormCotizacionAgregarProducto').serializeObject();
			jQuery.extend(cotizacion, productos);

			$.ajax({
				url: path + 'administrador/regcotizacion/agregarCotizacion',
				type: 'POST',
				dataType: 'JSON',
				data: cotizacion
			})
				.done(function (resp) {
					if (resp.success) {
						window.location.href = path + 'administrador/regcotizacion';
					}
				});

		}
	});

	var id_array = [];
	$('#FormCotizacionAgregarProducto').validate({
		ignore: [],
		rules: {
			producto: { required: true },
			nombreProducto: { required: true },
			cantidadProducto: { required: true, number: true }
		},
		submitHandler: function () {
			var producto = $('input[name=producto]').val();
			if (producto == '')
				return;
			$.getJSON(path + 'administrador/regcotizacion/getProducto', { producto }, function (resp, textStatus) {
				if ($('#TableCotizacionProductos tbody tr').length > 0) {
					$('#TableCotizacionProductos tr').each(function () {
						var data_id = parseInt($(this).data('id'));
						id_array.push(data_id);
					});
					if (id_array.includes(parseInt(resp.cod_producto))) {
						$('#prod-' + resp.cod_producto).remove();
					}
				}

				var tr = `
				<tr id="prod-${resp.cod_producto}" data-id="${resp.cod_producto}">
					<input type="hidden" name="id_prod[]" value="${resp.cod_producto}"/>
					<td>${resp.cod_producto}</td>
					<td>${resp.nomb_product}</td>
					<td>${resp.nomb_marca}</td>
					<td>${resp.nomb_unid}</td>
					<td style="width:110px"><input type="number" class="cant form-control" name="cant_prod[]" value="${$('input[name=cantidadProducto]').val()}" min="1" step="1"/></td>
					<td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[]" value="${round($('input[name=precioProducto]').val(), 2)}"></td>
					<td>
						<input type="hidden" class="desc" name="desc_prod[]" value="${ $('input[name=descuentoProducto]').val()}" />
						${round($('input[name=descuentoProducto]').val(), 2)}
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<div class="btn-group btn-group-justified m-b-10">
               <button data-id="${resp.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
            </div>
					</td>
				</tr>
			`;
				$('#TableCotizacionProductos tbody').append(tr);
				$('#FormCotizacionAgregarProducto')[0].reset();
				$('input[name=producto]').val('');
				calcularTotalCotizacion();
			});
		}
	});

	$('#TableCotizacionProductos tbody').on('click', '.removerProducto', function (event) {
		event.preventDefault();
		$('#prod-' + $(this).data('id')).remove();
		calcularTotalCotizacion();
	});

	$("#TableCotizacionProductos").on('focusout', 'input[name="cant_prod[]"], input[name="prec_prod[]"]', function () {
		calcularTotalCotizacion();

	});

	$('#TableCotizacionProductos').on('focusout', '.prec', function (event) {
		event.preventDefault();
		calcularTotalCotizacion();
	});

	function calcularTotalCotizacion() {
		var total = 0;
		var igv = 18;
		var igv_porcentaje = (igv / 100);
		if ($('#TableCotizacionProductos tbody tr').length > 0)
			$('#TableCotizacionProductos tbody tr').each(function () {
				var cant = parseFloat($(this).find('.cant').val());
				if (isNaN(cant)) {
					var cant = parseFloat($(this).find('.cant').text());
				}
				var prec = parseFloat($(this).find('.prec').val());

				var descuento = parseFloat($(this).find('.desc').val());
				if (isNaN(descuento)) {
					descuento = 0;
				}

			prec -= descuento;
				var subTotalProd = round((cant * prec), 2);
				var igvProd = subTotalProd / (igv_porcentaje + 1);
				igvProd = round(igvProd * igv_porcentaje, 2);
				let valorVentaProd = round(subTotalProd - igvProd, 2);
				$(this).find('td').eq(7).html(igvProd);
				$(this).find('td').eq(8).html(valorVentaProd);

				$(this).find('td').eq(9).html(round(subTotalProd, 2));
				total += parseFloat(subTotalProd);
			});
		
		var IGV = total / (igv_porcentaje + 1);
		IGV = round(IGV * igv_porcentaje, 2);

		let valorVenta = round(total - IGV, 2);

		$('#CotizacionValorVenta').html(valorVenta);
		$('#CotizacionIGV').html(IGV);
		$('#CotizacionTotal').html(round(total, 2));
		$('input[name=monto]').val(round(total, 2));
		$('input[name=total]').val(round(total, 2));
	}


	$('.FormCotizacion input[name=monto]').focusout(function (event) {
		var total = parseFloat($('#CotizacionTotal').text());
		var monto = parseFloat($('input[name=monto]').val());
		if (monto > total) {
			$(this).val(total);
			Swal.fire({
				title: "Error",
				text: "El efectivo no puede ser mayor que " + total,
				type: "error",
			});
		}
		var saldo = total - monto;
		$('input[name=saldo]').val(saldo.toFixed(2));
	});

	$('.FormCotizacion input[name=dias]').focusout(function (event) {
		var dias = parseInt($(this).val()) + 1;
		var fecha = $('input[name=fecha]').val();
		fecha = new Date(fecha);
		fecha.setDate(fecha.getDate() + dias);
		fecha = moment(fecha).format("YYYY-MM-DD");
		$('input[name=fecVenc]').val(fecha);
	});

	$('.FormCotizacion select[name=pago]').change(function (event) {
		var total = parseFloat($('#CotizacionTotal').html());
		if ($(this).val() == 'CRE') {
			$('input[name=dias]').prop('disabled', false).parent().parent().show();
			$('input[name=fecVenc]').parent().parent().show();
			$('input[name=saldo]').parent().parent().show();
			$('input[name=saldo]').val(total);
			$('input[name=monto]').val(0).prop('readonly', false);
		} else {
			$('input[name=dias]').prop('disabled', true).parent().parent().hide();
			$('input[name=fecVenc]').parent().parent().hide();
			$('input[name=saldo]').parent().parent().hide();
			$('input[name=saldo]').val(0);
			$('input[name=monto]').val(total).prop('readonly', true);
		}
	});


	$('#FormCotizacionEditar').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			tipoPedido: { required: true },
			nombreCliente: { required: true },
			monto: { required: true, number: true },
			dias: { required: true, number: true, min: 1 }
		},
		submitHandler: function () {
			if ($('#TableCotizacionProductos tbody tr').length == 0) {
				$('#FormCotizacionEditar').valid();
				return;
			}

			var monto = parseFloat($('input[name=monto]').val());
			var saldo = parseFloat($('input[name=saldo]').val());
			var total = parseFloat($('#CotizacionTotal').text());
			if ((monto + saldo) < total) {
				Swal.fire({
					title: "Error",
					text: "La suma de pago y saldo no es igual al monto total de cotización.",
					type: "error"
				});
				return;
			}

			var cotizacion = $('#FormCotizacionEditar').serializeObject();
			var productos = $('#FormCotizacionAgregarProducto').serializeObject();
			jQuery.extend(cotizacion, productos);

			$.ajax({
				url: path + 'administrador/regcotizacion/editarCotizacion',
				type: 'POST',
				dataType: 'JSON',
				data: cotizacion
			})
				.done(function (resp) {
					if (resp.success) {
						window.location.href = path + 'administrador/regcotizacion';
					}
				});
		}
	});


	$('#FormCotizacionAgregarCliente').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			documento: { required: true },
			// telefono: { required: true },
			direccion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormCotizacionAgregarCliente', function (json) {
				$('#ModalAgregarCliente').modal('hide');
				$('#FormCotizacionAgregarCliente select[name=tipo]').select('val', '');
				$('input[name=cliente]').val(json.cliente.id_cliente);
				$('#RUCAutocomplete').val(json.cliente.doc_cliente);
				$('#ClienteAutocomplete').val(json.cliente.nomb_cliente);
				$('#DireccionCliente').val(json.cliente.direc_cliente);
				$('input[name=precioCliente]').val(json.cliente.precio_cliente);
			})
		}
	});

	$('#CotizacionEditarCliente').click(function (event) {
		$('#ModalEditarCliente').modal();
		var id = $('input[name=cliente]').val();
		$.getJSON(path + 'administrador/regcotizacion/getCliente', { id }, function (json, textStatus) {
			$('#FormCotizacionEditarCliente input[name=id]').val(json.id_cliente);
			$('#FormCotizacionEditarCliente select[name=tipo]').val(json.cod_tipdocucli);
			$('#FormCotizacionEditarCliente select[name=precio]').val(json.precio_cliente);
			$('#FormCotizacionEditarCliente input[name=nombre]').val(json.nomb_cliente);
			$('#FormCotizacionEditarCliente input[name=documento]').val(json.doc_cliente);
			$('#FormCotizacionEditarCliente input[name=telefono]').val(json.telf_cliente);
			$('#FormCotizacionEditarCliente input[name=direccion]').val(json.direc_cliente);
			$('#FormCotizacionEditarCliente input[name=contacto]').val(json.contac_cliente);
			$('#FormCotizacionEditarCliente input[name=email]').val(json.email_cliente);
			$('#FormCotizacionEditarCliente select[name=estado]').val(json.estado_cliente);
		});
	});


	$('#FormCotizacionEditarCliente').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			documento: { required: true },
			telefono: { required: true },
			direccion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormCotizacionEditarCliente', function (json) {
				$('#ModalEditarCliente').modal('hide');
				$('#FormCotizacionEditarCliente select[name=tipo]').select('val', '');
				$('input[name=cliente]').val(json.cliente.id_cliente);
				$('#RUCAutocomplete').val(json.cliente.doc_cliente);
				$('#ClienteAutocomplete').val(json.cliente.nomb_cliente);
				$('#DireccionCliente').val(json.cliente.direc_cliente);
				$('input[name=precioCliente]').val(json.cliente.precio_cliente);
			})
		}
	});


	$('#TableCotizacion').on('click', '.anular', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular compra?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regcotizacion/anular', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableCotizacion').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});

	$('#TableCotizacion').on('click', '.procesar', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "info",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Procesar a Venta?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regcotizacion/procesar', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se proceso correctamente.",
							type: "success"
						});
						$('#TableCotizacion').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});

	/*=====  End of COTIZACION  ======*/



	/*==================================
	=            VENTAS            =
	==================================*/
	var TableVentas = $('#TableVentas').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'administrador/regventas/jsonVentas',
			"type": "GET",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.cliente = $('input[name=cliente]').val();
				if(d.vendedor=$('select[name=vendedor]').val()){
					d.vendedor = $('select[name=vendedor]').val();	
				}	else {							
				d.vendedor = $('input[name=vendedor]').val();	
				}									
				d.punto = $('select[name=punto]').val();
				d.estado = $('select[name=estado]').val();
			}
		},
		"columns": [
			{ "orderable": false, "className": 'details-control' },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false }
						
		],
		
		"columnDefs": [
			{ "width": "5%", "targets": 0 }
		]
	});

	$('#TableVentas tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = TableVentas.row(tr);		

		if (row.child.isShown()) {
			row.child.hide();
			tr.removeClass('shown');
			$(this).find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
		} else {
			row.child(formatVentaDetalle(row.data())).show();
			tr.addClass('shown');
			$(this).find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
		}  
    
  
	});
 

	function formatVentaDetalle(d) {
		var query = jQuery.parseJSON(d[11]);

		var table = `
	
		<table class="table table-bordered">
			<thead>
				<tr class="table-danger">
					<th>Cod</th>
					<th>Artículo</th>
					<th>Isdn</th>
					<th>Series</th>					
					<th>Marca</th>
					<th>Unidad</th>
					<th>Cant.</th>
					<th>P.Unit</th>
					<th>Desc.</th>
					<th>IGV</th>
					<th>Precio</th>
					<th>Subtotal</th>
				</tr>
			</thead>
			<tbody>
		`;
		var tr = '';
		$.each(query, function (index, val) {
			console.log(val);
			var series = '';
			$.each(val.series, function (indexSeries, valueSeries) { 
				 series += `<label class="label label-info">${valueSeries.serie_descripcion}</label> `;
			});
			tr += `
			<tr>
				<td>${val.cod_ventdet}</td>
				<td>${val.producto_ventdet}</td>
				<td>${val.producto_isdn}</td>				
				<td>${series}</td>								
				<td>${val.nomb_marca}</td>
				<td>${val.abreviatura_unid}</td>
				<td>${val.cant_ventdet}</td>
				<td>${val.precunit_ventdet}</td>
				<td>${(val.tipo_ventdet=='V')?val.descuento_ventdet:''}</td>
				<td>${(val.tipo_ventdet=='V')?val.igv_ventdet:''}</td>
				<td>${(val.tipo_ventdet=='V')?val.prec_ventdet:''}</td>
				<td>${(val.tipo_ventdet=='V')?val.subtotal_ventdet:''}</td>
			</tr>
		 `;
		});

		table += tr;
		table += `
			</tbody>
		</table>`;
		return table;
	}

	$('#VentasReportePdf').click(function (event) {
		let form = $('#FormVentasFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regventas/reportePdf?' + params);
	});

	$('#VentasReporteExcel').click(function (event) {
		let form = $('#FormVentasFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regventas/reporteExcel?' + params);
	});


	$('#FormVentasFiltro').validate({
		ignore: [],
		rules: {
			desde: { required: true },
			hasta: { required: true },
			estado: { required: true },
		},
		submitHandler: function () {
			$('#TableVentas').DataTable().ajax.reload();
		}
	});

		$('#TableVentas tbody').on('click','.enviar-whatsapp', function () {
		let id = $(this).data('id');
		let cliente = $(this).data('cliente');
		let telefono = $(this).data('telefono');
		if(telefono==''){
			Swal.fire({
				title: "Error",
				text: "Número de whatsapp desconocido",
				type: "error"
			});
			return;
		}
		$('#generar-documento-whatsapp').html('<i class="fa fa-hand-pointer"></i>	Generar Documento a Enviar').data('id',id).prop('disabled',false);
		$('#nombre-cliente').html(cliente);
		$('#enviar-whatsapp').addClass('disabled');
		$('#ModalEnviarWhatsapp').modal();
	});

	$('#generar-documento-whatsapp').click(function(){
		$(this).html('<i class="fa fa-sync fa-spin"></i> Procesando');
		let id = $(this).data('id');
		$.post(path+"administrador/regventas/imprimirVenta/"+id+"/guardar",{},
			function (data, textStatus, jqXHR) {
				var pdf = `${path}assets/temporal/whatsapp_email/${data.archivo}`;
				var xml = '';
				if(data.xml!=''){
					xml = `%0A%0AComprobante%20XML%0A${path}assets/temporal/whatsapp_email/${data.xml}`;
				}
				var mensaje = `https://api.whatsapp.com/send?phone=51${data.telefono}&text=Comprobante%20PDF%0A${pdf+xml}`
				
				$('#generar-documento-whatsapp').html('<i class="fa fa-check"></i> Generado').prop('disabled',true);
				$('#enviar-whatsapp').attr('href',mensaje).removeClass('disabled');
			},
			"JSON"
		);
	});

	$('#enviar-whatsapp').click(function (e) { 
		$('#ModalEnviarWhatsapp').modal('hide');
	});


	$('#TableVentas tbody').on('click','.enviar-email', function () {
		let id = $(this).data('id');
		let cliente = $(this).data('cliente');
		let email = $(this).data('email');
		if(email==''){
			Swal.fire({
				title: "Error",
				text: "El cliente no tiene un email",
				type: "error"
			});
			return;
		}
		$('#generar-documento-email').html('<i class="fa fa-envelope"></i> Enviar Email').data('id',id).prop('disabled',false);
		
		$('#email-cliente').html(cliente);
		$('#ModalEnviarEmail').modal();
	});

	$('#generar-documento-email').click(function(){
		$(this).html('<i class="fa fa-sync fa-spin"></i> Procesando');
		let id = $(this).data('id');
		$.post(path+"administrador/regventas/imprimirVenta/"+id+"/guardar",{},
			function (data, textStatus, jqXHR) {
				let email = data.email;
				let archivo = data.archivo;
				let cliente = data.cliente;
				let xml = data.xml;
				$.post(path+"administrador/regventas/enviarEmail", {email,archivo,cliente,xml},
					function (data02, textStatus02, jqXHR02) {
						$('#generar-documento-email').html('<i class="fa fa-check"></i> Enviado').prop('disabled',true);
						$('#enviar-email').removeClass('disabled');
					},
					"JSON"
				);
			},
			"JSON"
		);
	});

	$("#ClienteVentaAutocomplete").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			var dni = $('select[name=tipoPedido] option:selected').data('dni');
			var ruc = $('select[name=tipoPedido] option:selected').data('ruc');
			return path + "administrador/regventas/getClientes?q=" + query+'&dni='+dni+'&ruc='+ruc
		},
		getValue: function (element) {
			return element.nombre;
		},
		list: {
			onSelectItemEvent: function () {
				var ruc = $("#ClienteVentaAutocomplete").getSelectedItemData().ruc;
				var direccion = $("#ClienteVentaAutocomplete").getSelectedItemData().direccion;
				var id = $("#ClienteVentaAutocomplete").getSelectedItemData().id;
				var precio = $("#ClienteVentaAutocomplete").getSelectedItemData().precio_cliente;
				$("#RUCAutocomplete").val(ruc);
				$("#DireccionCliente").val(direccion);
				$('input[name=cliente]').val(id);
				$('input[name=precioCliente]').val(precio);
			},
			onClickEvent: function () {
			}
		}
	});

	$("#VentaProductoAutocomplete").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			if($('input[name=precioCliente]').val()==''){
				Swal.fire({
					title: 'Error',
					text: 'Debe selecciona un cliente',
					type: 'error'
				});
				return false;
			}
			$('#select2-series').empty().trigger("change").prop('disabled',true);
			// var cambio = $('select[name=moneda]').children('option:selected').data('valor');
			$('input[name=serieCheckProducto]').prop('disable',true);
			$('input[name=serieCheckProducto]').prop('check',false);
			var cambio = $('input[name=tipoCambio]').val();
			var precio_cliente = $('input[name=precioCliente]').val();
			return path + "administrador/regventas/getProductoBusqueda?cambio=" + cambio + "&producto=" + query + '&almacen=' + $('select[name=almacen]').val() + '&precio_cliente=' + precio_cliente;
		},
		requestDelay: 500,
		getValue: function (element) {
			var disponible = '';
			if (element.estado == '0') {
				disponible = ' (No Disponible)';
			}else{
				if(element.cod_tiparticulo==1){
					disponible = ' (stock'+' '+element.stock+')'				
				}else{
					disponible = '';
				}
			}
			return element.nombre + disponible;
		},
		list: {
			onKeyEnterEvent: function () {
				obtenerValoresArticulos()
			},
			onClickEvent: function(){
				obtenerValoresArticulos()
			}
		}
	});

	$('#servicioCheck').change(function() {
		if(this.checked) {
			$('#VentaProductoAutocomplete').prop('disabled',true).hide();
			$('#nombre-servicio').prop('disabled',false).show();
			$('input[name=producto]').val('servicio');			
			$('#unidad_p').prop('disabled',true).hide();
			$('#peso_p').prop('disabled',true).hide();
			$('#serie_c').prop('disabled',true).hide();			
			$('#precio_u').prop('disabled',true).hide();
			$('#monto_s').prop('disabled',false).show();			
			$('#isdn_product').prop('disabled',true).hide();
		}else{
			$('#nombre-servicio').prop('disabled',true).hide();
			$('#VentaProductoAutocomplete').prop('disabled',false).show();
			$('input[name=producto]').val('');			
			$('#unidad_p').prop('disabled',false).show();
			$('#peso_p').prop('disabled',false).show();
			$('#serie_c').prop('disabled',false).show();
			$('#monto_s').prop('disabled',false).hide();
			$('#precio_u').prop('disabled',false).show();			
			$('#isdn_product').prop('disabled',false).show();			

		}
	});
	// check observacion 
	$('#observacionCheck').change(function() {
		if(this.checked) {
			$('#observacion-a').prop('disabled',false).show();
			
		}else{
			$('#observacion-a').prop('disabled',true).hide();				

		}
	});

	$('#serieChek').change(function() {
		if(this.checked) {
			obtenerSeriesProducto();
			$('input[name=cantidadProducto]').prop('disabled',true);
		}else{
			$('#select2-series').prop('disabled',true);
			$('input[name=cantidadProducto]').prop('disabled',false);
		}
	});

	function obtenerSeriesProducto()	
	{
		idTypeAssignmentProduct=$('input[name=idTypeAssignmentProduct]').val();
		// producto = $('input[name=producto]').val();
		if(idTypeAssignmentProduct!==""){
			producto=$('input[name=idTypeAssignmentProduct]').val();
		}else {
		producto = $('input[name=producto]').val();
		}
		var almacen = $('select[name=almacen]').val();
		$.get(path+"administrador/regventas/getSeriesProducto", {producto,almacen},
			function (res, textStatus, jqXHR) {
					$('#select2-series').empty().trigger("change");
					$('#select2-series').select2({
						data:res	
					});
					// $('#select2-series').prop('disabled',false);

					if(res.length == 0){
					$('input[name=serieCheckProducto]').prop('checked', false);
					$('input[name=serieCheckProducto]').prop('disabled', true);
					$('input[name=cantidadProducto]').prop('disabled', false);
					$('#select2-series').prop('disabled', true);
				}else{
					$('input[name=serieCheckProducto]').prop('checked', true);
					$('input[name=serieCheckProducto]').prop('disabled', false);
					$('input[name=cantidadProducto]').prop('disabled', true);
					$('#select2-series').prop('disabled', false);
				}
				},
				"JSON"
			);
	}

	function obtenerValoresArticulos() {
		var selectedItemValue = $("#VentaProductoAutocomplete").getSelectedItemData();
		
		$('input[name=idTypeAssignmentProduct]').val(selectedItemValue.idTypeAssignmentProduct);
		$('input[name=producto]').val(selectedItemValue.id);
		$('input[name=pesoProducto]').val(selectedItemValue.peso_product);
		$('input[name=unidadProducto]').val(selectedItemValue.unidad);
		$('input[name=precioProducto]').val(parseFloat(selectedItemValue.venta).toFixed(4));
		if (selectedItemValue.estado == '0') {
			$('button[type=submit],input[name=cantidadProducto],input[name=descuentoProducto]').prop('disabled', true);
		} else {
			$('#serieChek').prop('disabled', false);
			$('button[type=submit],input[name=cantidadProducto],input[name=descuentoProducto]').prop('disabled', false);
		}
		obtenerSeriesProducto();
	}

	
    // $('#FormVentaAgregar #fechav').prop('disabled',true);
	$('#FormVentaAgregarProducto select[name=tipoPago]').change(function (event) {
		var tipo = $(this).val();
		if (tipo == 2) {
			$('select[name=tipoTarjeta]').prop('disabled', false);
			$('input[name=operacion]').prop('disabled', false);
		} else {
			$('select[name=tipoTarjeta]').prop('disabled', true);
			$('input[name=operacion]').prop('disabled', true);
		}
	});

	$('.FormVenta select[name=moneda]').change(function (event) {
		var cambio = $('select[name=moneda]').children('option:selected').data('valor');
		$('input[name=tipoCambio]').val(cambio);
		$('#TableVentaProductos tbody').empty();
		$('#VentaValorVenta').html('00.00');
		$('#VentaIGV').html('00.00');
		$('#VentaTotal').html('00.00');
		$('input[name=monto]').val(0);
		$('input[name=saldo]').val(0);
	});

	$('#FormVentaAgregar select[name=tipoPedido]').change(function (event) {
		var id = $(this).val();
		var dni = $(this).find('option:selected').data('dni');
		var ruc = $(this).find('option:selected').data('ruc');
		// console.log(dni,ruc);
		if(dni==1){			
			$('#fnacimiento').prop('disabled',false).show();
			$('#telefono').prop('disabled',false).show();
			$('#idtelefono').prop('disabled',true).hide();			
			$("#FormVentaAgregarCliente  select[name=tipo] option[value='2']").attr('disabled', false);
			$("#FormVentaAgregarCliente  select[name=tipo] option[value='4']").attr('disabled', true);
			$("#FormVentaAgregarCliente  select[name=tipo]").val('2');	
			$("#FormVentaEditarCliente  select[name=tipo] option[value='2']").attr('disabled', false);
			$("#FormVentaEditarCliente  select[name=tipo] option[value='4']").attr('disabled', true);
			$("#FormVentaEditarCliente  select[name=tipo]").val('2');
			$('#FormVentaEditarCliente #idtelefono').prop('disabled',true).hide();
			$('#FormVentaEditarCliente #fnacimiento').prop('disabled',false).show();	
			$('#FormVentaEditarCliente #telefono').prop('disabled',false).show();
		}
		if(ruc==1){	
		    $('#fnacimiento').prop('disabled',true).hide();
			$('#telefono').prop('disabled',true).hide();				
			$('#idtelefono').prop('disabled',false).show();			
			$("#FormVentaAgregarCliente  select[name=tipo] option[value='4']").attr('disabled', false);
			$("#FormVentaAgregarCliente  select[name=tipo] option[value='2']").attr('disabled', true);
			$("#FormVentaAgregarCliente  select[name=tipo]").val('4');
			$("#FormVentaEditarCliente  select[name=tipo] option[value='4']").attr('disabled', false);
			$("#FormVentaEditarCliente  select[name=tipo] option[value='2']").attr('disabled', true);
			$("#FormVentaEditarCliente  select[name=tipo]").val('4');
			$('#FormVentaEditarCliente #fnacimiento').prop('disabled',true).hide();	
			$('#FormVentaEditarCliente #telefono').prop('disabled',true).hide();
			$('#FormVentaEditarCliente #idtelefono').prop('disabled',false).show();					
		}

		$('#RUCAutocomplete').prop('disabled',true).val('');
		$('#ClienteVentaAutocomplete').prop('disabled',true).val('');
		$.getJSON(path + 'administrador/regventas/numeracion', { id }, function (json, textStatus) {
			$('input[name=correlativo]').val(json.correlativo_actual);
			$('input[name=serie]').val(json.serie);
			$('#RUCAutocomplete').prop('disabled',false);
			$('#ClienteVentaAutocomplete').prop('disabled',false);
		});
	});

	function verificarCoberturaCliente(callback) {
		var total = parseFloat($('#VentaTotal').html());
		var monto = $('#FormVentaAgregar input[name=monto]').val();
		var cliente = $('#FormVentaAgregar input[name=cliente]').val();
		if (cliente == '') {
			return;
		}
		var saldo = total - monto;
		$.getJSON(path + 'administrador/regventas/verificarCoberturaCliente', { saldo, cliente }, function (json, textStatus) {
			callback(json);
		});
	}

	$('#FormVentaAgregar input[name=monto]').focusout(function (event) {
		if ($('select[name=pago]').val() == 'CRE') {

			verificarCoberturaCliente(function (resp) {
				if (!resp.success) {
					Swal.fire({
						title: "Error",
						text: resp.mensaje,
						type: "error"
					});
					var total = parseFloat($('#VentaTotal').html());
					$('select[name=pago]').val('CO');
					$('input[name=dias]').prop('disabled', true).parent().parent().hide();
					$('input[name=fecVenc]').parent().parent().hide();
					$('input[name=saldo]').parent().parent().hide();
					$('input[name=saldo]').val(0);
					$('input[name=monto]').val(total).prop('readonly', true);
					return;
				}
			});
		}
	});

	$('#FormVentaAgregarProducto input[name=montoRecibido]').focusout(function (event) {
		let montoRecibido = parseFloat($(this).val());
		let monto = parseFloat($('#FormVentaAgregar input[name=monto]').val());
		if (!isNaN(monto)) {
			if (montoRecibido > 0) {
				let vuelto = montoRecibido - monto;
				$('input[name=vuelto]').val(round(vuelto, 2));
			}
		}
	});


	$('#FormVentaAgregar').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			tipoPedido: { required: true },
			nombreCliente: { required: true },
			monto: { required: true, number: true },
			dias: { required: true, number: true, min: 1 },
			montoRecibido: { required: true },
			nombreCliente:{required:true}
		},

		submitHandler: function () {
			if ($('#TableVentaProductos tbody tr').length == 0) {
				$('#FormVentaAgregarProducto').valid();
				return;
			}

			var monto = parseFloat($('input[name=monto]').val());
			var saldo = parseFloat($('input[name=saldo]').val());
			var total = parseFloat($('#VentaTotal').text());
			var montoRecibido = parseFloat($('input[name=montoRecibido]').val());
			if ((monto + saldo) != total) {
				Swal.fire({
					title: "Error",
					text: "La suma de pago y saldo no es igual al monto total de la venta.",
					type: "error"
				});
				return;
			}

			if (montoRecibido < monto) {
				Swal.fire({
					title: "Error",
					text: "El monto recibido no puede ser menor que el monto.",
					type: "error"
				});
				return;
			}

			var cotizacion = $('#FormVentaAgregar').serializeObject();
			var productos = $('#FormVentaAgregarProducto').serializeObject();
			jQuery.extend(cotizacion, productos);

			$.ajax({
				url: path + 'administrador/regventas/agregarVenta',
				type: 'POST',
				dataType: 'JSON',
				data: cotizacion,
				beforeSend: function () {
					$('#VentasContenedorGuardar').find('button:submit').prop('disabled', true).html('Procesando');
				}
			})
				.done(function (resp) {
					if (resp.success) {
						$('#FormVentaAgregar')[0].reset();
						$('#FormVentaAgregar').removeClass('has-success');
						$('#FormVentaAgregar').find('.form-group').removeClass('has-success');
						$('#TableVentaProductos tbody').remove();
						$('#VentaValorVenta').html('00.00');
						$('#VentaIGV').html('00.00');
						$('#VentaTotal').html('00.00');

						$('#VentasContenedorGuardar').find('button:submit').prop('disabled', false).html('Guardar');
						$('#VentaImprimirA4').attr('href', path + 'administrador/regventas/imprimirVenta/' + resp.xml.archivo);						
						$('#VentaImprimirTicket').attr('href', path + 'administrador/regventas/imprimirticketVenta/' + resp.xml.archivo);				
						// $('#ModalAccionesDespuesGuardar').modal();
						if(resp.printType == 'T'){
							document.querySelector('#VentaImprimirTicket').click();
						}else{
							document.querySelector('#VentaImprimirA4').click();
						}
						location.reload();
					}
				});

		}
	});



	var id_array = [];
	$('#FormVentaAgregarProducto').validate({
		ignore: [],
		rules: {
			producto: { required: true },
			nombreProducto: { required: true },
			'seriesProducto[]':{required:true},
			precioProducto:{required:true,decimal:true},
			cantidadProducto:{required:true,number:true}
			

		},
		submitHandler: function () {
			if (!$('#FormVentaAgregar').valid()) {
				return;
			}

			if($('#servicioCheck').prop('checked')){
				var servicioCheckBox = true;
			}else{
				var servicioCheckBox = false;
      }
      
			if($('input[name=serieCheckProducto]').prop('checked')){
				var seriesCheckBox = true;
			}else{
				var seriesCheckBox = false;
			}
			var producto = $('input[name=producto]').val();
			if (producto == '')
				return;
			var tipo = $('select[name=tipo]').val();
			var almacen = $('select[name=almacen]').val();
			var cantidad = $('#select2-series').find(':selected').length;
			if(!seriesCheckBox){
				var cantidad = $('input[name=cantidadProducto]').val();
			}
			var cambio = $('input[name=tipoCambio]').val();
			var seriesSelect = [];
			$('#select2-series').find(':selected').map(function(index,elem){
				seriesSelect.push($(elem).val());
			});
			
			var unidad = $('input[name=unidadProducto]').val();
			var peso = $('input[name=pesoProducto]').val();
			
      $('#FormVentaAgregarProducto button[type=submit]').prop('disabled',true);
      
      if(servicioCheckBox==false){
        $.getJSON(path + 'administrador/regventas/getProducto', { series:seriesSelect,producto, cantidad, cambio, almacen }, function (resp) {
        	// (resp.response.idTypeAssignmentProduct !== null) ? producto = resp.response.idTypeAssignmentProduct : resp.response.cod_producto;
            var producto = resp.response.cod_producto;
          if (resp.tipo == 1 && !resp.estado) {
            Swal.fire({
              title: "Error",
              text: "El stock máximo disponible es " + resp.response.stock_disponible,
              type: "error",
            });
            return;
          }
          if ($('#TableVentaProductos tbody tr.fila-producto').length > 0) {
            $('#TableVentaProductos tr.fila-producto').each(function () {
              var data_id = parseInt($(this).data('id'));
              id_array.push(data_id);
            });
            if (id_array.includes(parseInt(resp.response.cod_producto))) {
							if($('#prod-' + resp.response.cod_producto).next('.fila-detalle')[0]!=undefined){
								$('#prod-' + resp.response.cod_producto).next().remove();
              }
              $('#prod-' + resp.response.cod_producto).remove();
            }
          }
					
					var almacen = $('select[name=almacen]').val();
					// console.log('holaa');
          var tr = `
          <tr class="fila-producto hide" id="prod-${producto}" data-id="${producto}">
            <input type="hidden" name="id_prod[${producto}]" value="${producto}"/>
            <input type="hidden" name="idTypeAssignmentProduct[${producto}]" value="${resp.response.idTypeAssignmentProduct}"/>
						<input type="hidden" name="id_almacen[${almacen}]" value="${almacen}"/>
						<input type="hidden" name="unidad_prod[${producto}]" value="${unidad}" />
						<input type="hidden" name="peso_prod[${producto}]" value="${peso}" />
						<input type="hidden" name="tipo[${producto}]" value="${tipo}" class="tipo"/>
            <td class="details-control">
              ${(seriesCheckBox)?'<button type="button" class="btn btn-icon waves-effect waves-light btn-success"><span class="fa fa-caret-right"></span></button>':''}
            </td>
            <td>${resp.response.cod_producto}</td>
            <td><input name="nombre_prod[${producto}]" class="form-control" value="${html_escape(resp.response.nomb_product)}"></td>
            <td class="${(movilexpert=='0')?'d-none':''}"><input name="producto_isdn[${producto}]" class="form-control" value="${$('#producto_isdn').val()}"></td>
						<td>${resp.response.nomb_marca}</td>						
            <td>${resp.response.nomb_unid}</td>
            <td style="width:110px"><input min="1" type="${(seriesCheckBox)?'hidden':'number'}" class="cant form-control" name="cant_prod[${producto}]" value="${cantidad}" />${(seriesCheckBox)?cantidad:''}</td>
            <td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[${producto}]" value="${($('input[name=precioProducto]').val())}"></td>
            <td>
              <input type="hidden" class="desc" name="desc_prod[${producto}]" value="${ $('input[name=descuentoProducto]').val()}" />
              ${round($('input[name=descuentoProducto]').val(), 2)}
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <div class="btn-group btn-group-justified m-b-10">
                <button data-id="${resp.response.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
              </div>
            </td>
          </tr>
          `;
          if(seriesCheckBox){
            tr += `
            <tr class="fila-detalle" style="display:none">
            <td></td>
            <td><b>Series:</b></td>
            <td colspan="8">             
            <select data-producto="${producto}" id="serieprod-${producto}" name="series[${producto}]" class="series-producto form-control" multiple="multiple"></select>
            </td>
            <td colspan="8"></td>
            </tr>
            `;
          }
  
          if($('#TableVentaProductos tbody>tr.fila-producto')[0] == undefined){
            $('#TableVentaProductos tbody').append(tr);
          }else{
            if($('#TableVentaProductos tbody .fila-producto').last().next()[0] != undefined){
              $($('#TableVentaProductos tbody .fila-producto').last().next()).after(tr);
            }else{
              $($('#TableVentaProductos tbody .fila-producto').last()).after(tr);
            }
          }
		  //debugger;
          var dataSeries = resp.series;
          $('#serieprod-'+producto).select2({
            data:dataSeries,
            multiple:true
          });
          
          setTimeout(() => {
            $.each($('.series-producto'), function (index, element) {
              var select = $(element);
              var producto = select.data('producto');
              var array = [];					
              var choice = $(element).next().find('.select2-selection__choice');					
              $.each(choice,function(indexLi,elementLi){
                var title = $(this).attr('title');
                array.push(title);
              })
              $('#serieprod-'+producto).val(array);
            });
          }, 1000);
        
  
          $('#FormVentaAgregarProducto')[0].reset();
          $('input[name=producto]').val('');
					$('#select2-series').empty().trigger("change");
					$('#nombre-servicio').prop('disabled',true).hide();
					$('#VentaProductoAutocomplete').prop('disabled',false).show();
					$('#FormVentaAgregarProducto input[name=producto]').val('');
          $('#FormVentaAgregarProducto button[type=submit]').prop('disabled',false);
          calcularTotalVenta();
        });
      }else{
		  //debugger;
				var random = Math.floor(Math.random() * (9000 - 1000) + 1000);
				producto = 'ser-'+$('#nombre-servicio').val().trim().replace(' ','').substr(0,3)+random;
        var tr = `
        <tr class="fila-producto hide" id="prod-${producto}" data-id="${producto}">
          <input type="hidden" name="id_prod[${producto}]" value="${producto}"/>
					<input type="hidden" name="id_almacen[${almacen}]" value="${almacen}"/>
					<input type="hidden" name="unidad_prod[${producto}]" value="${unidad}" />
					<input type="hidden" name="peso_prod[${producto}]" value="${peso}" />
					<input type="hidden" name="tipo[${producto}]" value="${tipo}" class="tipo"/>					
          <td class="details-control"></td>
          <td>${producto}</td>
          <td><input name="nombre_prod[${producto}]" class="form-control" value="${$('#nombre-servicio').val()}"></td>
          <td><input type="hidden" name="producto_isdn[${producto}]" class="form-control" value="${$('#producto_isdn').val()}"></td>
					<td></td>
					<input name="unidad_prod[${producto}]" value="${unidad}">
					<input name="peso_prod[${producto}]" value="${peso}">
          <td>${unidad}</td>
          <td style="width:110px"><input min="1" type="number" class="cant form-control" name="cant_prod[${producto}]" value="${cantidad}" /></td>
          <td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[${producto}]" value="${round($('input[name=precioProducto]').val(), 2)}"></td>
          <td>
            <input type="hidden" class="desc" name="desc_prod[${producto}]" value="${ $('input[name=descuentoProducto]').val()}" />
            ${round($('input[name=descuentoProducto]').val(), 2)}
          </td>
          <td></td>
          <td></td>
          <td></td>       

          <td>
            <div class="btn-group btn-group-justified m-b-10">
              <button data-id="${producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
            </div>
          </td>
				</tr>`;
				
				if($('#TableVentaProductos tbody>tr.fila-producto')[0] == undefined){
					$('#TableVentaProductos tbody').append(tr);
				}else{
					if($('#TableVentaProductos tbody .fila-producto').last().next()[0] != undefined){
						$($('#TableVentaProductos tbody .fila-producto').last().next()).after(tr);
					}else{
						$($('#TableVentaProductos tbody .fila-producto').last()).after(tr);
					}
				}
				$('#FormVentaAgregarProducto input[name=cantidadProducto]').val('');
				$('#FormVentaAgregarProducto input[name=precioProducto]').val('');					
				$("#nombre-servicio").val("");
				$('#servicioCheck').prop('checked',false);									
				$('input[name=producto]').val('');
				$('#select2-series').empty().trigger("change");
				$('#FormVentaAgregarProducto button[type=submit]').prop('disabled',false);

				$('#nombre-servicio').prop('disabled',true).hide();
				$('#VentaProductoAutocomplete').prop('disabled',false).show();
				$('#FormVentaAgregarProducto input[name=producto]').val('');
				calcularTotalVenta();
      }
		}
	});

	$('#TableVentaProductos').on('change','.series-producto', function () {
		var producto = $(this).data('producto');
		var cantidad = $('#serieprod-'+producto).find(':selected').length;
		var td = `
		<input type="hidden" class="cant" name="cant_prod[${producto}]" value="${cantidad}" />${cantidad}
		`;
		$('#prod-'+producto+' td').eq(5).html(td);
		calcularTotalVenta();
	});
	

	$('#TableVentaProductos tbody').on('click', '.removerProducto', function (event) {
		event.preventDefault();
		if($('#prod-' + $(this).data('id')).next('.fila-detalle')[0]!=undefined){
			$('#prod-' + $(this).data('id')).next().remove();
		}
		$('#prod-' + $(this).data('id')).remove();
		calcularTotalVenta();
	});

	$("#TableVentaProductos").on('focusout', 'input[name="cant_prod[]"], input[name="prec_prod[]"]', function () {
		calcularTotalVenta();
	});

	$('#TableVentaProductos').on('focusout', '.cant', function (event) {
		event.preventDefault();
		calcularTotalVenta();
	});

	$('#TableVentaProductos').on('focusout', '.prec', function (event) {
		event.preventDefault();
		calcularTotalVenta();
	});

	$('#TableVentaProductos tbody').on('click','.details-control', function () {
		var fila = $(this).parent();
		if(fila.hasClass('hide')){
			fila.find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
			fila.removeClass('hide').addClass('show');
			fila.next().show();
		}else{
			fila.find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
			fila.removeClass('show').addClass('hide');
			fila.next().hide();
		}
	});
	function html_escape(text) {
	  return text
	      .replace(/&/g, "&amp;")
	      .replace(/</g, "&lt;")
	      .replace(/>/g, "&gt;")
	      .replace(/"/g, "&quot;")
	      .replace(/'/g, "&#039;");
	}
	function calcularTotalVenta() {
		var total = 0;
		var igv = 18;
		var igv_porcentaje = (igv / 100);
		if ($('#TableVentaProductos tbody .fila-producto').length > 0){
			$('#TableVentaProductos tbody .fila-producto').each(function () {
				var cant = parseFloat($(this).find('.cant').val());
				var tipo = $(this).find('.tipo').val();
				if (isNaN(cant)) {
					var cant = parseFloat($(this).find('.cant').text());
				}
				var prec = parseFloat($(this).find('.prec').val());

				var descuento = parseFloat($(this).find('.desc').val());
				if (isNaN(descuento)) {
					descuento = 0;
				}

					if(tipo=='V'){
					prec -= descuento;
					var subTotalProd = round((cant * prec), 2);
					var igvProd = subTotalProd / (igv_porcentaje + 1);
					igvProd = round(igvProd * igv_porcentaje, 2);
					let valorVentaProd = round(subTotalProd - igvProd, 2);
					$(this).find('td').eq(9).html(igvProd);
					$(this).find('td').eq(10).html(valorVentaProd);
	
					$(this).find('td').eq(11).html(round(subTotalProd, 2));
					total += parseFloat(subTotalProd);
				}
			});
		}

		var IGV = total / (igv_porcentaje + 1);
		IGV = round(IGV * igv_porcentaje, 2);

		let valorVenta = round(total - IGV, 2);

		$('#VentaValorVenta').html(valorVenta);
		$('#VentaIGV').html(IGV);
		$('#VentaTotal').html(round(total, 2));
		$('input[name=monto]').val(round(total, 2));
		$('input[name=total]').val(round(total, 2));

	}

	function calcularDescuento()
	{
		var descuentoTotal = parseFloat($('input[name=descuento]').val());
		$('#TableVentaProductos tbody .fila-producto').each(function () {
			var id = $(this).data('id');
			var prec = parseFloat($(this).find('.prec').val());
			var desc = (prec * descuentoTotal) / 100;
			var tdDesc = `
				<input type="hidden" class="desc" name="desc_prod[${id}]" value="${desc}"></input>
				${desc}
			`;
			$(this).find('.desc').parent().html(tdDesc);
		})
	}

	$('input[name=descuento]').focusout(function (e) { 
		calcularDescuento();
		calcularTotalVenta();
	});


	$('.FormVenta input[name=monto]').focusout(function (event) {
		var total = parseFloat($('#VentaTotal').text());
		var monto = parseFloat($('input[name=monto]').val());
		if (monto > total) {
			$(this).val(total);
			Swal.fire({
				title: "Error",
				text: "El monto no puede ser mayor que " + total,
				type: "error",
			});
		}
		var saldo = total - monto;
		$('input[name=saldo]').val(saldo.toFixed(2));
	});

	$('.FormVenta input[name=dias]').focusout(function (event) {
		var dias = parseInt($(this).val()) + 1;
		var fecha = $('input[name=fecha]').val();
		fecha = new Date(fecha);
		fecha.setDate(fecha.getDate() + dias);
		fecha = moment(fecha).format("YYYY-MM-DD");
		$('input[name=fecVenc]').val(fecha);
	});

	$('.FormVenta select[name=pago]').change(function (event) {
		var total = parseFloat($('#VentaTotal').html());
		if ($(this).val() == 'CRE') {
			$('#pagocredito').show();
			$('.pagocredito-dias').show();
			$('.pagocredito-cuotas').hide();
			$('input[name=dias]').prop('disabled', false);
			$('input[name=saldo]').val(total);
			$('input[name=monto]').val(0).prop('readonly', false);
		} else {
			$('#pagocredito').hide();
			$('.pagocredito-dias').hide();
			$('.pagocredito-cuotas').hide();
			$('input[name=dias]').prop('disabled', true);
			$('input[name=saldo]').val(0);
			$('input[name=monto]').val(total).prop('readonly', true);
		}
	});

	$('#switch-dias-cuotas').change(function() {
		$('#TableCuotasContent').hide();
		if(this.checked) {
			$('.pagocredito-dias').hide();
			$('.pagocredito-cuotas').show();
			$('input[name=dias]').prop('disabled', true);
		}else{
			$('.pagocredito-dias').show();
			$('.pagocredito-cuotas').hide();
			$('input[name=dias]').prop('disabled', false);
		}
	});

	$('#calcular-cuotas').click(function(){
		let total = parseFloat($('#VentaTotal').html());
		if(total==0){
			Swal.fire({
				title: "Error",
				text: "No se puede calcular cuando el total es 0",
				type: "error"
			});
			return;
		}
		$('#total-cuotas').html(total);
		$('#TableCuotasContent').show();
		let periodo = $('select[name=periodo]').val();
		let numero = $('input[name=numero_cuotas]').val();
		$.post(path+"administrador/regventas/calcularCuotas", {periodo,numero,total},
			function (data, textStatus, jqXHR) {
				var tr = '';
				$.each(data, function (index, value) { 
					tr += `
					<tr>
						<td><input name="cuotas_fecha[]" class="form-control" value="${value.fecha}"/></td>
						<td><input name="cuotas_monto[]" class="form-control cuota-monto" value="${value.monto}"/></td>
					</tr>
					`;
				});
				$('#TableCuotas tbody').html(tr);
				$.each($('#TableCuotas tbody input[name^="cuotas_fecha"]'), function(index, val) {
					$(val).datepicker({
						autoclose: true,
						language: "es",
						format: "yyyy-mm-dd",
						todayHighlight: true
					});
				});
			},
			"JSON"
		);
	});

	$('#TableCuotas tbody').on('focusout','.cuota-monto', function () {
		if($(this).val()==''){
			return;
		}
		var numero = parseFloat($(this).val());
		if(typeof numero != 'number'){
			$(this).val('0');
			return;
		}

		$('button[form="FormVentaAgregar"]').prop('disabled',false);

		var suma = 0;
		var total = parseFloat($('#VentaTotal').html());
		$.each($('.cuota-monto'), function(index, val) {
			suma += parseFloat($(this).val());
		});

		suma = Math.ceil(suma);
		
		if(suma < total){
			$('.cuotas-error').show().html('La suma es menor al total');
		}
		if(suma > total){
			$('.cuotas-error').show().html('La suma es mayor al total')
		}

		if(suma==total){
			$('.cuotas-error').hide();
		}


	});


	// $('#FormCotizacionEditar').validate({
	// 	ignore: [],
	// 	rules:{
	// 		fecha:{required:true},
	// 		tipoPedido:{required:true},
	// 		nombreCliente:{required:true},
	// 		monto:{required:true, number:true},
	// 		dias:{required:true,number:true,min:1}
	// 	},
	// 	submitHandler:function(){
	// 		if ($('#TableCotizacionProductos tbody tr').length == 0){
	// 			$('#FormCotizacionEditar').valid();
	// 			return;
	// 		}

	// 		var monto = parseFloat($('input[name=monto]').val());
	// 		var saldo = parseFloat($('input[name=saldo]').val());
	// 		var total = parseFloat($('#CotizacionTotal').text());
	// 		if ((monto + saldo) < total) {
	// 			Swal.fire({
	// 				title: "Error",
	// 				text: "La suma de pago y saldo no es igual al monto total de cotización.",
	// 				type: "error"
	// 			});
	// 			return;
	// 		}

	// 		var cotizacion = $('#FormCotizacionEditar').serializeObject();
	// 		var productos = $('#FormCotizacionAgregarProducto').serializeObject();
	// 		jQuery.extend(cotizacion, productos);

	// 		$.ajax({
	// 			url: path+'administrador/regcotizacion/editarCotizacion',
	// 			type: 'POST',
	// 			dataType: 'JSON',
	// 			data: cotizacion
	// 		})
	// 		.done(function(resp) {
	// 			if (resp.success) {
	// 				window.location.href = path+'administrador/regcotizacion';
	// 			} 	
	// 		});
	// 	}
	// });

	$('#FormVentaAgregarCliente').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			documento: { required: true,
			remote:{
				url: path+"administrador/regcliente/validaClienteUnico",
				type: "POST",
				data: {
					documento: function() {
						return $("#FormVentaAgregarCliente input[name=documento]").val();
					},
					id: function(){
						return $("input[name=codigo]").val();
					}
				}
			}
	 },
			// telefono: { required: true },
			direccion: { required: true }
					},
		messages:{
		documento:{
			remote:'Este número de documento ya existe'					

		}

		},
		submitHandler: function () {
			enviarFormulario('#FormVentaAgregarCliente', function (json) {
				if (json.success) {
					$('#TableListarClientes').DataTable().ajax.reload();
				}
				$('#ModalAgregarCliente').modal('hide');
				$('#FormVentaAgregarCliente select[name=tipo]').select('val', '');
				$('#FormVentaAgregarCliente input[name=documento]').val('');
				$('input[name=cliente]').val(json.cliente.id_cliente);
				$('#RUCAutocomplete').val(json.cliente.doc_cliente);
				$('#ClienteVentaAutocomplete').val(json.cliente.nomb_cliente);				
				$('#DireccionCliente').val(json.cliente.direc_cliente);
				$('input[name=precioCliente]').val(json.cliente.precio_cliente);									
			})
		}
	});



	$('#VentaEditarCliente').click(function (event) {
		$('#ModalEditarCliente').modal();
		var id = $('input[name=cliente]').val();
		$.getJSON(path + 'administrador/regventas/getCliente', { id }, function (json, textStatus) {
			$('#FormVentaEditarCliente input[name=id]').val(json.id_cliente);
			$('#FormVentaEditarCliente select[name=tipo]').val(json.cod_tipdocucli );
			$('#FormVentaEditarCliente input[name=nombre]').val(json.nomb_cliente);
			$('#FormVentaEditarCliente input[name=fnacimiento]').val(json.fena_pac);
			$('#FormVentaEditarCliente select[name=precio_venta]').val(json.precio_cliente);
			$('#FormVentaEditarCliente input[name=documento]').val(json.doc_cliente);
			$('#FormVentaEditarCliente input[name=telefono]').val(json.telf_cliente);
			$('#FormVentaEditarCliente input[name=direccion]').val(json.direc_cliente);
			$('#FormVentaEditarCliente input[name=contacto]').val(json.contac_cliente);
			$('#FormVentaEditarCliente input[name=email]').val(json.email_cliente);
			$('#FormVentaEditarCliente select[name=estado]').val(json.estado_cliente);
		});
	});


	$('#FormVentaEditarCliente').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			documento: { required: true },
			// telefono: { required: true },
			direccion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormVentaEditarCliente', function (json) {
				$('#ModalEditarCliente').modal('hide');
				$('#FormVentaEditarCliente select[name=tipo]').select('val', '');
				$('input[name=cliente]').val(json.cliente.id_cliente);
				$('#RUCAutocomplete').val(json.cliente.doc_cliente);
				$('#ClienteVentaAutocomplete').val(json.cliente.nomb_cliente);
				$('#DireccionCliente').val(json.cliente.direc_cliente);
				$('input[name=precioCliente]').val(json.cliente.precio_cliente);
			})
		}
	});


	$('#TableVentas').on('click', '.anular', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular venta?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regventas/anular', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableVentas').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});

	$('#DeudaCliente').click(function (event) {
		var cliente = $('input[name=cliente]').val();
		if (cliente == '') {
			Swal.fire({
				title: "Error",
				text: "No has seleccionado ningun cliente",
				type: "error"
			});
			return;
		}
		$('#TableDeudaCliente tbody').html('');
		$.getJSON(path + 'administrador/regventas/deudasCliente', { cliente }, function (json, textStatus) {
			var tr = '';
			$.each(json, function (index, val) {
				tr += `
				<tr>
					<td>${val.nomb_cliente}</td>
					<td>${val.doc_cliente}</td>
					<td>${val.fecha_vent}</td>
					<td>${val.total_vent}</td>
					<td>${val.pendiente_vent}</td>
					<td><a target="_blank" href="${path + 'administrador/regcuentascobrar/detalle/' + val.id_cliente}" class="btn btn-xs btn-success"><i class="fas fa-hand-holding-usd"></i> Pagar</a></td>
				</tr>
			`;
			});
			$('#TableDeudaCliente tbody').html(tr);
		});
	});

	/*=====  End of VENTAS  ======*/


	/*===================================
	=            FACTURACION            =
	===================================*/
	var TableFacturacion = $('#TableFacturacion').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regfacturacion/jsonFacturas',
			"type": "GET",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.estado = $('select[name=estado]').val();
			}
		},
		"columns": [
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false }
		],
		"columnDefs": [
			{ "width": "5%", "targets": 0 },
			{ "width": "15%", "targets": 9 }
		]
	});

	$('#FormFacturasFiltro').validate({
		ignore: [],
		rules: {
			desde: { required: true },
			hasta: { required: true },
			estado: { required: true },
		},
		submitHandler: function () {
			$('#TableFacturacion').DataTable().ajax.reload();
		}
	});

	$('#procesar-facturas').click(function () {
		var boton = $(this);
		var seleccionados = $("#TableFacturacion input[name='factura']:checked:enabled");
		var contador = 0;
		$.each(seleccionados, function () {
			var id = $(this).data('id');
			$(this).prop('disabled', true);
			var fila = $(this).parent().prev();
			var fila_dias = $(this).parent().prev().prev();
			fila.html('<div class="spinner-grow text-warning"></div> <label class="label label-warning">Procesando</label>');
			$.ajax({
				beforeSend: function () {
					boton.prop('disabled', true);
				},
				type: "POST",
				url: path + 'administrador/regfacturacion/enviarDocumentoSunat',
				data: { id },
				dataType: "JSON",
			}).then(function (data) {
				if (data.respuesta == 'ok' && data.hash_cdr != '') {
					fila_dias.html('<span class="fas fa-check icon-verde"></span>');
					fila.html('<label class="label label-success">Aceptada</label>');
					var btn_imprimir = `<a target="_blank" title="Imprimir" href="${path + 'administrador/regventas/imprimirVenta/'+data.query.archivoxml_vent}" class="btn btn-sm btn-primary"><i class="far fa-file-alt"></i></a>`;
					var btn_xml = `<a target="_blank" href="${path + 'facturacion/' + data.query.rutaxml_vent + '/' + data.query.archivoxml_vent + '.XML'}" class="btn btn-sm btn-primary">XML</a>`;
					var btn_cdr = `<a target="_blank" href="${path + 'facturacion/' + data.query.rutaxml_vent + '/R-' + data.query.archivoxml_vent + '.XML'}" class="btn btn-sm btn-primary">CDR</a>`;
					fila.next().html(btn_imprimir+' '+btn_xml + btn_cdr);
				} else {
					fila.html('<label class="label label-danger">Rechazada</label>');
					fila.next().find('input').prop('disabled', false).prop('checked', false);
				}
				contador++;
				if (seleccionados.length == contador) {
					boton.prop('disabled', false);
				}
			});


		});

	});

	$('#Reportexcelfe').click(function (event) {
	let form = $('#FormFacturasFiltro').serializeObject();//FormReporteVentasDetalladasBusqueda
	let params = $.param(form);
	$(this).attr('href', path + 'administrador/regfacturacion/reporteComprobates?' + params);
});

	/*=====  End of FACTURACION  ======*/



	/*==============================================
	=            RESUMEN DE BOLETAS                =
	==============================================*/
	$('#TableResumen').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": true,
		"processing": true,
		"serverSide": false,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
	});

	$('#modal-resumen').click(function () {
		$('#ModalAgregarResumen').modal();
		$('#TableResumenFecha tbody').html('');
	});

	$('#filtrarResumen').click(function (event) {
		event.preventDefault();
		$('#TableResumenFecha tbody').html('');
		var fecha = $('#FormResumenFecha input[name=fecha]').val();
		$.getJSON(path + 'administrador/regdocumentoelectronico/getResumenFecha', { fecha }, function (json, textStatus) {


			if (!json.verifica) {
				Swal.fire({
					title: "Error",
					text: "Esta fecha ya tiene resumen",
					type: "error"
				});
				return;
			}

			if (json.result.length == 0) {
				Swal.fire({
					title: "Error",
					text: "No hay registros en la fecha seleccionada",
					type: "error"
				});
				return;
			}


			var tr = '';
			$.each(json.result, function (index, val) {
				tr += `
				<tr>
					<td>${val.fecha_vent}</td>
					<td>${val.nomb_cliente}</td>
					<td>${val.igv_vent}</td>
					<td>${val.subtotal_vent}</td>
					<td>${val.total_vent}</td>
				</tr>
			`;
			});
			$('#TableResumenFecha tbody').html(tr);
		});
	});

	$('#FormResumenFecha').validate({
		ignore: [],
		rules: {
			fecha: { required: true }
		},
		submitHandler: function () {
			if ($('#TableResumenFecha tbody>tr').length == 0) {
				Swal.fire({
					title: "Error",
					text: "No hay registros en la fecha seleccionada",
					type: "error"
				});
				return;
			}

			enviarFormulario('#FormResumenFecha', () => {

				//$('#ModalAgregarResumen').trigger('click');
				//$('#TableAsignarPuntoVenta').DataTable().ajax.reload();
			})
		}
	});


	$('#TableResumen').on('click','.ver-boletas', function () {
		var id = $(this).data('id');
		
				 
		$('#lista-boletas').html('');
		$('#ModalListaBoletas').modal();
		$.post(path+"administrador/regdocumentoelectronico/getBoletas", {id},
			function (data, textStatus, jqXHR) {
				var link = '';
				var link = '<table id="TableBoletas" class="table mb-0" cellspacing="0" width="100%">';
				$.each(data, function (index, val) { 
					//link += `<a target="_blank" href="${path+'administrador/regventas/imprimirVenta/'+val.cod_vent}" class="btn btn-info btn-md">Boleta ${val.cod_vent}</a>`;
				link =link+'<tr class="bg-success text-white">';			
				link=link+'<th>Ver</th>';
				link =link+'<th>DNI/RUC</th>';				
				link =link+'<th>Cliente</th>';
				link =link+'<th>Documento</th>';
				link =link+'<th>Fecha</th>';
				link =link+'<th>Moneda</th>';
				link =link+'<th>Total</th>';
				link =link+'</tr>';			
				$.each(data.data, function (index, val) { 
					//link += '<a target="_blank" href="${path+'administrador/regventas/imprimirVenta/'+val.cod_vent}" class="btn btn-info btn-md">Boleta ${val.cod_vent}</a>';
					link =link+'<tr>';
					link =link+'<td><a target="_blank" href="'+path+'administrador/regventas/imprimirVenta/'+val.archivoxml_vent+'"><span class="fas fa-eye text-primary"></a></td>';
					link =link+'<td>'+val.doc_cliente+'</td>';
					link =link+'<td>'+val.nomb_cliente+'</td>';
					link =link+'<td>'+val.cod_talonario+'-'+val.numero_vent+'</td>';
					link =link+'<td>'+val.fecha_vent+'</td>';
					link =link+'<td>'+val.moneda_vent+'</td>';
					link =link+'<td>'+val.total_vent+'</td>';
					link =link+'</tr>';				
				});
				$('#lista-boletas').html(link);
				$('#TableBoletas').DataTable();
				});
				$('#lista-boletas').html(link);
			},
			"JSON"
		);
	});
	/*=====  End of RESUMEN DE BOLETAS      ======*/



	/*==============================================
	=                BAJA SUNAT                    =
	==============================================*/
	$('#TableBajas').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": true,
		"processing": true,
		"serverSide": false,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[2, 'desc']],
	});

	$('.baja-sunat').click(function () {
		var id = $(this).data('id');
		$('#FormBajaSunat input[name=id]').val(id);
		$('#ModalBajaSunat').modal();
	});

	$('#FormBajaSunat').validate({
		ignore: [],
		rules: {
			motivo: { required: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormBajaSunat', () => {
				//$('#ModalAgregarResumen').trigger('click');
				//$('#TableAsignarPuntoVenta').DataTable().ajax.reload();
			})
		}
	});
	/*=====  End of    BAJA SUNAT       ======*/


	/*==============================================
	=               GUIA DE REMISION              =
	==============================================*/
	$('#TableGuia').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": true,
		"processing": true,
		"serverSide": false,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[3, 'desc']],
	});

	$('.guia-remision').click(function () {
		var id = $(this).data('id');
		$('#FormGuiaRemision input[name=id]').val(id);
		$('#ModalGuiaRemision').modal();
	});

	$('#FormGuiaRemision').validate({
		ignore: [],
		rules: {
			nota: { required: true },
			motivo: { required: true },
			peso: { required: true },
			num_paquetes: { required: true },
			peso: { required: true },
			tipo_transportista: { required: true },
			doc_transporte: { required: true },
			num_doc_transporte: {
				required: true, number: true, minlength: 11
			},
			razon_social_transporte: { required: true },
			// ubigeo_partida: { required: true, number: true, minlength: 6, maxlength: 6 },
			ubigeo_partida: { required: true},
			direccion_partida: { required: true },
			// ubigeo_destino: { required: true, number: true, minlength: 6, maxlength: 6 },
			ubigeo_destino: { required: true},
			direccion_destino: { required: true }
		},
		messages: {
			ubigeo_partida: {
				minlength: 'Deben ser 6 dígitos',
				maxlength: 'Deben ser 6 dígitos',
			},
			ubigeo_destino: {
				minlength: 'Deben ser 6 dígitos',
				maxlength: 'Deben ser 6 dígitos',
			}
		},
		submitHandler: function () {
			enviarFormulario('#FormGuiaRemision', () => {
				//$('#ModalAgregarResumen').trigger('click');
				//$('#TableAsignarPuntoVenta').DataTable().ajax.reload();
			})
		}
	});

	$('#FormGuiaRemision select[name=doc_transporte]').change(function (e) {
		e.preventDefault();
		var val = $(this).val();
		if (val == 6) {
			$("input[name=num_doc_transporte]").rules("remove", 'minlength');
			$("input[name=num_doc_transporte]").rules("add", { minlength: 11 });
		} else {
			$("input[name=num_doc_transporte]").rules("remove", 'minlength');
			$("input[name=num_doc_transporte]").rules("add", { minlength: 8 });
		}
	});
	/*=====  End of GUIA DE REMISION     ======*/


	/* ============================================ */
	/*                NOTA DE DEBITO                */
	/* ============================================ */
	$('#TableNota').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": true,
		"processing": true,
		"serverSide": false,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[2, 'desc']],
	});

	$("#DebitoProductoAutocomplete").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			var moneda = $('input[name=moneda]').val();
			
			var cambio = $('input[name='+moneda+']').val();
			return path + "administrador/regdocumentoelectronico/getProductoBusqueda?cambio=" + cambio + "&producto=" + query
		},
		getValue: function (element) {
			return element.nombre;
		},
		list: {
			onSelectItemEvent: function () {
				var selectedItemValue = $("#DebitoProductoAutocomplete").getSelectedItemData();
				$('input[name=precioProducto]').val(round(selectedItemValue.venta, 2));
				$('input[name=producto]').val(selectedItemValue.id);
			}
		}
	});

	var id_array = [];
	$('#cargarNotaDebitoProducto').click(function (e) { 
		e.preventDefault();
		if ($('input[name=producto]').val()=='' || $('input[name=precioProducto]').val()=='' || $('input[name=cantidadProducto]').val()=='') {
			Swal.fire({
				title: "Error",
				text: "Debes de seleccionar un producto o servicio",
				type: "error"
			});
			return;	
		}

		var producto = $('input[name=producto]').val();	
		var moneda = $('input[name=moneda]').val();	
		var cambio = $('input[name='+moneda+']').val();
		var cantidad = $('input[name=cantidadProducto]').val();
		$.getJSON(path + 'administrador/regdocumentoelectronico/getProducto', { producto, cantidad, cambio }, function (resp, textStatus) {
			if ($('#TableDebitoProductos tbody tr').length > 0) {
				$('#TableDebitoProductos tr').each(function () {
					var data_id = parseInt($(this).data('id'));
					id_array.push(data_id);
				});
				if (id_array.includes(parseInt(resp.response.cod_producto))) {
					$('#prod-' + resp.response.cod_producto).remove();
				}
			}

			var tr = `
			<tr id="prod-${resp.response.cod_producto}" data-id="${resp.response.cod_producto}">
				<input type="hidden" name="id_prod[]" value="${resp.response.cod_producto}"/>
				<td>${resp.response.cod_producto}</td>
				<td>${resp.response.nomb_product}</td>
				<td>${resp.response.nomb_marca}</td>
				<td>${resp.response.nomb_unid}</td>
				<td style="width:110px"><input type="number" class="cant form-control" name="cant_prod[]" value="${$('input[name=cantidadProducto]').val()}" min="1" step="1"/></td>
				<td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[]" value="${round($('input[name=precioProducto]').val(), 2)}"></td>
				<td></td>
				<td></td>
				<td></td>
				<td>
					<div class="btn-group btn-group-justified m-b-10">
							<button data-id="${resp.response.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
					</div>
				</td>
			</tr>
		`;
			$('#TableDebitoProductos tbody').append(tr);
			$('input[name=producto]').val('');
			$('input[name=nombreProducto]').val('');
			$('input[name=precioProducto]').val('');
			$('input[name=cantidadProducto]').val('');

			calcularTotalDebito();
			
		});

	});


	$('.nota-debito').click(function () {
		var id = $(this).data('id');
		$('#FormNotaDebito')[0].reset();
		$('#FormNotaDebito input[name=id]').val(id);
		$('#ModalNotaDebito').modal();

		$.getJSON(path+"administrador/regdocumentoelectronico/getVenta", {id},
			function (data, textStatus, jqXHR) {
				$('#FormNotaDebito input[name=cliente]').val(data.nomb_cliente);
				$('#FormNotaDebito input[name=doc_cliente]').val(data.nom_tipdocucli);
				$('#FormNotaDebito input[name=num_doc_cliente]').val(data.doc_cliente);
				$('#FormNotaDebito input[name=direccion_cliente]').val(data.direc_cliente);
				$('#FormNotaDebito input[name=telefono_cliente]').val(data.telf_cliente);
				$('#FormNotaDebito input[name=email_cliente]').val(data.email_cliente);
				$('#FormNotaDebito input[name=num_fact_modificado]').val(data.serie+'-'+data.numero_vent);
				$('#FormNotaDebito input[name=moneda]').val(data.codmoneda_vent);
				
				var tr = '';
				$.each(data.detalle, function (index, val) { 
					
					tr += `
									<tr id="prod-${val.cod_producto}" data-id="${val.cod_producto}">
										<input type="hidden" name="id_prod[]" value="${val.cod_ventdet }"/>
										<input type="hidden" name="id_detalle[]" value="${val.cod_ventdet}"/>
										<td>${(val.cod_producto==null)?val.cod_servicio:val.cod_producto}</td>
										<td>${val.producto_ventdet}</td>
										<td>${val.nomb_marca}</td>
										<td>${val.nomb_unid}</td>
										<td style="width:110px"><input type="number" class="cant form-control" name="cant_prod[]" value="${val.cant_ventdet}" min="1" step="1"/></td>
										<td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[]" value="${parseFloat(val.precunit_ventdet) - parseFloat(val.descuento_ventdet)}"></td>
										<td></td>
										<td></td>
										<td></td>
										<td>
											<div class="btn-group btn-group-justified m-b-10">
												<button data-id="${val.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
											</div>
										</td>
									</tr>
								`;

				});

				$('#TableDebitoProductos tbody').html(tr);
				calcularTotalDebito();

			}
		);

	});

	$('#TableDebitoProductos tbody').on('click', '.removerProducto', function (event) {
		event.preventDefault();
		$('#prod-' + $(this).data('id')).remove();
		calcularTotalDebito();
	});

	$("#TableDebitoProductos").on('focusout', 'input[name="cant_prod[]"], input[name="prec_prod[]"]', function () {
		calcularTotalDebito();
	});

	$('#TableDebitoProductos').on('focusout', '.prec', function (event) {
		event.preventDefault();
		calcularTotalDebito();
	});

	function calcularTotalDebito() {
		var total = 0;
		var igv = 18;
		var igv_porcentaje = (igv / 100);
		if ($('#TableDebitoProductos tbody tr').length > 0)
			$('#TableDebitoProductos tbody tr').each(function () {
				var cant = parseFloat($(this).find('.cant').val());
				if (isNaN(cant)) {
					var cant = parseFloat($(this).find('.cant').text());
				}
				var prec = parseFloat($(this).find('.prec').val());

				var subTotalProd = round((cant * prec), 2);
				var igvProd = subTotalProd / (igv_porcentaje + 1);
				igvProd = round(igvProd * igv_porcentaje, 2);
				let valorVentaProd = round(subTotalProd - igvProd, 2);
				$(this).find('td').eq(6).html(igvProd);
				$(this).find('td').eq(7).html(valorVentaProd);

				$(this).find('td').eq(8).html(round(subTotalProd, 2));
				total += parseFloat(subTotalProd);
			});

		var IGV = total / (igv_porcentaje + 1);
		IGV = round(IGV * igv_porcentaje, 2);

		let valorVenta = round(total - IGV, 2);

		$('#DebitoValorVenta').html(valorVenta);
		$('#DebitoIGV').html(IGV);
		$('#DebitoTotal').html(round(total, 2));
		$('input[name=total]').val(round(total, 2));

	}


	$('#FormNotaDebito').validate({
		ignore: [],
		rules: {
		
		},
		submitHandler: function () {

			var num = $('#TableDebitoProductos tbody tr').length;
			if (num == 0) {
				Swal.fire({
					title: "Error",
					text: "No hay ningun producto",
					type: "error"
				});
				return;
			}
			enviarFormulario('#FormNotaDebito', () => {
				
			})
		}
	});
	/* ============================================ */
	/*              FIN NOTA DE DEBITO              */
	/* ============================================ */


/* ============================================ */
/*                NOTA DE CRÉDITO               */
/* ============================================ */

$("#CreditoProductoAutocomplete").easyAutocomplete({
	minCharNumber: 2,
	url: function (query) {
		var moneda = $('input[name=moneda]').val();
		
		var cambio = $('input[name='+moneda+']').val();
		return path + "administrador/regdocumentoelectronico/getProductoBusqueda?cambio=" + cambio + "&producto=" + query
	},
	getValue: function (element) {
		return element.nombre;
	},
	list: {
		onSelectItemEvent: function () {
			var selectedItemValue = $("#CreditoProductoAutocomplete").getSelectedItemData();
			$('input[name=precioProducto]').val(round(selectedItemValue.venta, 2));
			$('input[name=producto]').val(selectedItemValue.id);
		}
	}
});

var id_array = [];
$('#cargarNotaCreditoProducto').click(function (e) { 
	e.preventDefault();
	if ($('input[name=producto]').val()=='' || $('input[name=precioProducto]').val()=='' || $('input[name=cantidadProducto]').val()=='') {
		Swal.fire({
			title: "Error",
			text: "Debes de seleccionar un producto o servicio",
			type: "error"
		});
		return;	
	}

	var producto = $('input[name=producto]').val();	
	var moneda = $('input[name=moneda]').val();	
	var cambio = $('input[name='+moneda+']').val();
	var cantidad = $('input[name=cantidadProducto]').val();
	$.getJSON(path + 'administrador/regdocumentoelectronico/getProducto', { producto, cantidad, cambio }, function (resp, textStatus) {
		if ($('#TableCreditoProductos tbody tr').length > 0) {
			$('#TableCreditoProductos tr').each(function () {
				var data_id = parseInt($(this).data('id'));
				id_array.push(data_id);
			});
			if (id_array.includes(parseInt(resp.response.cod_producto))) {
				$('#prod-' + resp.response.cod_producto).remove();
			}
		}

		var tr = `
		<tr id="prod-${resp.response.cod_producto}" data-id="${resp.response.cod_producto}">
			<input type="hidden" name="id_prod[]" value="${resp.response.cod_producto}"/>
			<td>${resp.response.cod_producto}</td>
			<td>${resp.response.nomb_product}</td>
			<td>${resp.response.nomb_marca}</td>
			<td>${resp.response.nomb_unid}</td>
			<td style="width:110px"><input type="number" class="cant form-control" name="cant_prod[]" value="${$('input[name=cantidadProducto]').val()}" min="1" step="1"/></td>
			<td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[]" value="${round($('input[name=precioProducto]').val(), 2)}"></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<div class="btn-group btn-group-justified m-b-10">
						<button data-id="${resp.response.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
				</div>
			</td>
		</tr>
	`;
		$('#TableCreditoProductos tbody').append(tr);
		$('input[name=producto]').val('');
		$('input[name=nombreProducto]').val('');
		$('input[name=precioProducto]').val('');
		$('input[name=cantidadProducto]').val('');

		calcularTotalCredito();
		
	});

});


$('.nota-credito').click(function () {
	var id = $(this).data('id');
	$('#FormNotaCredito')[0].reset();
	$('#FormNotaCredito input[name=id]').val(id);
	$('#ModalNotaCredito').modal();

	$.getJSON(path+"administrador/regdocumentoelectronico/getVenta", {id},
		function (data, textStatus, jqXHR) {
			$('#FormNotaCredito input[name=cliente]').val(data.nomb_cliente);
			$('#FormNotaCredito input[name=doc_cliente]').val(data.nom_tipdocucli);
			$('#FormNotaCredito input[name=num_doc_cliente]').val(data.doc_cliente);
			$('#FormNotaCredito input[name=direccion_cliente]').val(data.direc_cliente);
			$('#FormNotaCredito input[name=telefono_cliente]').val(data.telf_cliente);
			$('#FormNotaCredito input[name=email_cliente]').val(data.email_cliente);
			$('#FormNotaCredito input[name=num_fact_modificado]').val(data.serie+'-'+data.numero_vent);
			$('#FormNotaCredito input[name=moneda]').val(data.codmoneda_vent);
			
			var tr = '';
			$.each(data.detalle, function (index, val) { 
				
				tr += `
								<tr id="prod-${val.cod_producto}" data-id="${val.cod_producto}">
									<input type="hidden" name="id_prod[]" value="${val.cod_producto}"/>
									<input type="hidden" name="id_detalle[]" value="${val.cod_ventdet}"/>
									<td>${(val.cod_producto==null)?val.cod_servicio:val.cod_producto}</td>
									<td>${val.producto_ventdet}</td>
									<td>${val.nomb_marca}</td>
									<td>${val.nomb_unid}</td>
									<td style="width:110px"><input type="number" class="cant form-control" name="cant_prod[]" value="${val.cant_ventdet}" min="1" step="1"/></td>
									<td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[]" value="${parseFloat(val.precunit_ventdet) - parseFloat(val.descuento_ventdet)}"></td>
									<td></td>
									<td></td>
									<td></td>
									<td>
										<div class="btn-group btn-group-justified m-b-10">
											<button data-id="${val.cod_producto}" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
										</div>
									</td>
								</tr>
							`;

			});

			$('#TableCreditoProductos tbody').html(tr);
			calcularTotalCredito();

		}
	);

});

$('#TableCreditoProductos tbody').on('click', '.removerProducto', function (event) {
	event.preventDefault();
	$('#prod-' + $(this).data('id')).remove();
	calcularTotalCredito();
});

	$("#TableCreditoProductos").on('focusout', 'input[name="cant_prod[]"], input[name="prec_prod[]"]', function () {
		calcularTotalCredito();
	});

	$('#TableCreditoProductos').on('focusout', '.prec', function (event) {
		event.preventDefault();
		calcularTotalCredito();
	});

	function calcularTotalCredito() {
		var total = 0;
		var igv = 18;
		var igv_porcentaje = (igv / 100);
		if ($('#TableCreditoProductos tbody tr').length > 0)
			$('#TableCreditoProductos tbody tr').each(function () {
				var cant = parseFloat($(this).find('.cant').val());
				if (isNaN(cant)) {
					var cant = parseFloat($(this).find('.cant').text());
				}
				var prec = parseFloat($(this).find('.prec').val());

				var subTotalProd = round((cant * prec), 2);
				var igvProd = subTotalProd / (igv_porcentaje + 1);
				igvProd = round(igvProd * igv_porcentaje, 2);
				let valorVentaProd = round(subTotalProd - igvProd, 2);
				$(this).find('td').eq(6).html(igvProd);
				$(this).find('td').eq(7).html(valorVentaProd);

				$(this).find('td').eq(8).html(round(subTotalProd, 2));
				total += parseFloat(subTotalProd);
			});

		var IGV = total / (igv_porcentaje + 1);
		IGV = round(IGV * igv_porcentaje, 2);

		let valorVenta = round(total - IGV, 2);

		$('#CreditoValorVenta').html(valorVenta);
		$('#CreditoIGV').html(IGV);
		$('#CreditoTotal').html(round(total, 2));
		$('input[name=total]').val(round(total, 2));

	}


	$('#FormNotaCredito').validate({
		ignore: [],
		rules: {
		
		},
		submitHandler: function () {

			var num = $('#TableCreditoProductos tbody tr').length;
			if (num == 0) {
				Swal.fire({
					title: "Error",
					text: "No hay ningun producto",
					type: "error"
				});
				return;
			}
			enviarFormulario('#FormNotaCredito', () => {
				
			})
		}
	});
/* ============================================ */
/*           	FIN NOTA DE CRÉDITO               */
/* ============================================ */


	/*==============================================
	=            ASIGNAR PUNTO DE VENTA            =
	==============================================*/
	var TableAsignarPuntoVenta = $('#TableAsignarPuntoVenta').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regasignpuntoventa/jsonAsignarPuntoVenta',
			"type": "POST",
			"data": function (d) {
				d.grupo = $("select[name=grupo]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false }
		]
	});

	$('#FormAsignaPuntoVentaFiltro select[name=grupo]').change(function (event) {
		$('#TableAsignarPuntoVenta').DataTable().ajax.reload();
	});

	$('#FormAgregarPuntoVentaUsuario').validate({
		ignore: [],
		rules: {
			usuario: { required: true },
			punto: { required: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormAgregarPuntoVentaUsuario', () => {
				$('#ButtonAgregarPuntoVenta').trigger('click');
				pintarPuntoVenta($('#FormAgregarPuntoVentaUsuario input[name=usuario]').val());
				$('#TableAsignarPuntoVenta').DataTable().ajax.reload();
			})
		}
	});

	$('#ButtonAgregarPuntoVenta').click(function (event) {
		$(this).hide();
		$('#FormAgregarPuntoVentaUsuario').show();
		var usuario = $('#FormAgregarPuntoVentaUsuario input[name=usuario]').val();
		$.getJSON(path + 'administrador/regasignpuntoventa/getPuntosVentasParaAgregar', { usuario }, function (json, textStatus) {
			var option = '<option value="">Seleccione</option>';
			$.each(json, function (index, val) {
				option += `
				<option value="${val.cod_puntoventa}">${val.nomb_puntoventa}</option>
			`;
			});
			$('#FormAgregarPuntoVentaUsuario select[name=punto]').html(option);
		});
	});

	$('#TableAsignarPuntoVenta tbody').on('click', '.asignarPuntoVenta', function (event) {
		event.preventDefault();
		$('#ButtonAgregarPuntoVenta').show();
		$('#FormAgregarPuntoVentaUsuario').hide();
		var id = $(this).data('id');
		var usuario = $(this).parent().parent().find('td').eq(1).text();
		$('#FormAgregarPuntoVentaUsuario input[name=usuario]').val(id);
		$('#NombreUsuario').text(usuario);
		pintarPuntoVenta(id);
	});

	function pintarPuntoVenta(id) {
		$('#TableAsignarPuntoVentaUsuario tbody').empty();
		$.getJSON(path + 'administrador/regasignpuntoventa/getPuntos', { id }, function (json, textStatus) {
			var tr = '';
			$.each(json, function (index, val) {
				if (val.pordefecto == 1) {
					var defecto = '<button class="btn btn-sm btn-info"><i class="fa fa-star"></i></button> Por Defecto';
					var quitar = '';
				} else {
					var defecto = `<button data-usuario="${val.cod_usu}" data-punto="${val.cod_puntoventa}" class="btn btn-sm btn-primary habilitarPorDefecto">Habilitar</button>`;
					var quitar = `<button type="button" data-usuario="${val.cod_usu}" data-punto="${val.cod_puntoventa}" class="btn btn-danger btn-sm quitar">Quitar</button>`;
				}
				tr += `
				<tr>
					<td>${val.nomb_puntoventa}</td>
					<td>${defecto}</td>
					<td>${quitar}</td>
				</tr>
			`;
			});
			$('#TableAsignarPuntoVentaUsuario tbody').html(tr);
		});
	}

	$('#TableAsignarPuntoVentaUsuario tbody').on('click', '.habilitarPorDefecto', function (event) {
		event.preventDefault();
		var usuario = $(this).data('usuario');
		var punto = $(this).data('punto');
		$.getJSON(path + 'administrador/regasignpuntoventa/cambiarPuntoVentaDefecto', { usuario, punto }, function (json, textStatus) {
			if (json.success) {
				pintarPuntoVenta(usuario);
			} else {
				Swal.fire({
					title: "Error",
					text: "El monto no puede ser mayor que " + monto,
					type: "error",
				});
			}
		});
	});

	$('#TableAsignarPuntoVentaUsuario').on('click', '.quitar', function (event) {
		event.preventDefault();
		var usuario = $(this).data('usuario');
		var punto = $(this).data('punto');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Confirma que desea quitar este punto de venta?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regasignpuntoventa/quitarPuntoVenta', { usuario, punto }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se quito correctamente.",
							type: "success"
						});
						pintarPuntoVenta(usuario);
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});
	/*=====  End of ASIGNAR PUNTO DE VENTA  ======*/


	/*=================================
	=            TRASPASOS            =
	=================================*/
	var TableTraspasos = $('#TableTraspasos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regtraspasos/jsonTraspasos',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.origen = $("select[name=origen]").val();
				d.destino = $("select[name=destino]").val();
			}
		},
		"columns": [
			{ "orderable": false, "className": 'details-control' },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false }

		]
	});

	$('#TableTraspasos tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = TableTraspasos.row(tr);

		if (row.child.isShown()) {
			row.child.hide();
			tr.removeClass('shown');
			$(this).find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
		} else {
			row.child(formatTraspasoDetalle(row.data())).show();
			tr.addClass('shown');
			$(this).find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
		}
	});

	function formatTraspasoDetalle(d) {
		var query = jQuery.parseJSON(d[10]);

		var table = `
	
		<table class="table table-bordered">
			<thead>
				<tr class="table-danger">
					<th style="text-align: center;">Codigo</th>
					<th style="text-align: center;">Producto</th>
					<th style="text-align: center;">Cant.</th>
					<th style="text-align: center;">Serie</th>
				</tr>
			</thead>
			<tbody>
		`;
		var tr = '';
		var i = 1;
		$.each(query, function (index, val) {
			tr += `
			<tr>
				<td>${ i}</td>
				<td>${val.nomb_product}</td>
				<td>${val.cant_trasdet}</td>
				<td>${(val.serie_trasdet!=null)?val.serie_trasdet:''}</td>
			</tr>
		 `;
			i++;
		});

		table += tr;
		table += `
			</tbody>
		</table>`;
		return table;
	}

	$('#TraspasosReportePdf').click(function (event) {
		let form = $('#FormTraspasosFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regtraspasos/reportePdf?' + params);
	});

	$('#TraspasosReporteExcel').click(function (event) {
		let form = $('#FormTraspasosFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regtraspasos/reporteExcel?' + params);
	});

	$('#FormTraspasosFiltro').validate({
		ignore: [],
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#TableTraspasos').DataTable().ajax.reload();
		}
	});

	$('#FormAgregarTraspasos select[name=origen]').change(function (event) {
		var origen = $(this).val();
		$('#TableTraspasosProductos tbody').empty();
		$.getJSON(path + 'administrador/regtraspasos/getDestinos', { origen }, function (json, textStatus) {
			var option = '<option value="">Seleccione</option>';
			$.each(json, function (index, val) {
				option += `
			<option value="${val.cod_almacen}">${val.nomb_almacen}</option>
			`;
			});
			$('#FormAgregarTraspasos select[name=destino]').html(option);
		});
	});

	$("#nombreProductoTraspasoAutocomplete").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			return path + "administrador/regtraspasos/getProductoBusqueda?producto=" + query
		},
		getValue: function (element) {
			return element.nombre;
		},
		list: {
			onSelectItemEvent: function () {
				var selectedItemValue = $("#nombreProductoTraspasoAutocomplete").getSelectedItemData();
				$('input[name=unidadProducto]').val(selectedItemValue.unidad);
				$('input[name=precioProducto]').val(selectedItemValue.venta);
				$('input[name=producto]').val(selectedItemValue.id);

			}
		}
	});

	$('#FormAgregarTraspasos').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			origen: { required: true },
			destino: { required: true }
		},
		submitHandler: function () {
			if ($('#TableTraspasosProductos tbody tr').length == 0) {
				$('#FormTraspasosAgregarProducto').valid();
				return;
			}

			var traspaso = $('#FormAgregarTraspasos').serializeObject();
			var productos = $('#FormTraspasosAgregarProducto').serializeObject();
			jQuery.extend(traspaso, productos);

			$.ajax({
				url: path + 'administrador/regtraspasos/agregarTraspaso',
				type: 'POST',
				dataType: 'JSON',
				data: traspaso
			})
				.done(function (resp) {
					if (resp.success) {
							Swal.fire({
							title: "Buen trabajo!",
							text: "El traspaso se realizo correctamente",
							type: "success"
						});
						setTimeout(() => {
							window.location.href = path + 'administrador/regtraspasos';
						}, 3000);
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
		}
	});

	var id_array = [];
	$('#FormTraspasosAgregarProducto').validate({
		ignore: [],
		rules: {
			nombreProducto: { required: true },
			cantidadProducto: { required: true }

		},
		submitHandler: function () {
			if (!$('#FormAgregarTraspasos').valid()) {
				Swal.fire({
					title: "Error",
					text: "Complete los datos de origen y destino.",
					type: "error"
				});
				return;
			}
				var series = [];
			if($('#checkbox-serie:checked').length){
				for (i = 0; i < $('input[name=cantidadProducto]').val(); i++) {
					let serie = window.prompt("Escriba la serie",'Ingrese la serie');
					series.push(serie);
				}
			}

			var origen = $('select[name=origen]').val();
			var destino = $('select[name=destino]').val();
			var producto = $('input[name=producto]').val();
			var cantidad = $('input[name=cantidadProducto]').val();
			$.getJSON(path + 'administrador/regtraspasos/verificaCantidadTraspaso', { producto, cantidad, origen, destino, series }, function (json, textStatus) {
				if (json.success && json.destino) {
					// if (json.success) {
					if ($('#TableTraspasosProductos tbody tr').length > 0) {
						$('#TableTraspasosProductos tr').each(function () {
							var data_id = parseInt($(this).data('id'));
							id_array.push(data_id);
						});
						if (id_array.includes(parseInt(json.producto.cod_producto))) {
							$('#prod-' + json.producto.cod_producto).remove();
						}
						}
          
					var seriesBadge = '';
					if($('#checkbox-serie:checked').length){
						
						if(json.series.length==0){
							Swal.fire({
								title: "Error",
								text: "No se encontro ninguna serie",
								type: "error"
							});
							return false;
						}
						$.each(json.series, function (indexSeries, valueSeries) { 
							seriesBadge += `
							<input type="hidden" name="serie_producto[${json.producto.cod_producto}]" value="${valueSeries}">
							<span class="badge badge-info">${valueSeries}</span>`;
						});
					}
					var tr = `
					<tr data-id="${json.producto.cod_producto}" id="prod-${json.producto.cod_producto}">
						<td>
						<input type="hidden" name="id_producto[]" value="${json.producto.cod_producto}" />
						${json.producto.cod_producto}</td>						
						<td>${json.producto.nomb_product + seriesBadge}</td>
						<td>${json.producto.nomb_unid}</td>						
						<td><input type="number" name="cant_producto[]" value="${(json.series.length > 0)?json.series.length:$('input[name=cantidadProducto]').val() }" class="form-control" readonly></td>
						<td><button data-id="${json.producto.cod_producto}" class="btn btn-sm btn-danger removerProducto"><i class="fa fa-trash"></i></button></td>
					</tr>
				`;

					$('#TableTraspasosProductos tbody').append(tr);

				} else {
					if (json.destino) {
						Swal.fire({
							title: "Error",
							text: json.mensaje,
							type: "error"
						});
					} else {
						Swal.fire({
							title: "Error",
							text: json.mensaje_destino,
							type: "error"
						});
					}
				}
			});
		}
	});

	$('#TableTraspasosProductos tbody').on('click', '.removerProducto', function (event) {
		event.preventDefault();
		$('#prod-' + $(this).data('id')).remove();
	});


	$('#FormEditarTraspasos').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			origen: { required: true },
			destino: { required: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormEditarTraspasos', function (data) {
				console.log(data);
			})
		}
	});
	/*=====  End of TRASPASOS  ======*/


	/*=========================================
	=            CUENTAS POR PAGAR            =
	=========================================*/
	var TableCuentasPagar = $('#TableCuentasPagar').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcuentaspagar/jsonCuentas',
			"type": "POST",
			"data": function (d) {
				d.proveedor = $("input[name=proveedor]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
		]
	});

	$('#FormCuentasPagarFiltro').validate({
		ignore: [],
		rules: {
		},
		submitHandler: function () {
			$('#TableCuentasPagar').DataTable().ajax.reload();
		}
	});

	$('#CuentasPagarReportePdf').click(function (event) {
		let form = $('#FormCuentasPagarFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regcuentaspagar/reportePdf?' + params);
	});

	$('#CuentasPagarReporteExcel').click(function (event) {
		let form = $('#FormCuentasPagarFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regcuentaspagar/reporteExcel?' + params);
	});

	$('.CuentasPagar').click(function (event) {
		var id = $(this).data('id');
		$('#ModalCuentasPagar').modal();
		$.getJSON(path + 'administrador/regcuentaspagar/getCuenta', { id }, function (json, textStatus) {
			$('#FormCuentasPagar input[name=compra]').val(json.cod_comp);
			$('#FormCuentasPagar input[name=proveedor]').val(json.tb_proveedor_id);
			$('#FormCuentasPagar input[name=ruc]').val(json.tb_proveedor_doc);
			$('#FormCuentasPagar input[name=anexo]').val(json.tb_proveedor_nom);
			$('#FormCuentasPagar textarea[name=detalle]').val('PAGO COMPRA: ' + json.numdocumento_comp);
			$('#FormCuentasPagar input[name=importe]').val(json.saldo);
			$('#FormCuentasPagar input[name=saldo]').val(json.saldo);
			$('#FormCuentasPagar input[name=saldo]').data('saldo', json.saldo);
		});
	});

	$('#FormCuentasPagar input[name=importe]').focusout(function (event) {
		var importe = parseFloat($('input[name=importe]').val());
		var saldo = parseFloat($('input[name=saldo]').data('saldo'));
		saldo -= importe;
		$('input[name=saldo]').val(round(saldo, 2));
	});

	$('#FormCuentasPagar').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			caja: { required: true },
			detalle: { required: true },
			importe: { required: true, decimal: true, min: 1 }
		},
		submitHandler: function () {
			var saldo = parseFloat($('input[name=saldo]').data('saldo'));
			if (saldo < 0) {
				Swal.fire({
					title: "Error",
					text: 'El saldo no puede ser menor que 0 (cero)',
					type: "error"
				});
				return;
			}
			enviarFormulario('#FormCuentasPagar', function (data) {

			})
		}
	});
	/*=====  End of CUENTAS POR PAGAR  ======*/


	/*==========================================
	=            CUENTAS POR COBRAR            =
	==========================================*/
	var TableCuentasCobrar = $('#TableCuentasCobrar').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcuentascobrar/jsonCuentas',
			"type": "POST",
			"data": function (d) {
				d.cliente = $("input[name=cliente]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
		]
	});

	$('#FormCuentasCobrarFiltro').validate({
		ignore: [],
		rules: {
		},
		submitHandler: function () {
			$('#TableCuentasCobrar').DataTable().ajax.reload();
		}
	});

	$('#CuentasCobrarReportePdf').click(function (event) {
		let form = $('#FormCuentasCobrarFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regcuentascobrar/reportePdf?' + params);
	});

	$('#CuentasCobrarReporteExcel').click(function (event) {
		let form = $('#FormCuentasCobrarFiltro').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regcuentascobrar/reporteExcel?' + params);
	});

		$('.detalle-cuentacobrar').click(function (event) {
		var id = $(this).data('id');
		$('#ModalDetalleVenta').modal();
		$.getJSON(path + 'administrador/regcuentascobrar/getVentaDetalle', { id }, function (json, textStatus) {
			
			var tr = '';
			$.each(json.detalle, function (index, val) { 
				tr += `
					<tr>
						<td>${ val.nomb_product }</td>
						<td>${ val.nomb_marca }</td>
						<td>${ val.nomb_unid }</td>
						<td>${ val.precunit_ventdet } </td>
						<td>${ val.cant_ventdet }</td>
						<td>${ val.descuento_ventdet }</td>
						<td>${ val.subtotal_ventdet }</td>
					</tr>
				`;
			});
			$('#cuentacobrardetalle-Total').html(json.total_vent);
			$('#table-ventadetalle-cuentacobrar tbody').html(tr);
		});
	});

	$('.CuentasCobrar').click(function (event) {
		var id = $(this).data('id');
		$('#ModalCuentasCobrar').modal();
		$.getJSON(path + 'administrador/regcuentascobrar/getCuenta', { id }, function (json, textStatus) {
			$('#FormCuentasCobrar input[name=venta]').val(json.cod_vent);
			$('#FormCuentasCobrar input[name=cliente]').val(json.id_cliente);
			$('#FormCuentasCobrar input[name=ruc]').val(json.doc_cliente);
			$('#FormCuentasCobrar input[name=anexo]').val(json.nomb_cliente);
			$('#FormCuentasCobrar textarea[name=detalle]').val('PAGO VENTA: ' + json.nom_tipdocumento + '-' + json.serie + '-' + json.numero_vent);
			$('#FormCuentasCobrar input[name=importe]').val(json.saldo);
			$('#FormCuentasCobrar input[name=saldo]').val(json.saldo);
			$('#FormCuentasCobrar input[name=saldo]').data('saldo', json.saldo);
		});
	});

	$('#FormCuentasCobrar input[name=importe]').focusout(function (event) {
		var importe = parseFloat($('input[name=importe]').val());
		var saldo = parseFloat($('input[name=saldo]').data('saldo'));
		saldo -= importe;
		$('input[name=saldo]').val(round(saldo, 2));
	});

	$('#FormCuentasCobrar').validate({
		ignore: [],
		rules: {
			fecha: { required: true },
			caja: { required: true },
			detalle: { required: true },
			importe: { required: true, decimal: true, min: 1 }
		},
		submitHandler: function () {
			var saldo = parseFloat($('input[name=saldo]').data('saldo'));
			if (saldo < 0) {
				Swal.fire({
					title: "Error",
					text: 'El saldo no puede ser menor que 0 (cero)',
					type: "error"
				});
				return;
			}
			enviarFormulario('#FormCuentasCobrar', function (data) {

			})
		}
	});
	/*=====  End of CUENTAS POR COBRAR  ======*/



	/*==============================================
	=            LISTA DE PROVEEDORES            =
	==============================================*/
	var TableListarProveedor = $('#TableListarProveedor').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regproveedor/jsonProveedores',
			"type": "POST",
			"data": function (d) {
				d.tipo = $("select[name=tipo]").val();
				d.nombre = $("input[name=nombre").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false }
		]
	});


	$('#ProveedorReportePdf').click(function (event) {
		let form = $('#FormProveedorBuscar').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/reginventarioinicial/reportePdf?' + params);
	});

	$('#ProveedorReporteExcel').click(function (event) {
		let form = $('#FormProveedorBuscar').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/reginventarioinicial/reporteExcel?' + params);
	});

	$('#FormProveedorBuscar select[name=tipo]').change(function (event) {
		$('#TableListarProveedor').DataTable().ajax.reload();
	});

	$('#FormProveedorBuscar').validate({
		submitHandler: function () {
			$('#TableListarProveedor').DataTable().ajax.reload();
		}
	});

	$('#FormAgregarProveedor').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			documento: { required: true },
			telefono: { required: true },
			direccion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormAgregarProveedor', function (json) {
				if (json.success) {
					$('#TableListarProveedor').DataTable().ajax.reload();
				}
				$('#ModalAgregarProveedor').modal('hide');
				$('FormAgregarProveedor select[name=tipo]').select('val', '');
			})
		}
	});


	/*==========================================
			 PROVEEDOR - EDITAR
	===========================================*/

	$('#TableListarProveedor').on('click', '.editar-proveedor', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regproveedor/getProveedor', { id }, function (json, textStatus) {
			$('#FormEditarProveedor input[name=id]').val(json.tb_proveedor_id);
			$('#FormEditarProveedor select[name=tipo]').val(json.tb_proveedor_tip);
			$('#FormEditarProveedor input[name=nombre]').val(json.tb_proveedor_nom);
			$('#FormEditarProveedor input[name=documento]').val(json.tb_proveedor_doc);
			$('#FormEditarProveedor input[name=telefono]').val(json.tb_proveedor_tel);
			$('#FormEditarProveedor input[name=direccion]').val(json.tb_proveedor_dir);
			$('#FormEditarProveedor input[name=contacto]').val(json.tb_proveedor_con);
			$('#FormEditarProveedor input[name=email]').val(json.tb_proveedor_ema);
			$('#FormEditarProveedor select[name=estado]').val(json.tb_proveedor_xac);

		});
	});

	$('#FormEditarProveedor').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			documento: { required: true },
			telefono: { required: true },
			direccion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarProveedor', function (json) {
				if (json.success) {
					$('#TableListarProveedor').DataTable().ajax.reload();
				}
				$('#ModalEditarProveedor').modal('hide');
				$('#FormEditarProveedor select[name=tipo]').select('val', '');
				$('#FormEditarProveedor input[name=nombre]').val('');
				$('#FormEditarProveedor input[name=documento]').val('val');
				$('#FormEditarProveedor input[name=direccion]').val('val');
				$('#FormEditarProveedor input[name=contacto]').val('val');
				$('#FormEditarProveedor input[name=email]').val('val');
				$('#FormEditarProveedor select[name=estado]').select('val', '');
			})
		}
	});

	/*==========================================
			 PROVEEDOR - ANULAR
	===========================================*/

	$('#TableListarProveedor').on('click', '.anular-proveedor', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Proveedor?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regproveedor/anularProveedor', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableListarProveedor').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});

	/*=========================================
	=            CLIENTE COBERTURA            =
	=========================================*/
	var TableClienteCobertura = $('#TableClienteCobertura').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regclientecobertura/jsonCobertura',
			"type": "POST",
			"data": function (d) {
				d.cliente = $("input[name=cliente").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false }
		]
	});

	$('#FormClienteCoberturaFiltro').validate({
		rules: {
			cliente: { required: true }
		},
		submitHandler: function () {
			$('#TableClienteCobertura').DataTable().ajax.reload();
		}
	});


	$('#FormCobertura select[name=cliente]').select2({
		ajax: {
			delay: 500,
			url: path + 'administrador/regclientecobertura/getClientes',
			dataType: 'json',
			data: function (params) {
				var queryParameters = {
					q: params.term
				}
				return queryParameters;
			},
			processResults: function (data) {
				return {
					results: data
				}
			},
			transport: function (params, success, failure) {
				if (typeof params['data']['q'] != "undefined") {
					if (params['data']['q'].length > 3) {
						var $request = $.ajax(params);
						$request.then(success);
						$request.fail(failure);
						return $request;
					}
				}
			}
		}
	});

	$('#FormCobertura').validate({
		rules: {
			inicio: { required: true },
			limite: { required: true },
			cliente: { required: true },
			monto: { required: true, number: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormCobertura', function (resp) {
				if (resp.success) {
					$('#ModalAgregarCobertura').modal('hide');
					$('#TableClienteCobertura').DataTable().ajax.reload();
				} else {

				}
			})
		}
	});

	$('#TableClienteCobertura tbody').on('click', '.editarCobertura', function (event) {
		event.preventDefault();
		$('#ModalEditarCobertura').modal();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regclientecobertura/getCobertura', { id }, function (json, textStatus) {
			$('#FormCoberturaEditar input[name=id]').val(json.id_cobertura);
			$('#FormCoberturaEditar input[name=inicio]').val(json.inicio_cobertura);
			$('#FormCoberturaEditar input[name=limite]').val(json.limite_cobertura);
			$('#FormCoberturaEditar input[name=cliente]').val(json.nomb_cliente);
			$('#FormCoberturaEditar input[name=monto]').val(json.monto_cobertura);

			if (json.amplicacion_cobertura == 1) {
				$('#FormCoberturaEditar input[name=cobertura]').prop('checked', true);
			} else {
				$('#FormCoberturaEditar input[name=cobertura]').prop('checked', false);
			}
		});
	});

	$('#FormCoberturaEditar').validate({
		rules: {
			inicio: { required: true },
			limite: { required: true },
			cliente: { required: true },
			monto: { required: true, number: true }
		},
		submitHandler: function () {
			enviarFormulario('#FormCoberturaEditar', function (resp) {
				if (resp.success) {
					$('#ModalEditarCobertura').modal('hide');
					$('#TableClienteCobertura').DataTable().ajax.reload();
				} else {

				}
			})
		}
	});

	$('#TableClienteCobertura').on('click', '.eliminar', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Eliminar registro?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regclientecobertura/eliminar', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableClienteCobertura').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});
	/*=====  End of CLIENTE COBERTURA  ======*/



	/*==============================================
	=            LISTA DE CLIENTES            =
	==============================================*/
	var TableListarClientes = $('#TableListarClientes').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regcliente/jsonClientes',
			"type": "POST",
			"data": function (d) {
				d.tipo = $("select[name=tipo]").val();
				d.nombre = $("input[name=nombre").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },

			{ "orderable": false }
		]
	});


	$('#FormClienteBuscar select[name=tipo]').change(function (event) {
		$('#TableListarClientes').DataTable().ajax.reload();
	});

	$('#FormClienteBuscar').validate({
		submitHandler: function () {
			$('#TableListarClientes').DataTable().ajax.reload();
		}
	});

	$('#FormAgregarCliente').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			documento: { required: true,
			remote:{
				url: path+"administrador/regcliente/validaClienteUnico",
				type: "POST",
				data: {
					documento: function() {
						return $("#FormAgregarCliente input[name=documento]").val();
					},
					id: function(){
						return $("input[name=codigo]").val();
					}
				}
			}
	 },
			// telefono: { required: true },
			direccion: { required: true }
					},
		messages:{
		documento:{
			remote:'Este número de documento ya existe'
		}

		},
		submitHandler: function () {
			enviarFormulario('#FormAgregarCliente', function (json) {
				if (json.success) {
					$('#TableListarClientes').DataTable().ajax.reload();
				}
				$('#ModalAgregarCliente').modal('hide');
				$('FormAgregarCliente select[name=tipo]').select('val', '');
				$('#FormAgregarCliente input[name=documento]').val('');
			})
		}
	});




	/*==========================================
			 CLIENTE - EDITAR
	===========================================*/

	$('#TableListarClientes').on('click', '.editar-cliente', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regcliente/getCliente', { id }, function (json, textStatus) {
			$('#FormEditarCliente input[name=id]').val(json.id_cliente);
			$('#FormEditarCliente select[name=tipo]').val(json.cod_tipdocucli);
			$('#FormEditarCliente input[name=nombre]').val(json.nomb_cliente);
			$('#FormEditarCliente input[name=fnacimiento]').val(json.fena_pac);
			$('#FormEditarCliente input[name=documento]').val(json.doc_cliente);
			$('#FormEditarCliente select[name=precio_venta]').val(json.precio_cliente);
			$('#FormEditarCliente input[name=telefono]').val(json.telf_cliente);
			$('#FormEditarCliente input[name=direccion]').val(json.direc_cliente);
			$('#FormEditarCliente input[name=contacto]').val(json.contac_cliente);
			$('#FormEditarCliente input[name=email]').val(json.email_cliente);
			$('#FormEditarCliente select[name=estado]').val(json.estado_cliente);

		});
	});

	$('#FormEditarCliente').validate({
		ignore: [],
		rules: {
			tipo: { required: true },
			nombre: { required: true },
			documento: { required: true },
			// telefono: { required: true },
			direccion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarCliente', function (json) {
				if (json.success) {
					$('#TableListarClientes').DataTable().ajax.reload();
				}
				$('#ModalEditarCliente').modal('hide');
				$('#FormEditarCliente select[name=tipo]').select('val', '');
				$('#FormEditarCliente input[name=nombre]').val('');
				$('#FormEditarCliente input[name=documento]').val('val');
				$('#FormEditarCliente input[name=fnacimiento]').val('val');
				$('#FormEditarCliente input[name=direccion]').val('val');
				$('#FormEditarCliente input[name=contacto]').val('val');
				$('#FormEditarCliente input[name=email]').val('val');
				$('#FormEditarCliente select[name=estado]').select('val', '');
			})
		}
	});


	/*==========================================
			 CLIENTE - ANULAR
	===========================================*/

	$('#TableListarClientes').on('click', '.anular-cliente', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Cliente?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regcliente/anularCliente', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableListarClientes').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});


	/*=================================================================
													MODULO REPORTES
	=================================================================*/

	/*===============================
	=            VENTAS POR AÑO            =
	===============================*/

	function ColoresClass() {
		this.colores = [
			'#ef5777',
			'#575fcf',
			'#4bcffa',
		];
	};

	ColoresClass.prototype.hexToRgb = function (val, type) {

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(val);
		if (type == 'oscuro') {
			return `rgba(${parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + ',1'})`;
		} else if (type == 'claro') {
			return `rgba(${parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + ',0.4'})`;
		}
	},
		ColoresClass.prototype.result = function (index, type) {
			this.color = this.hexToRgb(this.colores[index], type)
			return this.color;
		};
	var color = new ColoresClass();
	var colores = [
		'rgba(235, 193, 66, 1)',
		'rgba(3, 169, 244, 1)',
		'rgba(0, 150, 136, 1)',
	];

	if ($('#VentasAnio')[0]) {

		function GraficoVentas() {
			$("#VentasAnio").remove();
			$("#ContentVentasAnio").html("<canvas id='VentasAnio' style='height:300px'></canvas>");
			var selector = $("#VentasAnio");
			var ctx = selector.get(0).getContext("2d");
			var container = selector.parent();
			var ww = selector.attr('width', $(container).width());
			var data = {
				labels: [],
				datasets: [
					{
						fillColor: "rgba(3, 169, 244, 0.4)",
						strokeColor: "rgba(3, 169, 244, 1)",
						pointColor: "rgba(3, 169, 244, 1)",
						pointStrokeColor: "#fff",
						data: []
					}
				]
			};
			var options = {
				responsive: true,
				maintainAspectRatio: false
			};
			var formReporteVentasAnio = $('#FormFiltroReporteVentasAnio').serializeObject();
			$.getJSON(path + 'reportes/regventasanio/jsonVentas', formReporteVentasAnio, function (json, textStatus) {
				$.each(json, function (index, val) {
					data.labels.push(val.mes);
					data.datasets[0].data.push(val.monto)
				});

				new Chart(ctx).Line(data, options);
			});

		}

		GraficoVentas();

		$('#FormFiltroReporteVentasAnio select[name=anio]').change(function (event) {
			GraficoVentas();
		});

		$('#FormFiltroReporteVentasAnio input[name=Contado],#FormFiltroReporteVentasAnio input[name=Credito]').change(function (event) {
			GraficoVentas();
		});

	}
	/*=====  End of VENTAS POR AÑO  ======*/

	/*===============================
	= GRAFICOS COMPRAS POR AÑO      =
	===============================*/

	function ColoresClass() {
		this.colores = [
			'#ef5777',
			'#575fcf',
			'#4bcffa',
		];
	};

	ColoresClass.prototype.hexToRgb = function (val, type) {

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(val);
		if (type == 'oscuro') {
			return `rgba(${parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + ',1'})`;
		} else if (type == 'claro') {
			return `rgba(${parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + ',0.4'})`;
		}
	},
		ColoresClass.prototype.result = function (index, type) {
			this.color = this.hexToRgb(this.colores[index], type)
			return this.color;
		};
	var color = new ColoresClass();
	var colores = [
		'rgba(235, 193, 66, 1)',
		'rgba(3, 169, 244, 1)',
		'rgba(0, 150, 136, 1)',
	];

	if ($('#VentasAnio')[0]) {

		function GraficoVentas() {
			$("#VentasAnio").remove();
			$("#ContentVentasAnio").html("<canvas id='VentasAnio' style='height:300px'></canvas>");
			var selector = $("#VentasAnio");
			var ctx = selector.get(0).getContext("2d");
			var container = selector.parent();
			var ww = selector.attr('width', $(container).width());
			var data = {
				labels: [],
				datasets: [
					{
						fillColor: "rgba(3, 169, 244, 0.4)",
						strokeColor: "rgba(3, 169, 244, 1)",
						pointColor: "rgba(3, 169, 244, 1)",
						pointStrokeColor: "#fff",
						data: []
					}
				]
			};
			var options = {
				responsive: true,
				maintainAspectRatio: false
			};
			var formReporteVentasAnio = $('#FormFiltroReporteVentasAnio').serializeObject();
			$.getJSON(path + 'reportes/regventasanio/jsonCompras', formReporteVentasAnio, function (json, textStatus) {
				$.each(json, function (index, val) {
					data.labels.push(val.mes);
					data.datasets[0].data.push(val.monto)
				});

				new Chart(ctx).Line(data, options);
			});

		}

		GraficoVentas();

		$('#FormFiltroReporteVentasAnio select[name=anio]').change(function (event) {
			GraficoVentas();
		});

		$('#FormFiltroReporteVentasAnio input[name=Contado],#FormFiltroReporteVentasAnio input[name=Credito]').change(function (event) {
			GraficoVentas();
		});

	}
	/*=====  End of GRAFICO COMPRAS POR AÑO  ======*/

	/*===============================
	=            VENTAS POR MES            =
	===============================*/

	function ColoresClass() {
		this.colores = [
			'#ef5777',
			'#575fcf',
			'#4bcffa',
		];
	};

	ColoresClass.prototype.hexToRgb = function (val, type) {

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(val);
		if (type == 'oscuro') {
			return `rgba(${parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + ',1'})`;
		} else if (type == 'claro') {
			return `rgba(${parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + ',0.4'})`;
		}
	},
		ColoresClass.prototype.result = function (index, type) {
			this.color = this.hexToRgb(this.colores[index], type)
			return this.color;
		};
	var color = new ColoresClass();
	var colores = [
		'rgba(235, 193, 66, 1)',
		'rgba(3, 169, 244, 1)',
		'rgba(0, 150, 136, 1)',
	];

	if ($('#VentasMes')[0]) {

		function GraficoVentas() {
			$("#VentasMes").remove();
			$("#ContentVentasMes").html("<canvas id='VentasMes' style='height:300px'></canvas>");
			var selector = $("#VentasMes");
			var ctx = selector.get(0).getContext("2d");
			var container = selector.parent();
			var ww = selector.attr('width', $(container).width());
			var data = {
				labels: [],
				datasets: [
					{
						fillColor: "rgba(3, 169, 244, 0.4)",
						strokeColor: "rgba(3, 169, 244, 1)",
						pointColor: "rgba(3, 169, 244, 1)",
						pointStrokeColor: "#fff",
						data: []
					}
				]
			};
			var options = {
				responsive: true,
				maintainAspectRatio: false
			};
			var formReporteVentasMes = $('#FormFiltroReporteVentasMes').serializeObject();
			$.getJSON(path + 'reportes/regdashboard/jsonVentasMes', formReporteVentasMes, function (json, textStatus) {
				$.each(json, function (index, val) {
					data.labels.push(val.mes);
					data.datasets[0].data.push(val.monto)
				});

				new Chart(ctx).Line(data, options);
			});

		}

		GraficoVentas();

		$('#FormFiltroReporteVentasMes select[name=mes]').change(function (event) {
			GraficoVentas();
		});


		$('#FormFiltroReporteVentasMes input[name=Contado],#FormFiltroReporteVentasMes input[name=Credito]').change(function (event) {
			GraficoVentas();
		});

	}
	/*=====  End of VENTAS POR AÑO  ======*/

	/*================================================
	=            REPORTES COMPRAS TOTALES            =
	=================================================*/
	var Tablereportcompras = $('#Tablereportcompras').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportpagos/jsonReportcompras',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.proveedor = $("input[name=proveedor]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
		],
		"initComplete": function (settings, json) {
			totalReportePagos(json.total);
		}
	});

	function totalReportePagos(total) {
		$('#TotalPagos').html(round(total, 2));
	}

	$('#ReportcomprasFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportcompras').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});

	})
	$('#ReportcomprasFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportcompras').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	})


	$('#ReportcomprasFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportcompras').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportcomprasFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportcompras').DataTable().ajax.reload(function (json) {
				totalReportePagos(json.total);
			});
		}
	});



	/*================================================
	=            REPORTES COMPRAS X PROVEEDOR        =
	=================================================*/
	var Tablereportproveedores = $('#Tablereportproveedores').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportcomprove/jsonReportcomproveedor',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_proveedor = $("input[name=tb_proveedor]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },


			// {"orderable":false},

		],
		"initComplete": function (settings, json) {
			totalReportePagosProveedor(json.total);
		}
	});

	function totalReportePagosProveedor(total) {
		$('#TotalPagosProveedor').html(round(total, 2));
	}

	$('#ReportcomproveedorFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportproveedores').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	})
	$('#ReportcomproveedorFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportproveedores').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	})


	$('#ReportcomproveedorFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportproveedores').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	});

	$('#ReportcomproveedorFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportproveedores').DataTable().ajax.reload(function (json) {
				totalReportePagosProveedor(json.total);
			});
		}
	});




	/*================================================
	=            REPORTES COMPRAS PRODUCTOS          =
	=================================================*/
	var Tablereportproductos = $('#Tablereportproductos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportcomproduct/jsonReportproductos',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_producto = $("input[name=tb_producto]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [

			{ "orderable": false },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			// {"orderable":false},

		],
		"initComplete": function (settings, json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		}
	});

	function totalReportePagosProductos(total) {
		$('#TotalPagosProductos').html(round(total, 2));
	}

	function totalReportesPagosTotales(totales) {
		$('#TotalPagosTotales').html(round(totales, 2));
	}


	$('#ReportproductosFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		});

	})
	$('#ReportproductosFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		});

	})


	$('#ReportproductosFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		});

	});

	$('#ReportproductosFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportproductos').DataTable().ajax.reload(function (json) {
				totalReportePagosProductos(json.total);
				totalReportesPagosTotales(json.totales);
			});

		}
	});


	/*================================================
	=            REPORTES COTIZACIONES TOTALES            =
	=================================================*/
	var Tablereportcotizaciones = $('#Tablereportcotizaciones').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportcotipagos/jsonReportcotizacion',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_cliente = $("input[name=tb_cliente]").val();
				d.pago_cot = $("select[name=pago_cot]").val();
				d.moneda_cat = $("select[name=moneda_cat]").val();
				// d.tb_talonario = $("select[name=tb_talonario]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			// {"orderable":false},

		],
		"initComplete": function (settings, json) {
			totalReporteCotizaciones(json.total);

		}
	});

	function totalReporteCotizaciones(total) {
		$('#TotalCotizacion').html(round(total, 2));
	}

	$('#ReportcotizacionFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportcotizaciones').DataTable().ajax.reload(function (json) {
			totalReporteCotizaciones(json.total);
		});
	})
	$('#ReportcotizacionFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportcotizaciones').DataTable().ajax.reload(function (json) {
			totalReporteCotizaciones(json.total);
		});
	})
	$('#ReportcotizacionFormBusqueda select[name=pago_cot]').change(function (event) {
		$('#Tablereportcotizaciones').DataTable().ajax.reload(function (json) {
			totalReporteCotizaciones(json.total);
		});
	});

	$('#ReportcotizacionFormBusqueda select[name=moneda_cat]').change(function (event) {
		$('#Tablereportcotizaciones').DataTable().ajax.reload(function (json) {
			totalReporteCotizaciones(json.total);
		});
	});

	// $('#ReportcotizacionFormBusqueda select[name=tb_talonario]').change(function(event) {
	// 	$('#Tablereportcotizaciones').DataTable().ajax.reload(function(json){
	// 		totalReporteCotizaciones(json.total);
	// 	});
	// });

	$('#ReportcotizacionFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportcotizaciones').DataTable().ajax.reload(function (json) {
			totalReporteCotizaciones(json.total);
		});
	});

	$('#ReportcotizacionFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportcotizaciones').DataTable().ajax.reload(function (json) {
				totalReporteCotizaciones(json.total);
			});
		}
	});



	/*================================================
	=            REPORTES COTIZACION X CLIENTE       =
	=================================================*/
	var Tablereportcoticlientes = $('#Tablereportcoticlientes').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportcoticlientes/jsonReportcoticlientes',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_cliente = $("input[name=tb_cliente]").val();
				// d.tb_proveedor = $("input[name=tb_proveedor]").val();
				d.pago_cot = $("select[name=pago_cot").val();

			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },


			// {"orderable":false},

		],
		"initComplete": function (settings, json) {
			totalReportePagosProveedor(json.total);
		}
	});

	function totalReportePagosProveedor(total) {
		$('#TotalPagosProveedor').html(round(total, 2));
	}

	$('#ReportcoticlientesFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportcoticlientes').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	})
	$('#ReportcoticlientesFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportcoticlientes').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	})


	$('#ReportcoticlientesFormBusqueda select[name=pago_cot]').change(function (event) {
		$('#Tablereportcoticlientes').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	});

	$('#ReportcoticlientesFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportcoticlientes').DataTable().ajax.reload(function (json) {
				totalReportePagosProveedor(json.total);
			});
		}
	});


	/*================================================
	=            REPORTES COTIZACION POR PRODUCTOS   =
	=================================================*/
	var Tablereportcotiproductos = $('#Tablereportcotiproductos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportcotiproductos/jsonReportcotiproductos',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_producto = $("input[name=tb_producto]").val();
				d.tb_marca = $("select[name=tb_marca]").val();
				d.tb_categoria = $("select[name=tb_categoria]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			// {"orderable":false},

		],
		"initComplete": function (settings, json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		}
	});

	function totalReportePagosProductos(total) {
		$('#TotalPagosProductos').html(round(total, 2));
	}

	function totalReportesPagosTotales(totales) {
		$('#TotalPagosTotales').html(round(totales, 2));
	}


	$('#ReportcotiproductosFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportcotiproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		});

	})
	$('#ReportcotiproductosFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportcotiproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		});

	})


	$('#ReportcotiproductosFormBusqueda select[name=tb_marca]').change(function (event) {
		$('#Tablereportcotiproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		});

	});

	$('#ReportcotiproductosFormBusqueda select[name=tb_categoria]').change(function (event) {
		$('#Tablereportcotiproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
		});

	});


	$('#ReportcotiproductosFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportcotiproductos').DataTable().ajax.reload(function (json) {
				totalReportePagosProductos(json.total);
				totalReportesPagosTotales(json.totales);
			});

		}
	});



	/*================================================
	=            REPORTES VENTAS TOTALES            =
	=================================================*/
	var Tablereportventas = $('#Tablereportventas').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportventotal/jsonReportventotal',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.correlativo = $("input[name=correlativo]").val();
				d.cliente = $("input[name=cliente]").val();
				d.punto = $("select[name=punto]").val();
				d.vendedor = $("select[name=vendedor").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
		],
		"initComplete": function (settings, json) {
			totalReportePagos(json.total);
		}
	});

	function totalReportePagos(total) {
		$('#TotalPagos').html(round(total, 2));
	}

	$('#ReportventasFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportventas').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});

	})
	$('#ReportventasFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportventas').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	})

	$('#ReportventasFormBusqueda select[name=punto]').change(function (event) {
		$('#Tablereportventas').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventasFormBusqueda select[name=vendedor]').change(function (event) {
		$('#Tablereportventas').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventasFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportventas').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventasFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportventas').DataTable().ajax.reload(function (json) {
				totalReportePagos(json.total);
			});
		}
	});


	/*================================================
	=            REPORTES VENTAS PAGOS            =
	=================================================*/
	var Tablereportventaspagos = $('#Tablereportventaspagos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportventpago/jsonReportpagosventas',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.pago = $("select[name=pago]").val();
				d.punto = $("select[name=punto]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
		],
		"initComplete": function (settings, json) {
			totalReportePagos(json.total);
		}
	});

	function totalReportePagos(total) {
		$('#TotalPagos').html(round(total, 2));
	}

	$('#ReportventaspagosFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportventaspagos').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});

	})
	$('#ReportventaspagosFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportventaspagos').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	})

	$('#ReportventaspagosFormBusqueda select[name=pago]').change(function (event) {
		$('#Tablereportventaspagos').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventaspagosFormBusqueda select[name=punto]').change(function (event) {
		$('#Tablereportventaspagos').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventaspagosFormBusqueda select[name=vendedor]').change(function (event) {
		$('#Tablereportventaspagos').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventaspagosFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportventaspagos').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventaspagosFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportventaspagos').DataTable().ajax.reload(function (json) {
				totalReportePagos(json.total);
			});
		}
	});



	/*================================================
	=            REPORTES VENTAS DETALLES TOTALES    =
	=================================================*/
	var Tablereportventadetalles = $('#Tablereportventadetalles').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportventdetalle/jsonReportventasdetalle',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.cliente = $("input[name=cliente]").val();
				d.vendedor = $("select[name=vendedor]").val();
				d.punto = $("select[name=punto]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
		],
		"initComplete": function (settings, json) {
			totalReportePagos(json.total);
		}
	});

	function totalReportePagos(total) {
		$('#TotalPagosDetalle').html(round(total, 2));
	}

	$('#ReportventasdetallesFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportventadetalles').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});

	})
	$('#ReportventasdetallesFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportventadetalles').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	})

	$('#ReportventasdetallesFormBusqueda select[name=vendedor]').change(function (event) {
		$('#Tablereportventadetalles').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventasdetallesFormBusqueda select[name=punto]').change(function (event) {
		$('#Tablereportventadetalles').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});


	$('#ReportventasdetallesFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportventadetalles').DataTable().ajax.reload(function (json) {
			totalReportePagos(json.total);
		});
	});

	$('#ReportventasdetallesFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportventadetalles').DataTable().ajax.reload(function (json) {
				totalReportePagos(json.total);
			});
		}
	});


	/*================================================
	=            REPORTES VENTAS X CLIENTE        =
	=================================================*/
	var Tablereportventaclientes = $('#Tablereportventaclientes').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportventcliente/jsonReportventclientes',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_cliente = $("input[name=tb_cliente]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },


			// {"orderable":false},

		],
		"initComplete": function (settings, json) {
			totalReportePagosProveedor(json.total);
		}
	});

	function totalReportePagosProveedor(total) {
		$('#TotalPagosProveedor').html(round(total, 2));
	}

	$('#ReportventclienteFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportventaclientes').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	})
	$('#ReportventclienteFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportventaclientes').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	})


	$('#ReportventclienteFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportventaclientes').DataTable().ajax.reload(function (json) {
			totalReportePagosProveedor(json.total);
		});
	});

	$('#ReportventclienteFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportventaclientes').DataTable().ajax.reload(function (json) {
				totalReportePagosProveedor(json.total);
			});
		}
	});



	/*================================================
	=            REPORTES VENTAS PRODUCTOS          =
	=================================================*/
	var Tablereportventproductos = $('#Tablereportventproductos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/regreportventproducto/jsonReportventproductos',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.producto = $("input[name=producto]").val();
				d.estado = $("select[name=estado]").val();
				d.punto = $("select[name=punto]").val();
				d.tb_marca = $("select[name=tb_marca]").val();
				d.tb_categoria = $("select[name=tb_categoria]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			// {"orderable":false},

		],
		"initComplete": function (settings, json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
		}
	});

	function totalReportePagosProductos(total) {
		$('#TotalPagosProductos').html(round(total, 2));
	}

	function totalReportesPagosTotales(totales) {
		$('#TotalPagosTotales').html(round(totales, 2));
	}
	function totalReportesPagosPrecios(totales) {
		$('#TotalPagosPrecios').html(round(totales, 2));
	}


	$('#ReportventproductosFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportventproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
		});

	})
	$('#ReportventproductosFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportventproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
		});

	})

	$('#ReportventproductosFormBusqueda select[name=punto]').change(function (event) {
		$('#Tablereportventproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
		});

	});

	$('#ReportventproductosFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportventproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
		});

	});

	$('#ReportventproductosFormBusqueda select[name=tb_marca]').change(function (event) {
		$('#Tablereportventproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
		});

	});

	$('#ReportventproductosFormBusqueda select[name=tb_categoria]').change(function (event) {
		$('#Tablereportventproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
		});

	});


	$('#ReportventproductosFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportventproductos').DataTable().ajax.reload(function (json) {
				totalReportePagosProductos(json.total);
				totalReportesPagosTotales(json.totales);
				totalReportesPagosPrecios(json.totalesprecios);
			});

		}
	});

// EXPORTAR REPORTE VENTAS POR PRODUCTO A EXCEL

$('#Reporteventproductos').click(function (event) {
		let form = $('#ReportventproductosFormBusqueda').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'reportes/regreportventproducto/reportVentaproductexcel?' + params);
	});

	/*===========================================
	=            PERMISOS - LISTADO            =
	===========================================*/
	var TableMantenimientoPermisos = $('#TableMantenimientoPermisos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/permisos/jsonPermisos',
			"type": "GET",
			"data": function (d) {
				d.menus = $("select[name=menus]").val();
				d.rol = $("select[name=tb_perfil]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
		]
	});

	$('#PermisosFormBusqueda select[name=menus]').change(function (event) {
		$('#TableMantenimientoPermisos').DataTable().ajax.reload();
	});

	$('#PermisosFormBusqueda select[name=tb_perfil]').change(function (event) {
		$('#TableMantenimientoPermisos').DataTable().ajax.reload();
	});


	$('#PermisosFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoPermisos').DataTable().ajax.reload();
		}
	});


	$('#TableMantenimientoPermisos').on('click', '.anular-permiso', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Permisos?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/permisos/quitarpermiso', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoPermisos').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});



	var TableKardex = $('#TableKardex').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'dashboard/jsonKardex',
			"type": "GET",
			"data": function (d) {
				d.menus = $("select[name=menus]").val();
				d.rol = $("select[name=tb_perfil]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
		]
	});



  //  graficoVentasTopProd();
    // ventas top productos
    $('#formVentasTopProductos select[name=mesVentasTopProd]').change(function (event) {
            mesSelected = $(this).val();
            datagraficoVentasTopProd(path,mesSelected);
            console.log(mesSelected);
        });




// });
/*============================================================
	=            REPORTES GANANCIA VENTAS PRODUCTOS          =
==============================================================*/
	var Tablereportgananciaproductos = $('#Tablereportgananciaproductos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[1, 'desc']],
		"ajax": {
			"url": path + 'reportes/reganancvent/jsonReportgananciaproductos',
			"type": "POST",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.producto = $("input[name=producto]").val();
				d.estado = $("select[name=estado]").val();
				d.punto = $("select[name=punto]").val();
				d.tb_marca = $("select[name=tb_marca]").val();
				d.tb_categoria = $("select[name=tb_categoria]").val();
			}
		},
		"columns": [

			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			{ "orderable": false },
			// {"orderable":false},

		],
		"initComplete": function (settings, json) {
			totalReportePagosProductos(json.total);
			totalReporteCompProductos(json.precompras)
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
			totalReportesPagosCompras(json.ventcompras);
			totalReportesPagosGanancia(json.ganancia);
		}
	});

	function totalReportePagosProductos(total) {
		$('#TotalPagosProductos').html(round(total, 2));
	}
	function totalReporteCompProductos(totalescompras) {
		$('#TotalPagosPrecompras').html(round(totalescompras, 2));
	}
	function totalReportesPagosPrecios(totales) {
		$('#TotalPagosPrecios').html(round(totales, 2));
	}
	function totalReportesPagosCompras(totales) {
		$('#TotalPagosCompras').html(round(totales, 2));
	}

	function totalReportesPagosTotales(totales) {
		$('#TotalPagosTotales').html(round(totales, 2));
	}
	function totalReportesPagosGanancia(totales) {
		$('#TotalPagosGanancias').html(round(totales, 2));
	}



	$('#ReportgananproductosFormBusqueda input[name=desde]').change(function (event) {
		$('#Tablereportgananciaproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReporteCompProductos(json.precompras);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
			totalReportesPagosCompras(json.ventcompras);
			totalReportesPagosGanancia(json.ganancia);
		});

	})
	$('#ReportgananproductosFormBusqueda input[name=hasta]').change(function (event) {
		$('#Tablereportgananciaproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReporteCompProductos(json.precompras);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
			totalReportesPagosCompras(json.ventcompras);
			totalReportesPagosGanancia(json.ganancia);
		});

	})

	$('#ReportgananproductosFormBusqueda input[name=producto]').change(function (event) {
		$('#Tablereportgananciaproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReporteCompProductos(json.precompras);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
			totalReportesPagosCompras(json.ventcompras);
			totalReportesPagosGanancia(json.ganancia);
		});

	});

	$('#ReportgananproductosFormBusqueda select[name=punto]').change(function (event) {
		$('#Tablereportgananciaproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReporteCompProductos(json.precompras);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
			totalReportesPagosCompras(json.ventcompras);
			totalReportesPagosGanancia(json.ganancia);
		});

	});

	$('#ReportgananproductosFormBusqueda select[name=estado]').change(function (event) {
		$('#Tablereportgananciaproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReporteCompProductos(json.precompras);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
			totalReportesPagosCompras(json.ventcompras);
			totalReportesPagosGanancia(json.ganancia);
		});

	});

	$('#ReportgananproductosFormBusqueda select[name=tb_marca]').change(function (event) {
		$('#Tablereportgananciaproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReporteCompProductos(json.precompras);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
			totalReportesPagosCompras(json.ventcompras);
			totalReportesPagosGanancia(json.ganancia);
		});

	});

	$('#ReportgananproductosFormBusqueda select[name=tb_categoria]').change(function (event) {
		$('#Tablereportgananciaproductos').DataTable().ajax.reload(function (json) {
			totalReportePagosProductos(json.total);
			totalReporteCompProductos(json.precompras);
			totalReportesPagosTotales(json.totales);
			totalReportesPagosPrecios(json.totalesprecios);
			totalReportesPagosCompras(json.ventcompras);
			totalReportesPagosGanancia(json.ganancia);
		});

	});


	$('#ReportgananproductosFormBusqueda').validate({
		rules: {
			desde: { required: true },
			hasta: { required: true }
		},
		submitHandler: function () {
			$('#Tablereportgananciaproductos').DataTable().ajax.reload(function (json) {
				totalReportePagosProductos(json.total);
				totalReporteCompProductos(json.precompras);
				totalReportesPagosTotales(json.totales);
				totalReportesPagosPrecios(json.totalesprecios);
				totalReportesPagosCompras(json.ventcompras);
				totalReportesPagosGanancia(json.ganancia);
			});

		}
	});
	// exportar utilidad buta a excel
	$('#Reportutilidadexcel').click(function (event) {
		let form = $('#ReportgananproductosFormBusqueda').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'reportes/reganancvent/reporteDetalleExcel?' + params);
	});

	/* ======================== */
/*     REPORTE DETALLADO    */
/* ======================== */
var TableReporteDetalladoCompras = $('#TableReporteDetalladoCompras').DataTable({
	"language": {
		"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
	},
	"searching": false,
	"processing": true,
	"serverSide": true,
	"iDisplayLength": 10,
	"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],	
	"aaSorting": [[0, 'desc']],
	"ajax": {
		"url": path + 'reportes/regreportedetallado/jsonCompras',
		"type": "GET",
		"data": function (d) {
			d.desde = $("input[name=desde]").val();
			d.hasta = $("input[name=hasta]").val();
			d.proveedor = $("input[name=proveedor]").val();
			d.almacen = $("input[name=almacen]").val();
		}
	},
	"columns": [
		{ "orderable": false, "className": 'details-control' },
		{ "orderable": true },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false }
	],
	"initComplete": function (settings, json) {
		totalReportesComprasDetalladas(json.total);
	}
});

$('#TableReporteDetalladoCompras tbody').on('click', 'td.details-control', function () {
	var tr = $(this).closest('tr');
	var row = TableReporteDetalladoCompras.row(tr);

	if (row.child.isShown()) {
		row.child.hide();
		tr.removeClass('shown');
		$(this).find('span').removeClass('fa-caret-down').addClass('fa-caret-right');
	} else {
		row.child(formatReporteDetalladoComprasHistoria(row.data())).show();
		tr.addClass('shown');
		$(this).find('span').removeClass('fa-caret-right').addClass('fa-caret-down');
	}
});

function formatReporteDetalladoComprasHistoria(d) {
	var usuarios = jQuery.parseJSON(d[13]);
	var table = `

	<table class="table table-bordered" style="width:300px">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Cantidad</th>
			</tr>
		</thead>
		<tbody>
	`;
	var tr = '';
	$.each(usuarios, function (index, val) {
		tr += `
		<tr>
			<td>${val['fecha_comp']}</td>
			<td>${val['cant_compdet']}</td>
		</tr>
		`;
	});
	table += tr;
	table += `
		</tbody>
	</table>`;
	return table;
}

$('#FormReporteComprasDetalladasBusqueda').validate({
	submitHandler: function () {
		$('#TableReporteDetalladoCompras').DataTable().ajax.reload(function (json) {
			totalReportesComprasDetalladas(json.total);
		});
	}
});


function totalReportesComprasDetalladas(total) {
	$('#TotalReporteComprasDetalladas').html(round(total, 2));
}

$('#ReporteComprasDetalladasExcel').click(function (event) {
	let form = $('#FormReporteComprasDetalladasBusqueda').serializeObject();
	let params = $.param(form);
	$(this).attr('href', path + 'reportes/regreportedetallado/comprasDetalladasExcel?' + params);
});





var TableReporteDetalladoVentas = $('#TableReporteDetalladoVentas').DataTable({
	"language": {
		"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
	},
	"searching": false,
	"processing": true,
	"serverSide": true,
	"iDisplayLength": 10,
	"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
	"aaSorting": [[0, 'desc']],
	"ajax": {
		"url": path + 'reportes/regreportedetallado/jsonVentas',
		"type": "GET",
		"data": function (d) {
			d.desde = $("input[name=desde]").val();
			d.hasta = $("input[name=hasta]").val();
			d.almacen = $("input[name=almacen]").val();
			d.cliente = $("input[name=cliente]").val();
			d.vendedor = $("input[name=vendedor]").val();
		}
	},
	"columns": [
		{ "orderable": true },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false }
	],
	"initComplete": function (settings, json) {
		totalReportesVentasDetalladas(json);
	}
});

TableReporteDetalladoVentas.column(7).visible(movilexpert=='0'?false:true);

$('#FormReporteVentasDetalladasBusqueda').validate({
	submitHandler: function () {
		$('#TableReporteDetalladoVentas').DataTable().ajax.reload(function (json) {
			totalReportesVentasDetalladas(json);
		});
	}
});

function totalReportesVentasDetalladas(json) {
	$('#TotalReporteVentasDetalladas').html(round(json.total, 2));
}

$('#ReporteVentasDetalladasExcel').click(function (event) {
	let form = $('#FormReporteVentasDetalladasBusqueda').serializeObject();
	let params = $.param(form);
	$(this).attr('href', path + 'reportes/regreportedetallado/ventasDetalladasExcel?' + params);
});
/* ======================== */
/*     END REPORTE DETALLADO    */
/* ======================== */

/* ======================== */
/*     QUE HAY DE NUEVO     */
/* ======================== */
var TableNuevo = $('#TableNuevo').DataTable({
	"language": {
		"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
	},
	"searching": false,
	"columns": [
		{ "orderable": true },
		{ "orderable": false },
		{ "orderable": false },
	],
	"columnDefs": [
		{ "width": "10%", "targets": 0 },
		{ "width": "15%", "targets": 2 }
	]
});

$('#agregarNuevoModal').click(function (e) { 
	e.preventDefault();
	$('#ModalAgregarNuevo').modal();
});

if($("#FormAgregarNuevo #contenidoTiny").length > 0){
	var tynyConteido = tinymce.init({
			selector: "textarea#contenidoTiny",
			theme: "modern",
			height:300,
			plugins: [
					"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
					"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					"save table contextmenu directionality emoticons template paste textcolor"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
			style_formats: [
					{title: 'Bold text', inline: 'b'},
					{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
					{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
					{title: 'Example 1', inline: 'span', classes: 'example1'},
					{title: 'Example 2', inline: 'span', classes: 'example2'},
					{title: 'Table styles'},
					{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			]
	});
}

if($("#FormEditarNuevo #contenidoTinyEdit").length > 0){
	var tynyConteido = tinymce.init({
			selector: "textarea#contenidoTinyEdit",
			theme: "modern",
			height:300,
			plugins: [
					"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
					"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					"save table contextmenu directionality emoticons template paste textcolor"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
			style_formats: [
					{title: 'Bold text', inline: 'b'},
					{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
					{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
					{title: 'Example 1', inline: 'span', classes: 'example1'},
					{title: 'Example 2', inline: 'span', classes: 'example2'},
					{title: 'Table styles'},
					{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			]
	});
}

$('#FormAgregarNuevo').validate({
	ignore: [],
	rules: {
		titulo: { required: true }
	},
	submitHandler: function () {
		var contenido = tinyMCE.get('contenidoTiny').getContent();
		$('#FormAgregarNuevo textarea[name=contenido]').val(contenido);
		setTimeout(() => {
			enviarFormulario('#FormAgregarNuevo', function (json) {
			})
		}, 700);
	}
});


$('#TableNuevo').on('click','.editarNuevo', function () {
	var id = $(this).data('id');
	$.getJSON(path+"administrador/regnuevo/getNuevo", {id},
		function (data, textStatus, jqXHR) {
			$('#ModalEditarNuevo').modal();
			$('#FormEditarNuevo input[name=id]').val(data.id);
			$('#FormEditarNuevo input[name=titulo]').val(data.titulo);
			tinymce.get('contenidoTinyEdit').setContent(data.contenido);
		}
	);
});

$('#FormEditarNuevo').validate({
	ignore: [],
	rules: {
		titulo: { required: true }
	},
	submitHandler: function () {
		var contenido = tinyMCE.get('contenidoTinyEdit').getContent();
		$('#FormEditarNuevo textarea[name=contenido]').val(contenido);
		setTimeout(() => {
			enviarFormulario('#FormEditarNuevo', function (json) {
			})
		}, 700);
	}
});

$('#TableNuevo').on('click','.eliminarNuevo', function () {
	var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Eliminar registro?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regnuevo/eliminar', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						setTimeout(() => {
							window.location.href = path + 'administrador/regnuevo';
						}, 2000);
						
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
});

/* ======================== */
/*     QUE HAY DE NUEVO FIN     */
/* ======================== */
/* ======================== */
/*       COMPROBANTES       */
/* ======================== */
if($('#TableComprobantes')){
	
	function getCaptcha()
	{
		$.get(path+"administrador/regcomprobante/captcha",{},
			function (data, textStatus, jqXHR) {
				$('#captcha').html(data.imagen)
			},
			"JSON"
		);
	}
	getCaptcha();
	
	$('#FormComprobantesFiltro input[name=captcha]').keyup(function () { 
		//e.value = e.value.toUpperCase();
		var mayus = $(this).val().toUpperCase();
		$(this).val(mayus);
	});

	$('#actualizarCaptcha').click(function (e) { 
		e.preventDefault();
		getCaptcha();
	});

	$('#FormComprobantesFiltro').validate({
		ignore: [],
		rules: {
			ruc_dni: { required: true },
			factura_boleta: { required: true },
			captcha: { required: true },
		},
		submitHandler: function () {
			var procesando = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Procesando';
			$('#FormComprobantesFiltro button[type="submit"]').html(procesando);
			var buscar = '<i class="fa fa-search"></i> Buscar';
			var captcha = $('#FormComprobantesFiltro input[name=captcha]').val();
			$.get(path+"administrador/regcomprobante/verificaCaptcha", {captcha},
				function (data, textStatus, jqXHR) {
					if(data.success==false){
						Swal.fire({
							title: "Error",
							text: "El captcha es incorrecto.",
							type: "error"
						});
						$('#FormComprobantesFiltro button[type="submit"]').html(buscar);
						return;
					}
					var factura_boleta = $('#FormComprobantesFiltro input[name=factura_boleta]').val();
					var ruc_dni =  $('#FormComprobantesFiltro input[name=ruc_dni]').val();
					
					$.post(path+"administrador/regcomprobante/resultados", {captcha,factura_boleta,ruc_dni},
						function (json, textStatus, jqXHR) {
							var tr = '';
							$.each(json, function (index, val) { 
								var sunat = '';
								if(val.estado_fac==null){
									sunat = 'Pendiente';
								}
								if(val.estado_fac=='1'){
									sunat = 'Aceptado';
								}
								if(val.estado_fac=='2'){
									sunat = 'Rechazado';
								}
								tr += `
									<tr>
										<td><button data-id="${val.cod_vent}" class="detalle btn btn-primary btn-sm"><i class="fa fa-list"></i></button></td>
										<td>${val.num}</td>
										<td>${val.nom_tipdocumento}</td>
										<td>${val.nom_tipdocucli}</td>
										<td>${val.doc_cliente}</td>
										<td>${val.num_comp}</td>
										<td>${val.codmoneda_vent}</td>
										<td>${val.total_vent}</td>
										<td>${val.fecha_vent}</td>
										<td>${sunat}</td>
									</tr>
								`;
							});
							$('#TableComprobantes tbody').html(tr);
							$('#FormComprobantesFiltro button[type="submit"]').html(buscar);
							getCaptcha();
							$('#FormComprobantesFiltro input[name=captcha]').val('');
							
						},
						"JSON"
					);
				},
				"JSON"
			);
		}
	});

	$('#TableComprobantes tbody').on('click','.detalle', function () {
		var id = $(this).data('id');
		$.post(path+"administrador/regcomprobante/getDetalle",{id},
			function (data, textStatus, jqXHR) {
				$('#tipo-documento-cliente').html(data.nom_tipdocucli);
				$('#tipo-documento').html(data.nom_tipdocumento);
				$('#numero-comprobante').html(data.serie+'-'+data.numero_vent);
				$('#nombre-razonsocial').html(data.nomb_cliente);
				$('#moneda').html(data.codmoneda_vent);
				$('#num-documento').html(data.doc_cliente);
				$('#fecha-emision').html(data.fecha_vent);
				$('#imprimir').attr('href',path+'administrador/regcomprobante/imprimir/'+data.archivoxml_vent);
				$('#descargar').attr('href',path+'administrador/regcomprobante/descargar/'+data.archivoxml_vent);

				var tr = '';
				var num = 1;
				var descuentos = 0;
				$.each(data.detalle, function (index, value) { 
					tr += `
						<tr>
							<td>${num}</td>
							<td>${(value.cod_producto != null)?value.cod_producto:value.cod_servicio}</td>
							<td>${value.producto_ventdet}</td>
							<td>${value.producto_isdn}</td>
							<td>${value.abreviatura_unid}</td>
							<td>${value.cant_ventdet}</td>
							<td class="text-right">${round(value.precunit_ventdet - value.igv_ventdet,2)}</td>
							<td class="text-right">${value.precunit_ventdet}</td>
							<td class="text-right">${value.igv_ventdet}</td>
							<td class="text-right">${value.descuento_ventdet}</td>
							<td class="text-right">${value.subtotal_ventdet}</td>
						</tr>
					`;
					descuentos += value.descuento_ventdet * value.cant_ventdet;
					num++;
				});
				
				$('#detalle-comprobante tbody').html(tr);

				$('#valor-venta').html(data.subtotal_vent);
				$('#igv').html(data.igv_vent);
				$('#descuento').html(round(descuentos,2));
				$('#importe-total').html(data.total_vent);
				$('#ModalDetalle').modal();
			},
			"JSON"
		);
	});

}
/* ======================== */
/*     COMPROBANTES END     */
/* ======================== */


/* ======================== */
/*          EMPRESA         */
/* ======================== */
$('#SubirCertificado').fileupload({
  url: path+'empresa/Regempresa/uploadCertificado',
	dataType: 'json',
	autoUpload: false,
  done: function (e, data) {
		$('#FormEmpresa input[name=certificadoNombre]').val(data.result.name);
  },
  progressall: function (e, data) {
    var progress = parseInt(data.loaded / data.total * 100, 10);
    $('#progress .progress-bar').show();
    $('#progress .progress-bar').css(
      'width',
      progress + '%'
    );
  }
}).prop('disabled', !$.support.fileInput)
	.parent().addClass($.support.fileInput ? undefined : 'disabled');


$('#FormEmpresa').validate({
	ignore: [],
	rules: {
		RUC: { required: true },
		razon_social:{required:true},
		ubigeo:{required:true}
	},
	submitHandler: function () {
		enviarFormulario('#FormEmpresa', function (json) {
			$('#logo-archivo').attr('src',path+'assets/images/logo/'+json.empresa.photo);
			$('#certificado-archivo').html(json.empresa.certificado_emp);
			$('input[name=certificado]').val('');
			$('input[name=logo]').val('');
		})
	}
});

   
/* ======================== */
/*        END EMPRESA       */
/* ======================== */


/* ============================================ */
/*                 MOVIL EXPERT                 */
/* ============================================ */
$('#movil-expert').change(function (e) { 
	//e.preventDefault();
	var check = $(this);
	if(check.is(':checked')){
		$('#movil-expert').trigger('click');
		$('#ModalMovilExpertConfirmar').modal();
	}else{
		$.post(path+"empresa/regempresa/movilExpert", {'estado': 0},
			function (data, textStatus, jqXHR) {
			},
			"HTML"
		);
		return;
	}
});


$('#FormConfirmarMovilExpert').validate({
	rules: {
		contrasena: { required: true }
	},
	submitHandler: function () {
		var contrasena = $('input[name=contrasena]').val();
		$.post(path+"administrador/regcajaapertura/verificaContrasena", {contrasena},
			function (data, textStatus, jqXHR) {
				if(data['success'] == true){
					$.post(path+"empresa/regempresa/movilExpert", {'estado': 1},
						function (data, textStatus, jqXHR) {
						},
						"HTML"
					);
					$('#movil-expert').trigger('click');
					Swal.fire({
						title: "Buen trabajo",
						text: "El módulo movil expert se activo correctamente.",
						type: "success"
					});
				}else{
					Swal.fire({
						title: "Error",
						text: "La contraseña es incorrecta.",
						type: "error"
					});
				}
				$('#ModalMovilExpertConfirmar').modal('hide');
			},
			"JSON"
		);
	}
});

/* ============================================ */
/*               END MOVIL EXPERT               */
/* ============================================ */



/* ======================== */
/*           KARDEX         */
/* ======================== */
$('.yearmonthpicker').datepicker({
	autoclose: true,
	language: "es",
	format: "yyyy-mm",
	viewMode: "months", 
	minViewMode: "months"
});

var tableToExcel = (function() {

	let uri = 'data:application/vnd.ms-excel;charset=utf-8;base64,'
		, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="utf-8"></meta><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><style>.thead-red {background-color: #c00808;color: #f1f1f1;} </style></head><body><h4>{title}</h4><table>{table}</table></body></html>'
		, base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
		, format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
	return function(table, name,title) {
		if (!table.nodeType) table = document.getElementById(table)
		var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML,title: title}
		window.location.href = uri + base64(format(template, ctx))
	}
})()


if($('#FormKardexFisico')[0]){
	$('#FormKardexFisico').validate({
		ignore: [],
		rules: {
		},
		submitHandler: function () {
			getKardexFisico();
		}
	});

	function getKardexFisico(){
		let almacen = $('#FormKardexFisico select[name=almacen]').val();
		let fecha = $('#FormKardexFisico input[name=fecha]').val();
		$('#TableKardexFisico tbody').html('');
		$.ajax({
			type: "GET",
			url: path+"reportes/kardex/jsonKardex",
			data: {almacen,fecha},
			dataType: "JSON",
			success: function (response) {
				let tr = '';
				$.each(response, function (index, value) { 
					tr += `
					<tr>
						<td>${value.nomb_product}</td>
						<td>${value.nomb_tiparticulo}</td>
						<td>${value.nomb_unid}</td>
						<td>${value.stock_inicial}</td>
						<td>${value.stock}</td>
						<td>${value.traspasos_recibidos}</td>
						<td>${value.venta_cantidad}</td>
						<td>${value.traspasos_enviados}</td>
						<td>${value.obsequio_cantidad}</td>
						<td>${value.bonificacion_cantidad}</td>
						<td>${value.stock_final}</td>
					</tr>
					`;
					$('#TableKardexFisico tbody').html(tr);
				});
			}
		});
	}
	getKardexFisico();
	$('#ExcelKardexFisico').click(function(){
		tableToExcel('TableKardexFisico','kardex fisico', 'KARDEX FÍSICO')
	})
}

if($('#FormKardexValorado')[0]){
	$('#FormKardexValorado').validate({
		ignore: [],
		rules: {
		},
		submitHandler: function () {
			getKardexValorado();
		}
	});

	function getKardexValorado(){
		let almacen = $('#FormKardexValorado select[name=almacen]').val();
		let fecha = $('#FormKardexValorado input[name=fecha]').val();
		$('#TableKardexValorado tbody').html('');
		$.ajax({
			type: "GET",
			url: path+"reportes/kardex/jsonKardex",
			data: {almacen,fecha},
			dataType: "JSON",
			success: function (response) {
				let tr = '';
				$.each(response, function (index, value) { 
					tr += `
					<tr>
						<td>${value.nomb_product}</td>
						<td>${value.nomb_tiparticulo}</td>
						<td>${value.nomb_unid}</td>
						<td>${value.valorado_stock_inicial}</td>
						<td>${value.compra_precio}</td>
						<td>${value.valorado_traspasos_recibidos}</td>
						<td>${value.venta_precio}</td>
						<td>${value.valorado_traspasos_enviados}</td>
						<td>${value.obsequio_precio}</td>
						<td>${value.bonificacion_precio}</td>
						<td>${value.valorado_stock_final}</td>
					</tr>
					`;
					$('#TableKardexValorado tbody').html(tr);
				});
			}
		});
	}
	getKardexValorado();
	$('#ExcelKardexValorado').click(function(){
		tableToExcel('TableKardexValorado','kardex valorado', 'KARDEX VALORADO')
	})
}
/* ======================== */
/*        END KARDEX        */
/* ======================== */
/* ======================== */
/*        FILE IMPUT        */
/* ======================== */
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

});
/*===========================================
	=            GASTOS - LISTADO            =
	===========================================*/
	var TableMantenimientoGastos = $('#TableMantenimientoGastos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regastos/jsonGastos',
			"type": "GET",
			"data": function (d) {
				d.desde = $("input[name=desde]").val();
				d.hasta = $("input[name=hasta]").val();
				d.tb_gastos = $("input[name=tb_gastos]").val();
				d.tb_tipo_gastos = $("select[name=tb_tipo_gastos]").val();
				d.estado = $("select[name=estado]").val();
			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		],
		"initComplete": function (settings, json) {
			totalReporteGastosTotales(json.total);
		}
	});

	function totalReporteGastosTotales(total) {
		$('#TotalPagosGastos').html(round(total, 2));
	}

	$('#GastosFormBusqueda input[name=desde]').change(function (event) {
		$('#TableMantenimientoGastos').DataTable().ajax.reload(function (json) {
			totalReporteGastosTotales(json.total);
		});

	})
	$('#GastosFormBusqueda input[name=hasta]').change(function (event) {
		$('#TableMantenimientoGastos').DataTable().ajax.reload(function (json) {
			totalReporteGastosTotales(json.total);
		});
	})

	$('#GastosFormBusqueda input[name=tb_gastos]').change(function (event) {
		$('#TableMantenimientoGastos').DataTable().ajax.reload(function (json) {
			totalReporteGastosTotales(json.total);
		});
	})

	$('#GastosFormBusqueda select[name=tb_tipo_gastos]').change(function (event) {
		$('#TableMantenimientoGastos').DataTable().ajax.reload(function (json) {
			totalReporteGastosTotales(json.total);
		});
	})

	$('#GastosFormBusqueda select[name=estado]').change(function (event) {
		$('#TableMantenimientoGastos').DataTable().ajax.reload(function (json) {
			totalReporteGastosTotales(json.total);
		});
	})

$('#GastosReportePdf').click(function (event) {
		let form = $('#GastosFormBusqueda').serializeObject();
		let params = $.param(form);
		$(this).attr('href', path + 'administrador/regastos/reportegastosPdf?' + params);
	});

	

	$('#GastosFormBusqueda').validate({
		ignore: [],
		rules: {
			desde: { required: true },
			hasta: { required: true },
			estado: { required: true },
			tb_tipo_gastos: { required: true },
		},
		submitHandler: function () {
			$('#TableMantenimientoGastos').DataTable().ajax.reload();
		}
	});



	/*==========================================
				GASTOS - AGREGAR
	===========================================*/


	$('#FormGastos').validate({
		ignore: [],
		rules: {
			tipogastos: { required: true },
			banco: { required: true },
			nombre: { required: true },
			total: { required: true },
			

		},
		submitHandler: function () {
			$('#ModalAgregarGastos').modal('hide');
			enviarFormulario('#FormGastos', function (json) {
				if (json.success) {
					$('#TableMantenimientoGastos').DataTable().ajax.reload();
					$('#FormGastos select[name=tipogastos]').val('');
					$('#FormGastos select[name=banco]').select('val', '');
					$('#FormGastos input[name=nombre]').val('');
					$('#FormGastos input[name=cuenta]').val('');
					$('#FormGastos input[name=persona]').val('');
					$('#FormGastos input[name=total]').val('');
					$('#FormGastos input[name=operacion]').val('');
					$('#FormGastos input[name=documentos]').val('');
					$('#FormGastos input[name=observacion]').val('');
					
					
				}
					
					


		


			})
		}
	});

	/*==========================================
				GASTOS - EDITAR
	===========================================*/

	$('#TableMantenimientoGastos').on('click', '.editar-gastos', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regastos/getGastos', { id }, function (json, textStatus) {
			$('#FormEditarGastos input[name=id]').val(json.cod_gastos);
			$('#FormEditarGastos select[name=tipogastos]').val(json.cod_tipgastos);
			$('#FormEditarGastos input[name=nombre]').val(json.nomb_gastos);
			$('#FormEditarGastos select[name=banco]').val(json.cod_ban);
			$('#FormEditarGastos input[name=cuenta]').val(json.cuenta_gastos);		
			$('#FormEditarGastos input[name=operacion]').val(json.oper_gastos);
			$('#FormEditarGastos input[name=persona]').val(json.persona_gastos);
			$('#FormEditarGastos input[name=documento]').val(json.documento_gastos);
			$('#FormEditarGastos input[name=total]').val(json.total_gastos);
			$('#FormEditarGastos input[name=observacion]').val(json.observacion_gastos);
			$('#FormEditarGastos select[name=estado]').val(json.est_gastos);
		});
	});

	// $('#cambiarPassword').click(function(event) {

	// 	if ($(this).is(":checked")) {
	// 		$('#FormEditarUsuario input[name=passwoord]').prop('disabled', false);
	// 	}else{
	// 		$('#FormEditarUsuario input[name=passwoord]').prop('disabled', true);
	// 	}
	// });

	$('#FormEditarGastos').validate({
		ignore: [],
		rules: {
			tipogastos: { required: true },
			nombre: { required: true },
			banco: { required: true },
		

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarGastos', function (json) {
				if (json.success) {
					$('#TableMantenimientoGastos').DataTable().ajax.reload();
				}
				$('#ModalEditarGastos').modal('hide');
				$('#FormEditarGastos select[name=tipogastos]').select('val', '');
				$('#FormEditarGastos input[name=nombre]').val('');
				$('#FormEditarGastos select[name=banco]').select('val', '');
				$('#FormEditarGastos input[name=cuenta]').val('');
				$('#FormEditarGastos input[name=operacion]').val('');
				$('#FormEditarGastos input[name=persona]').val('');
				$('#FormEditarGastos input[name=documento]').val('');
				$('#FormEditarGastos input[name=total]').val('');
				$('#FormEditarGastos input[name=observacion]').val('');
				$('#FormEditarGastos select[name=estado]').select('val', '');
			})
		}
	});

	/*==========================================
			 GASTOS - ANULAR
	===========================================*/

	$('#TableMantenimientoGastos').on('click', '.anular-gastos', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		Swal.fire({
			title: "Confirmar",
			type: "warning",
			cancelButtonText: 'No',
			confirmButtonText: 'Si',
			showCancelButton: true,
			confirmButtonColor: "#007AFF",
			cancelButtonColor: "#d43f3a",
			text: "¿Anular Gastos?"
		}).then((result) => {
			if (result.value) {
				$.getJSON(path + 'administrador/regastos/anularGastos', { id }, function (json, textStatus) {
					if (json.success) {
						Swal.fire({
							title: "Buen trabajo",
							text: "Se anulo correctamente.",
							type: "success"
						});
						$('#TableMantenimientoGastos').DataTable().ajax.reload();
					} else {
						Swal.fire({
							title: "Error",
							text: "Ocurrio un error, vuelva a intentarlo.",
							type: "error"
						});
					}
				});
			}
		});
	});
 /*===========================================
	=            TIPO GASTOS - LISTADO            =
	===========================================*/
	var TableMantenimientoTipoGastos = $('#TableMantenimientoTipoGastos').DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
		"aaSorting": [[0, 'desc']],
		"ajax": {
			"url": path + 'administrador/regtipogastos/jsonTipoGastos',
			"type": "GET",
			"data": function (d) {
				d.tb_tipo_gastos = $("input[name=tb_tipo_gastos]").val();

			}
		},
		"columns": [
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": true },
			{ "orderable": false },

		]
	});

	$('#TipoGastosFormBusqueda').validate({
		submitHandler: function () {
			$('#TableMantenimientoTipoGastos').DataTable().ajax.reload();
		}
	});


	/*==========================================
				TIPO GASTOS - AGREGAR
	===========================================*/


	$('#FormTipoGastos').validate({
		rules: {
			descripcion: { required: true },


		},
		submitHandler: function () {
			$('#ModalAgregarTipoGastos').modal('hide');
			enviarFormulario('#FormTipoGastos', function (json) {
				if (json.success) {
					$('#TableMantenimientoTipoGastos').DataTable().ajax.reload();
					$('#FormTipoGastos input[name=descripcion]').val('');


				}


			})
		}
	});



	/*==========================================
			 TIPO GASTOS - EDITAR
	===========================================*/

	$('#TableMantenimientoTipoGastos').on('click', '.editar-tipogastos', function (event) {
		event.preventDefault();
		var id = $(this).data('id');
		$.getJSON(path + 'administrador/regtipogastos/getTipoGastosad', { id }, function (json, textStatus) {
			$('#FormEditarTipoGastos input[name=id]').val(json.cod_tipgastos);
			$('#FormEditarTipoGastos input[name=descripcion]').val(json.descripcion);
			$('#FormEditarTipoGastos select[name=estado]').val(json.estado_tipo);
		});
	});

	$('#FormEditarTipoGastos').validate({
		ignore: [],
		rules: {
			descripcion: { required: true },

		},
		submitHandler: function () {
			enviarFormulario('#FormEditarTipoGastos', function (json) {
				if (json.success) {
					$('#TableMantenimientoTipoGastos').DataTable().ajax.reload();
				}
				$('#ModalEditarTipoGastos').modal('hide');
				$('#FormEditarTipoGastos input[name=descripcion]').val('');
				$('#FormEditarTipoGastos select[name=estado]').select('val', '');
			})
		}
	});




/* ============================================ */
/*                    BALANCE                   */
/* ============================================ */
var TableBalance = $('#TableBalance').DataTable({
	"language": {
		"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
	},
	"searching": false,
	"processing": true,
	"serverSide": true,
	"iDisplayLength": 10,
	"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
	"aaSorting": [[1, 'asc']],
	"ajax": {
		"url": path + 'reportes/balance/jsonBalance',
		"type": "GET",
		"data": function (d) {
			d.desde = $("#FormBalanceFiltro input[name=desde]").val();
			d.hasta = $("#FormBalanceFiltro input[name=hasta]").val();
			d.sede = $('#FormBalanceFiltro select[name=sede]').val()
		}
	},
	"columns": [
		{ "orderable": false },
		{ "orderable": true },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
		{ "orderable": false },
	],
	"initComplete": function(settings, json) {
		calcularBalance(json.totales);
	}
});

$('#FormBalanceFiltro input,#FormBalanceFiltro select').change(function(event) {
	
	$('#TableBalance').DataTable().ajax.reload(function(json){
		calcularBalance(json.totales)
	});

});

function calcularBalance(totales){
	$('#ingresos').html(totales.ingresos);
	$('#egresos').html(totales.egresos);
	$('#balance').html(totales.balance);
}

$('#BalanceImprimirPDF').click(function(event) {
	let form = $('#FormBalanceFiltro').serializeObject();
	let params = $.param(form);
	$(this).attr('href',path+'reportes/balance/reportePDF?'+params);
});

$('#BalanceExcel').click(function(event) {
	let form = $('#FormBalanceFiltro').serializeObject();
	let params = $.param(form);
	$(this).attr('href',path+'reportes/balance/reporteExcel?'+params);
});



/* ============================================ */
/*                  END BALANCE                 */
/* ============================================ */



/*=========================================
=           VERIFICAR STOCK MINIMO       =
===========================================*/
if($('#wrapper[data-stockminimos]').length){
	$.post(path+"reportes/regdashboard/productosStockMinimos", {},
		function (data, textStatus, jqXHR) {
			if(data.success){
				var tr = '';
				$.each(data.data, function (index, value) { 
					tr += `
						<tr>
							<td>${value.nomb_almacen}</td>
							<td>${value.nomb_product}</td>
							<td>${value.nomb_categoria}</td>
							<td>${value.nomb_unid}</td>
							<td>${value.prec_costo}</td>
							<td>${value.stock}</td>
							<td>${value.stockmin_product}</td>
						</tr>
					`;
				});
				$('#TableStockMinimos tbody').html(tr);
			
				
				$('#ModalStockMinimos').modal();
			}
		},
		"JSON"
	);
}

$('#posponer-stockminimo').click(function(){
	$('#ModalStockMinimos').modal('hide');
	$.post(path+"reportes/regdashboard/productosStockMinimosPosponer", {},
		function (data, textStatus, jqXHR) {
			
		},
		"HTML"
	);
})

/*=========================================
=         END VERIFICAR STOCK MINIMO      =
===========================================*/

/* ============================================ */
/*                  CUMPLEAÑOS                  */
/* ============================================ */

$('.radio-cumple').click(function(event) {
	if($(this).val()=='mes'){
		$('#MesCumple').show();
	}else{
		$('#MesCumple').hide();
	}
});


$('#FormCumpleanos').validate({
	debug: false,
	submitHandler:function() {
		$.post(path+"administrador/regcliente/cumpleanos", $('#FormCumpleanos').serializeObject(),
			function (data, textStatus, jqXHR) {
				var tr = '';
				if(data.query.length==0){
					Swal.fire({
						title: "Ninguno",
						text: "No se encontro ningun paciente que cumpla años.",
						type: "info"
					});
					return;
				}
				$.each(data.query, function (index, value) { 
					 tr += `
					 	<tr>
							<td>${value.nomb_cliente}</td>
							<td>${value.fena_pac}</td>
							<td>${calcularEdad(value.fena_pac)} años</td>
							
						</tr>
					 `;
				});
				console.log(tr);
				$('#TableCumpleanos tbody').html(tr);
			},
			"JSON"
		);
	}
});


function calcularEdad(fecha) {
	var hoy = new Date();
	var cumpleanos = new Date(fecha);
	var edad = hoy.getFullYear() - cumpleanos.getFullYear();
	var m = hoy.getMonth() - cumpleanos.getMonth();

	if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
			edad--;
	}

	return edad;
}


/* ============================================ */
/*              END CUMPLEAÑOS                  */
/* ============================================ */

/* ============================================ */
/*              IMPORTAR PRODUCTOS                  */
/* ============================================ */

$('#ImportarPlantilla').fileupload({
	url: path+'administrador/regproducto/uploadPlantilla',
	  dataType: 'json',
	  autoUpload: false,
	  acceptFileTypes: /(\.|\/)(xlsx)$/i,
	done: function (e, data) {
		  $("#ImportarPlantilla").find(".files").empty();
		  $('.progress').hide();
		  if(data.result.success==true){
			  if(data.result.importar.success==false){
				  var error = '';
				  $.each(data.result.importar, function (indexError, valueError) { 
					  $.each(valueError, function (indexColumna, valueColumna) { 
						  $.each(valueColumna, function (indexI, value) { 
							  error += `<li>${value}</li>`;							 
						  });
					  });
				  });
				  $('#errores-plantilla').html(error);
				  $('#alert-errres-plantilla').show();
			  }else{
				  Swal.fire({
					  title: "Buen trabajo",
					  text: "Se importaron "+data.result.importar.num_filas+" registros.",
					  type: "success"
				  });
			  }
		  }else{
			  $('#alert-errres-plantilla').html('<li>'+data.result.error+'</li>').show();
		  }
	},
	progressall: function (e, data) {
	  var progress = parseInt(data.loaded / data.total * 100, 10);
	  $('.progress').show();
	  $('.progress .progress-bar').css(
		'width',
		progress + '%'
	  );
	},
	  add: function (e, data) {
		  
		  if(data.originalFiles[0].type!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
			  Swal.fire({
				  title: "Error",
				  text: "El archivo no es válido",
				  type: "error"
			  });
			  return;
		  }
		  $('#alert-errres-plantilla').hide();
		  $('.label-productos').html(data.originalFiles[0].name);
		  $("#iniciarImportacionProducto").off('click').on('click', function () {
				  data.submit();
		  });
	  }
  })
  .prop('disabled', !$.support.fileInput)
	  .parent().addClass($.support.fileInput ? undefined : 'disabled'); 
  
   
  
	  $('#ImportarPlantillaStock').fileupload({
		  url: path+'administrador/regproducto/uploadPlantillaStock',
		  dataType: 'json',
		  autoUpload: false,
		  acceptFileTypes: /(\.|\/)(xlsx)$/i,
		  done: function (e, data) {
			  $("#ImportarPlantillaStock").find(".files").empty();
			  $('.progressStock').hide();
			  if(data.result.success==true){
				  if(data.result.importar.success==false){
					  var error = '';
					  $.each(data.result.importar, function (indexError, valueError) { 
						  $.each(valueError, function (indexColumna, valueColumna) { 
							  $.each(valueColumna, function (indexI, value) { 
								  error += `<li>${value}</li>`;							 
							  });
						  });
					  });
					  $('#errores-plantilla').html(error);
					  $('#alert-errres-plantilla').show();
				  }else{
					  Swal.fire({
						  title: "Buen trabajo",
						  text: "Se importaron "+data.result.importar.num_filas+" registros.",
						  type: "success"
					  });
				  }
			  }else{
				  $('#alert-errres-plantilla').html('<li>'+data.result.error+'</li>').show();
			  }
		  },
		  progressall: function (e, data) {
			  var progress = parseInt(data.loaded / data.total * 100, 10);
			  $('.progressStock').show();
			  $('.progressStock .progress-bar').css(
				  'width',
				  progress + '%'
			  );
		  },
		  add: function (e, data) {
			  
			  if(data.originalFiles[0].type!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
				  Swal.fire({
					  title: "Error",
					  text: "El archivo no es válido",
					  type: "error"
				  });
				  return;
			  }
			  $('#alert-errres-plantilla').hide();
			  $('.label-stock').html(data.originalFiles[0].name);
			  $("#iniciarImportacionStock").off('click').on('click', function () {
					  data.submit();
			  });
		  }
	  })
	  .prop('disabled', !$.support.fileInput)
	.parent().addClass($.support.fileInput ? undefined : 'disabled');

/* ============================================ */
/*              END IMPORTAR                  */
/* ============================================ */


