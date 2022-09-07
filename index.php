<?php
// requirements:
// PHP8 - SQLite - web server

if(phpversion() <8){ die("PHP 8 required"); };

ini_set('display_errors',           1);
ini_set('display_startup_errors',   1);
error_reporting(E_ALL);
include_once( 'model/service/Task.php');

// ========================================= AJAX =======================================
if (isset($_GET['ajax'])) {

    $cmd  = $_GET['ajax'];
    $ret  = array('status' => 'error', 'received' => $_REQUEST); // default return values

    $TASK = new Task();

    switch ($cmd) {

        case 'fetchAll':
            $ret['result'] = $TASK->fetchAll();
            $ret['status'] = 'ok';
            break;


        case 'update':
            if($ret['result'] = $TASK->updateOne($_GET['id'],$_POST)){
                $ret['status'] = 'ok';
            }
            break;


        case 'create':
            if($ret['result'] = $TASK->createOne($_POST)){
               $ret['status'] = 'ok';
            }
            break;


        case 'delete':
            if ($ret['result'] = $TASK->deleteOne($_GET['id'])) {
                $ret['status'] = 'ok';
            }
            break;



        case 'tick':
            if($ret['result'] = $TASK->updateBoolean($_GET['id'],'completed')){
                $ret['status'] = 'ok';
            }
            break;

        default:
            $ret['result'] = "command: '$cmd' unknown";
            break;
    }
    die(json_encode($ret));
}
// ============ END AJAX ============================================================


?><!DOCTYPE html><html><head><title>My Tasks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<style>
    .btn-primary{
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#6db3f2+0,54a3ee+50,3690f0+51,1e69de+100;Blue+Gloss+%233 */
        background: rgb(109,179,242); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(109,179,242,1) 0%, rgba(84,163,238,1) 50%, rgba(54,144,240,1) 51%, rgba(30,105,222,1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6db3f2', endColorstr='#1e69de',GradientType=0 ); /* IE6-9 */
 }
    .btn-secondary{
        margin-left: 2px;
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#afbcc7+0,a0aebc+50,91a1b3+51,7e8ca2+100 */
        background: rgb(175,188,199); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(175,188,199,1) 0%, rgba(160,174,188,1) 50%, rgba(145,161,179,1) 51%, rgba(126,140,162,1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  rgba(175,188,199,1) 0%,rgba(160,174,188,1) 50%,rgba(145,161,179,1) 51%,rgba(126,140,162,1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  rgba(175,188,199,1) 0%,rgba(160,174,188,1) 50%,rgba(145,161,179,1) 51%,rgba(126,140,162,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#afbcc7', endColorstr='#7e8ca2',GradientType=0 ); /* IE6-9 */

    }

    .headRow{
        letter-spacing: -1px;
        text-shadow: -1px -1px 2px rgba(255,255,255,.5);
        font-weight: bold;font-size: 1.05em;
        color:rgba(0,0,0,.7);
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#b3dced+0,29b8e5+50,bce0ee+100;Blue+Pipe */
        background: rgb(179,220,237); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(179,220,237,1) 0%, rgba(41,184,229,1) 50%, rgba(188,224,238,1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b3dced', endColorstr='#bce0ee',GradientType=0 ); /* IE6-9 */
    }
    .bodyRow{
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#ffffff+0,e5e5e5+100;White+3D */
        background: rgb(255,255,255); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(229,229,229,1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
    }
    .bodyRow:hover{
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#eef0f2+0,d2d8dc+100 */
        background: rgb(238,240,242); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(238,240,242,1) 0%, rgba(210,216,220,1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  rgba(238,240,242,1) 0%,rgba(210,216,220,1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  rgba(238,240,242,1) 0%,rgba(210,216,220,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eef0f2', endColorstr='#d2d8dc',GradientType=0 ); /* IE6-9 */

    }
    #feedbackMsg, #addFormMsg{
        display:none;
        border:1px solid red;
        border-radius: 3px;
        text-align:center;
        color:red;
    }

</style>
<body>
<div class="container">
    <h4>Task Management</h4>
    <p>Instructions go here</p>
    <div id='feedbackMsg' class="row"></div>
    <div class="row card-panel headRow">
        <div class='col s1'>ID</div>
        <div class='col s5 center-align'>Task</div>
        <div class='col s2 center-align'>Created</div>
        <div class='col s2 center-align'>Updated</div>
        <div class='col s1 center-align'>Complete</div>
        <div class='col s1 center-align'>Delete</div>
    </div>
        <div id="maincontent"></div>
        <div id='addFormMsg' class="row"></div>
        <div id="addForm">
            <input type="text" id="name" placeholder="Enter task description"/>
            <a href="javascript: addOne()" class="btn btn-primary">Add Task</a>
        </div>
    </div>
</div>
<script>
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
  $('#feedbackMsg').html("An error occurred. This page will refresh momentarily.");
    $( "#feedbackMsg" ).fadeIn( 3000, function() {
        window.location.reload();
    });
}


function deleteTask(id){
  if(confirm('Are you sure you want to delete ID '+id+'?')){
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
  var date = new Date(d+' UTC'); // sqlite dates are in  by default
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




  getAll();

</script>
</body>
</html>