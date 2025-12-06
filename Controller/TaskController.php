<?php

namespace ppb\Controller;

use ppb\Model\Database;
use ppb\Model\TaskModel;

class TaskController {
    
    public function __construct()
    {
        
    }

    public function getTask() 
    {
        $model = new TaskModel() ;
        echo json_encode($model->selectTask(),JSON_PRETTY_PRINT) ; 

    }

    public function writeTask($data)
{
    $model = new TaskModel();
    $model->insertTask($data);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["success" => true, "msg" => "Task wurde gespeichert"]);
}

    
   
}