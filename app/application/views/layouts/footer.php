<footer class="footer text-center">
    2019 - 2025 © BEE COMPANY - SOFTWARE SMS
</footer>
<!-- <script src="<?= base_url_app() ?>assets/js/quill.min.js"></script> -->
<script src="<?= base_url_app() ?>assets/js/jquery.min.js"></script>

<script src="<?= base_url_app() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url_app() ?>assets/js/metisMenu.min.js"></script>
<script src="<?= base_url_app() ?>assets/js/waves.js"></script>
<script src="<?= base_url_app() ?>assets/js/jquery.slimscroll.js"></script>

<!-- Dropzone js -->
<script src="<?= base_url_app() ?>assets/plugins/dropzone/dropzone.js"></script>

<script src="<?= base_url_app() ?>assets/plugins/switchery/switchery.min.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/moment/moment.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.es.min.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"
    type="text/javascript"></script>
<script src="<?= base_url_app() ?>assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?= base_url_app() ?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"
    type="text/javascript"></script>

<script src="<?php echo base_url_app(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url_app(); ?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Validate-->
<script src="<?php echo base_url_app(); ?>assets/plugins/jquery-validate/jquery.validate.js"></script>
<script src="<?php echo base_url_app(); ?>assets/plugins/jquery-validate/additional-methods.js"></script>
<script src="<?php echo base_url_app(); ?>assets/plugins/jquery-validate/localization/messages_es.js"></script>
<script src="<?php echo base_url_app(); ?>assets/plugins/jquery-form/jquery.form.js"></script>

<script src="<?= base_url_app() ?>assets/plugins/EasyAutocomplete-1.3.5/jquery.easy-autocomplete.js"></script>

<!-- Init Js file -->
<script type="text/javascript" src="<?= base_url_app() ?>assets/pages/jquery.form-advanced.init.js"></script>

<!-- Counter js  -->
<script src="<?= base_url_app() ?>assets/plugins/waypoints/jquery.waypoints.min.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/counterup/jquery.counterup.min.js"></script>

<!-- Modal-Effect -->
<script src="<?= base_url_app() ?>assets/plugins/custombox/js/custombox.min.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/custombox/js/legacy.min.js"></script>

<!-- App js -->
<script src="<?= base_url_app() ?>assets/js/jquery.core.js"></script>
<script src="<?= base_url_app() ?>assets/js/jquery.app.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

<script src="<?= base_url_app() ?>assets/template/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url_app() ?>assets/plugins/tinymce/tinymce.min.js"></script>
<script src="<?= base_url_app() ?>assets/jquery-upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?= base_url_app() ?>assets/jquery-upload/js/jquery.fileupload.js"></script>

<script src="<?= base_url_app() ?>assets/jquery-toast/src/jquery.toast.js"></script>
<script src="<?= base_url_app() ?>assets/codeseven/build/toastr.min.js"></script>

<script src="<?= base_url_app() ?>assets/main.js?v=<?= time() ?> "></script>

<!-- Highcharts -->
<script src="<?php echo base_url_app(); ?>assets/template/highcharts/highcharts.js"></script>
<script src="<?php echo base_url_app(); ?>assets/template/highcharts/exporting.js"></script>
<script src="<?php echo base_url_app(); ?>assets/template/highcharts/export-data.js"></script>

<script src="<?php echo base_url_app() ?>assets/plugins/chart.js/chart.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/js/uikit-icons.min.js"></script>
<script src="<?= base_url_app() ?>assets/plugins/emojis/vanillaEmojiPicker.js"></script>

