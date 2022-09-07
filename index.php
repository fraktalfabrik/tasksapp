<?php
// requirements:
// PHP8 - SQLite - web server

if(phpversion() <8){ die("PHP 8 required"); };

// ini_set('display_errors',  1);ini_set('display_startup_errors',1);error_reporting(E_ALL);


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
favicon.ico

?><!DOCTYPE html><html><head><title>My Tasks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="assets/css/tasks.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/js/tasks.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32x32.png">


</head>
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
<script>$( document ).ready(function() {
 getAll()

});</script>
</body>
</html>