/* ============================================ */
/*              Helpers                  */
/* ============================================ */
var _items=[];
var _index=0;
var newHtml=null;
getList=function(path,obj,dataJSON,extras){
    $.ajax({
            url:path,
            type:'get',
            dataType:'json',
            data:dataJSON,
            beforeSend:function(){
                    $('#'+obj).html('<option></option>').attr('disabled',true);
                    if (typeof extras != 'undefined' && extras == 'object') {
            if (typeof extras.before != 'undefined'  && typeof extras.before=='function') {
                            extras.before();
                    }
              }
            },
            success:function(result){
                if(result.status==1 && result.data.length>0){
                    if(extras=='TODOS'){
                        loadList(obj,result.data,1);
                    }
                    else{
                        loadList(obj,result.data,0);
                    }
                }else{
                    $('#'+obj).empty().append('<option>SIN DATOS</option>').attr('disabled',true);
                }
            },
            complete:function(){
                if(typeof extras!='undefined' && typeof extras=='object'){
                    if(typeof extras.selected!='undefined'){
                         $('#'+obj).val(extras.selected);
                    }

                    if(typeof extras.trigger!='undefined' && typeof extras.trigger=='function'){
                         $('#'+obj).trigger('change');
                    }

                    if(typeof extras.finish!='undefined' && typeof extras.finish=='function'){
                         extras.finish();
                    }
                    if(typeof extras!='undefined' && typeof extras=='TODOS'){
                         $('#'+obj+' option:eq(0)').html('TODOS');
                    }
                }
            }
    });
};
loadList=function(obj,data,indica){
    obj=$('#'+obj);
    if(indica==1){
        newHtml='<option value="00">TODOS</option>';
    }
    else{
        newHtml='<option value="00">[SELECCIONE]</option>';
    }
    $.each(data,function(key, field){
        _index=0;
        $.each(field,function(key,item){
                _items[_index]=item;
                _index++;
        });
        newHtml+='<option value="'+_items[0]+'">'+$.trim(_items[1])+'</option>';
    });
    obj.empty().append(newHtml).attr('disabled',false);
};

MessageBox=function(msg,title='',icon='error'){
    swal.fire(
	 msg,
	 title,
	  icon
	);
};
/* ============================================ */
/*              End Helpers                  */
/* ============================================ */