<script type="text/javascript">
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

        tipo_doc = $('#tipo_documento').val();

        if (tipo_doc == "") {

            alert("Debes seleccionar un tipo de documento.");

        } else {

            $('#capa_load').html('<img src="<?= base_url_app() ?>assets/images/loading.gif" alt="" style="position: absolute;top: 10px;left: 46%;">');

            $.post('<?= base_url() ?>Validardatos/validarDocumento', {
                dni: $('#txt_documento').val(),
                tipo_doc: tipo_doc
            }, function (data) {



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

                    doc = $('#txt_documento').val();

                    if (doc.length < 11) {
                        alert("ingrese ruc valido");

                    }

                    if (datos[0] == nada) {

                        alert('RUC no válido o no registrado');

                        $('#capa_load').html("");

                        $("#FormVentaAgregarCliente")[0].reset();

                    } else {

                        $('#txt_documento').val(datos[0]);
                        $('#txt_nombre').val(datos[1]);

                        // $('#txt_inicio_actividad').val(datos[2]);

                        // $('#txt_condicion').val(datos[3]);

                        // $('#txt_tipo_contribuyente').val(datos[4]);

                        // $('#txt_estado_contribuyente').val(datos[5]);

                        // $('#txt_fecha_inscripcion').val(datos[6]);

                        $('#txt_direccion').val(datos[7] + " - " + datos[8] + " - " + datos[9] + " - " + datos[10]);

                        // $('#txt_emision_electronica').val(datos[8]);

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
</script>

<!-- <script language="javascript">
var base_url_app = '<?php echo base_url_app(); ?>';
var path = '<?= base_url_app(); ?>';
</script> -->

<script>
    $('#ModalMensajeFlash').modal();

    colores = ['rgb(255 0 0 / 85%)', 'rgba(0,166,90,0.85)', 'rgba(0,192,239,0.85)', 'rgba(221,75,57,0.85)'];
    $.post(path + "reportes/regdashboard/getCumpleanos", {},
        function (data, textStatus, jqXHR) {
            var num = 0;
            $.each(data, function (indexInArray, val) {
                $.toast({
                    loader: false,
                    text: "Hoy cumple años " + val.nomb_cliente + ', deseale un feliz cumpleaños <button onclick="desactivarCumpleano(' + val.id_cliente + ')" id="cumpleanos-' + val.id_cliente + '" class="btn btn-black btn-md desactivarCumpleano"><span class="fa fa-check"></span></button>',
                    showHideTransition: 'slide', // It can be plain, fade or slide
                    bgColor: colores[num], // Background color for toast
                    textColor: '#fff', // text color
                    allowToastClose: true, // Show the close button or not
                    hideAfter: 1000, // `false` to make it sticky or time in miliseconds to hide after
                    stack: 5, // `fakse` to show one stack at a time count showing the number of toasts that can be shown at once
                    textAlign: 'left', // Alignment of text i.e. left, right, center
                    position: 'top-right' // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values to position the toast on page
                });
                if (num == 3) {
                    num = 0;
                } else {
                    num++;
                }
            });
        },
        "JSON"
    );

    function desactivarCumpleano(id) {
        var id_cliente = id;
        $('#cumpleanos-' + id_cliente).parent('.jq-toast-single').hide()
        $.post(path + "reportes/regdashboard/desactivarCumpleano", {
            id: id_cliente
        },
            function (data, textStatus, jqXHR) {

            },
            "JSON"
        );
    }
</script>
<script>
    $('#ModalMensaje').modal();

    colores = ['rgb(122 0 255 / 89%)', 'rgba(0,166,90,0.85)', 'rgba(0,192,239,0.85)', 'rgba(221,75,57,0.85)'];
    $.post(path + "reportes/regdashboard/getUsu", {},
        function (data, textStatus, jqXHR) {
            var num = 0;
            $.each(data, function (indexInArray, val) {
                $.toast({
                    heading: '¡Feliz cumpleaños!',
                    // loader:false,
                    text: val.nomb_usu + ' Hoy es un dia especial de prosperidad y felicidad que dios te bendiga <button onclick="desactivarCumpleanousu(' + val.cod_usu + ')" id="cumpleanosusu-' + val.cod_usu + '" class="btn btn-black btn-md desactivarCumpleanousu"><span class="fa fa-check"></span></button>',
                    icon: 'info',
                    showHideTransition: 'slide', // It can be plain, fade or slide
                    bgColor: colores[num], // Background color for toast
                    textColor: '#fff', // text color
                    allowToastClose: true, // Show the close button or not
                    hideAfter: 1000, // `false` to make it sticky or time in miliseconds to hide after
                    stack: 5, // `fakse` to show one stack at a time count showing the number of toasts that can be shown at once
                    textAlign: 'left', // Alignment of text i.e. left, right, center
                    position: 'top-right' // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values to position the toast on page
                });
                if (num == 3) {
                    num = 0;
                } else {
                    num++;
                }
            });
        },
        "JSON"
    );

    function desactivarCumpleanousu(id) {
        var cod_usu = id;
        $('#cumpleanosusu-' + cod_usu).parent('.jq-toast-single').hide()
        $.post(path + "reportes/regdashboard/desactivarCumpleanousu", {
            id: cod_usu
        },
            function (data, textStatus, jqXHR) {

            },
            "JSON"
        );
    }
</script>
<script>
    // $('#ModalMensajeFecha').modal();

    // colores = ['rgb(255 0 0 / 85%)', 'rgba(0,166,90,0.85)', 'rgba(0,192,239,0.85)', 'rgba(221,75,57,0.85)'];
    $.post(path + "reportes/regdashboard/getFechaemp", {},
        function (data, textStatus, jqXHR) {
            console.log(data);
            var num = 0;
            $.each(data, function (indexInArray, val) {
                var mensaje = '';

                // Verificar si los permisos están desactivados
                if (val.permisos_desactivados) {
                    mensaje = "Estimado(a) cliente tienes un pago pendiente vencido, regulariza a la brevedad para reactivar el servicio. Gracias.";
                } else if (val.hoy_corte) {
                    mensaje = "Estimado(a) cliente. Evita las molestias de corte de servicio pagando a tiempo su facturacion. Hoy a media noche se corta su servicio";
                } else {
                    var dias_restantes = val.dias_restantes;
                    if (dias_restantes <=5) {
                        mensaje = "Estimado(a) cliente, su ciclo de facturacion vence en " + dias_restantes + " día" + (dias_restantes > 1 ? "s" : "") + ". Evita las molestias de corte de servicio, pagando a tiempo. Gracias";
                    }else{
                        return;
                    }
                }

                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }

                toastr["error"](mensaje, "Atencion")
            });
        },
        "JSON"
    );

</script>

<script type="text/javascript">
    $('#modal1').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal1 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal2').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal2 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal3').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal3 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal4').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal4 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal5').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal5 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal6').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal6 iframe').attr("src", $("#modal6 iframe").attr("src"));
    });
    $('#modal7').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal7 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal8').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal8 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal9').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal9 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal10').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal10 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
    $('#modal11').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal11 iframe').attr("src", $("#modal4 iframe").attr("src"));
    });
    $('#modal12').on('hidden.bs.modal', function (e) {
        // do something...
        $('#modal12 iframe').attr("src", $("#modal1 iframe").attr("src"));
    });
