<?php

namespace ppb\Controller;

use ppb\Model\AnimalModel;

class AnimalController {
    
    public function __construct()
    {
        
    }

    public function getAnimal() 
    {
        $model = new AnimalModel() ;
        echo json_encode($model->selectAnimal(),JSON_PRETTY_PRINT) ; 
    }

    public function writeAnimal($data)
{
    $model = new AnimalModel();
    $model->insertAnimal($data);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["success" => true, "msg" => "Animal wurde gespeichert"]);
}

   
}