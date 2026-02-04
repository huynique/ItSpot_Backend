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

    public function getFilteredAnimal(): void
    {
        $model = new AnimalModel() ;
        $filter = [];
        if(isset($_GET['family'])) {
            $filter['family'] = $_GET['family'];
        }
        if(isset($_GET['animalid'])) {
            $filter['animalid'] = $_GET['animalid'];
        }

        echo json_encode($model->selectAnimal($filter),JSON_PRETTY_PRINT) ; 
    }

    public function writeAnimal($data)
    {
    $model = new AnimalModel();
    $model->insertAnimal($data);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["success" => true, "msg" => "Animal was posted"]);
    }

   
}