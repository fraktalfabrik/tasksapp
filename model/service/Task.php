<?php

include_once ('model/Service.php');


class Task extends Service\Service implements  Service\ServiceInterface{
    public $_table = 'tasks';
     // format: field-name => sqlite data-type
     public $_fields_add_edit = array('name' => SQLITE3_TEXT);
}