
function getAll() {
    $('#maincontent').html('<div class="progress"><div class="indeterminate"></div></div>');
    var url = '?ajax=fetchAll';
    $.get(url, function (json) {
        if (json.status == 'ok') {
            $('#maincontent').html('');
            $.each(json.result, function (idx, obj) {

                var html = "<div class='row bodyRow'>";
                html += "<div class='col s1 center-align'>" + obj.id + "</div>";
                html += "<div class='col s5'><a href='javascript:edit(" + obj.id + ")'><span id='edit_"+ obj.id+"'>" + obj.name + "</span></a></div>";
                html += "<div class='col s2'>" + formatDate(obj.created) + "</div>";
                html += "<div class='col s2'>" + formatDate(obj.updated) + "</div>";
                html += "<div class='col s1'><a href='javascript:tickTask(" + obj.id + ")'>" + getTickIcon(obj.completed) + "</a></div>";
                html += "<div class='col s1'><a href='javascript:deleteTask(" + obj.id + ")'><i class='material-icons red-text'>delete</i></a></div>";
                html += "</div>";
                $('#maincontent').append(html);

            })
        } else {
            doHandleError(json);
        }
    }, 'json')
}


function doHandleError(json) {
    $('#feedbackMsg').html("A server error has occurred. This page will refresh in a few seconds.");
    $( "#feedbackMsg" ).fadeIn( 3000, function() {
        window.location.reload();
    });
}


function deleteTask(id){
    if(confirm('Are you sure you want to delete ID: '+id+'?')){
        var url = '?ajax=delete&id='+id;
        $.get(url, function (json) {
            if (json.status == 'ok') {
                getAll();
            } else {
                doHandleError(json);
            }
        },'json')
    }
}

function tickTask(id){
    var url = '?ajax=tick&id='+id;
    $.get(url, function (json) {
        if (json.status == 'ok') {
            getAll();
        } else {
            doHandleError(json);
        }

    },'json')
}


function addOne(){
    var name =  $('#name').val();
    if(name.length< 3 ){
        $('#addFormMsg').html('Please enter at least 3 characters.');
        $('#addFormMsg').fadeIn();
        return;
    }

    $('#addFormMsg').html('');
    var url  = '?ajax=create';
    var data = {'name':name}
    $.ajax({
        type:   "POST",
        url:    url,
        data:   data,
        success: function(json){
            if (json.status == 'ok') {
                getAll();
                $('#name').val('');  $('#name').focus();
            } else {
                doHandleError(json);
            }
        },
        dataType: 'json'
    });
}

function getTickIcon(isComplete){
    if(isComplete == 1) {
        return  '<i class="material-icons">check</i>';
    } else {
        return '<i class="material-icons red-text">assignment</i>';
    }
}

function formatDate(d){
    var date = new Date(d+' UTC'); // sqlite dates are in UTC by default
    return date.toLocaleString("en-AU",{timeZone: "Australia/Brisbane"});
}


var  isEditing = []; // keeping track of opened text-input fields and their content

function edit(id){

    if(isEditing[id]){return}
    var current = $('#edit_'+id).html();
    isEditing[id] = current;
    var ip = "<input type='text' id='ip_"+id+"' />";
    var upd = "<a class='btn btn-primary' href='javascript:update("+id+")'>update</a>";
    var cnl = "<a class='btn btn-secondary' href='javascript:cancelEdit("+id+")'>cancel</a>";
    $('#edit_'+id).html(ip + upd + cnl) ;
    $('#ip_'+id).val(current);
    $('#ip_'+id).focus();
}



function cancelEdit(id){
    if(!isEditing[id]){return}
    var current = $('#edit_'+id).html(isEditing[id]);
    isEditing[id] = false;
}


function update(id){
    $('#ip_'+id).attr('disabled','disabled');
    $('#ip_'+id).css('opacity',.2);
    var url  = '?ajax=update&id='+id;
    var data = {'name': $('#ip_'+id).val()}
    $.ajax({
        type:   "POST",
        url:    url,
        data:   data,
        success: function(json){
            if (json.status == 'ok') {
                isEditing[id] = false;
                getAll();
            } else {
                doHandleError(json);
            }
        },
        dataType: 'json'
    });
}