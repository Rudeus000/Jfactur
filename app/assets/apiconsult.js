function consultar_numero_doc_cliente(input_idusuario, num_doc, input_nombre, input_direccion, input_ubigeo, tipo_doc, input_email, input_telefono) {
    $("#icon_search_document").hide();
    $("#icon_searching_document").show();
    $(".search_document").prop('disabled', true);
    input_idusuario.val('');
    $("#cliente_api_foto").val('');
    $("#cliente_api_fecha_nac").val('');
    $("#cliente_api_sexo").val('');

    $.ajax({
        url : '/sys_fe/herramientas/get_data_cliente',
        data: {tipo_doc: tipo_doc, num_doc: num_doc},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);
                        if (typeof data.data.api !== 'undefined') {
                            if (typeof data.data.api.success !== 'undefined') {
                                if(data.data.api.success === true) {
                                    if (typeof data.data.api.result !== 'undefined') {
                                        if (typeof data.data.api.result.desDireccion !== 'undefined') {
                                            input_direccion.val(data.data.api.result.desDireccion);
                                        }

                                        if (typeof data.data.api.result.feNacimiento !== 'undefined') {
                                            $("#cliente_api_fecha_nac").val(data.data.api.result.feNacimiento);

                                            var parts =data.data.api.result.feNacimiento.split('/'); 
                                            let edad = calcular_edad(parts[2] + '-' + parts[1] + '-' + parts[0]);
                                            if(edad >= 18) {
                                                $("#titulo_nombrecliente").html("Nombre del Cliente <strong class='text-success'> (Edad: " + edad + " Años)</strong>");
                                            } else {
                                                $("#titulo_nombrecliente").html("Nombre del Cliente <strong class='text-primary'> (Edad: " + edad + " Años)</strong>");
                                            }
                                        } else {
                                            $("#titulo_nombrecliente").html("Nombre del Cliente: ");
                                        }

                                        if (typeof data.data.api.result.sexo !== 'undefined') {
                                            $("#cliente_api_sexo").val(data.data.api.result.sexo);
                                        }
                                    }

                                    if (typeof data.data.api.images !== 'undefined') {
                                        if (typeof data.data.api.images.foto !== 'undefined') {
                                            $("#cliente_api_foto").val(data.data.api.images.foto);
                                            $("#cliente_api_foto_src").attr("src", "data:image/png;base64, " + data.data.api.images.foto);
                                            $("#content_cliente_api_foto_src").show();
                                            $("#content_razon_social_cliente").css("display", "table");
                                        } else {
                                            $("#content_cliente_api_foto_src").hide();
                                            $("#content_razon_social_cliente").css("display", "block");
                                        }
                                    }
                                }
                            }
                        }
                        if (typeof data.codigo_ubigeo !== 'undefined' && typeof data.texto_ubigeo !== 'undefined') {
                            input_ubigeo.append('<option value="' + data.codigo_ubigeo + '">' + data.texto_ubigeo + '</option>');
                            input_ubigeo.val(data.codigo_ubigeo).trigger("select2:select");
                        }
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_telefono.val(data.data.celular);
                        //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                        input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");

                        if (typeof data.data.foto !== 'undefined') {
                            if(data.data.foto !== null && data.data.foto !== '') {
                                $("#cliente_api_foto").val(data.data.foto);
                                $("#cliente_api_foto_src").attr("src", data.data.foto);
                                $("#content_cliente_api_foto_src").show();
                                $("#content_razon_social_cliente").css("display", "table");
                            } else {
                                $("#content_cliente_api_foto_src").hide();
                                $("#content_razon_social_cliente").css("display", "block");
                            }
                        }

                        if (typeof data.data.fecha_nac !== 'undefined') {
                            if(data.data.fecha_nac !== null && data.data.fecha_nac !== '') {
                                let edad = calcular_edad(data.data.fecha_nac);
                                if(edad >= 18) {
                                    $("#titulo_nombrecliente").html("Nombre del Cliente <strong class='text-success'> (Edad: " + edad + " Años)</strong>");
                                } else {
                                    $("#titulo_nombrecliente").html("Nombre del Cliente <strong class='text-primary'> (Edad: " + edad + " Años)</strong>");
                                }
                            } else {
                                $("#titulo_nombrecliente").html("Nombre del Cliente: ");
                            }
                        } else {
                            $("#titulo_nombrecliente").html("Nombre del Cliente: ");
                        }
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion);
                        if(typeof data.data.codigo_ubigeo !== 'undefined' && data.data.codigo_ubigeo != '') {
                            //input_ubigeo.val(data.data.codigo_ubigeo).trigger("change").trigger("select2:select");
                            input_ubigeo.append('<option value="' + data.data.codigo_ubigeo + '">' + data.texto_ubigeo + '</option>');
                            input_ubigeo.val(data.data.codigo_ubigeo).trigger("select2:select");
                        }
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_telefono.val(data.data.celular);
                        //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                        input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");
                    }

                    if(typeof data.data.estado !== 'undefined' && data.data.estado != '') {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                        
                        if(data.data.estado == 'ACTIVO' || data.data.estado == 'activo') {
                            $("#estado_numerodocumento").addClass("form-group col-md-4 text-success");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-5 text-success");
     
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        } else {
                            $("#estado_numerodocumento").addClass("form-group col-md-4 text-danger");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-5 text-danger");
    
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        }
                    } else {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#estado_numerodocumento").addClass("form-group col-md-4");
                        $("#razonsocial_numerodocumento").addClass("form-group col-md-5");
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                    }
                }
            } else if(tipo_doc == 0) {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    input_direccion.val(data.data.direccion_fiscal);
                    //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                    input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");
                    input_email.val(data.data.email);
                    input_telefono.val(data.data.celular);
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    input_direccion.val(data.data.direccion_fiscal);
                    input_telefono.val(data.data.celular);
                    //input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    input_ubigeo.append('<option value="' + data.data.id_cod_ubigeo + '">' + data.texto_ubigeo + '</option>');
                    input_ubigeo.val(data.data.id_cod_ubigeo).trigger("select2:select");
                }
            }

            $("#control_gremision_electronica").hide('slide');
            if (typeof data.lista_guias !== "undefined") {
                if(data.lista_guias.length > 0) {
                    $("#control_gremision_electronica").show('slide');
                }
                $("#id_guia_remision_electronica").empty();
                $("#id_guia_remision_electronica").append('<option value="">Selecciona un Documento</option>');
                $.each(data.lista_guias, function(key, item) {
                    $("#id_guia_remision_electronica").append('<option value="' + item.id + '">' + item.text + '</option>');
                });
            }

            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document").show();
                $("#icon_searching_document").hide();
                $(".search_document").prop('disabled', false);
            });
        }
    }, function(reason){
        swal({
            title: 'ERROR',
            text: 'Error al conectarse a la SUNAT, recarga la página e inténtalo nuevamente!',
            html: true,
            type: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        });
    });
}