</script>

<script>


class oCopo{


  constructor(tam, id){


     this.x = 0;


     this.y = 0;


     this.size = tam;


     this.nombre = id


     this.obj = document.createElement("div");


     this.obj.setAttribute('id',id);


     this.obj.innerText="*";


     this.obj.style.position = "absolute";


     this.obj.style.fontSize = tam+"px";


     this.obj.style.color = "white";


     document.body.appendChild(this.obj)


}


dibujar(x,y){


     this.x = x;


     this.y = y;


     this.obj.style.top = this.y+"px";


     this.obj.style.left = this.x+"px";


     }


}


function iniCopos(num, anc, alto){


   var copos = new Array(num);


   var tam, x, y;


   for (let i = 0; i<num; i++)


     {


     tam = Math.round(Math.random()*10)+ 8;


     copos[i] = new oCopo(tam, "c"+i);


     x = parseInt(Math.random()*anc);


     y = parseInt(Math.random()*alto);


     copos[i].dibujar(x,y);


     }


return copos;


}


function iniNevada(num, vel)


{


var ancho = document.body.offsetWidth-10;


var alto = window.innerHeight-10;


var losCopos = iniCopos(num, ancho, alto)


nevar(losCopos, ancho,alto, vel);


} 


function nevar(copos, coposAncho, coposAlto, vel)


{


var x, y;


for (let i = 0; i < copos.length; i++)


    {


    y = copos[i].y;


     x = copos[i].x;


    if (Math.random() > 0.5)


        y += parseInt(Math.random()+1);


    y += parseInt(Math.random()+2);


    if (y >= (coposAlto - copos[i].size))


        {


        y = Math.round(Math.random()*10);


        x  =parseInt(Math.random()*coposAncho-1); 


        }


copos[i].dibujar(x,y); 


    }


setTimeout(nevar, vel, copos,  coposAncho, coposAlto, vel);


}



</script>