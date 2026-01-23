<?php

namespace ppb\Controller;

use ppb\Model\SightingModel;

class AnimalController {
    
    public function __construct()
    {
        
    }

    public function getSightings() 
    {
        $model = new SightingModel() ;
        echo json_encode($model->selectSighting(),JSON_PRETTY_PRINT) ; 
    }

    public function getFilteredSightings(): void
    {
        $model = new SightingModel() ;
        $filter = [];
        if(isset($_GET['minAnimalCount'])) {
            $filter['minAnimalCount'] = (int)$_GET['minAnimalCount'];
        }
        echo json_encode($model->selectAnimal($filter),JSON_PRETTY_PRINT) ; 
    }

    public function writeSighting($data)
    {
    $model = new SightingModel();
    $model->insertAnimal($data);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["success" => true, "msg" => "Animal was posted"]);
    }

   
}