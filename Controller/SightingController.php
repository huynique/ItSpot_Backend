<?php

namespace ppb\Controller;

use ppb\Model\SightingModel;

class SightingController {

    public function __construct() {}

    public function getSighting(): void
    {
        $model = new SightingModel();
        $filter = [];

        // Whitelist der erlaubten Parameter
        $map = [
            'family'            => 'string',
            'animalid'          => 'int',
            'minAnimalCount'    => 'int',
            'minSightingsCount' => 'int',
            'fromDate'          => 'string', // 'YYYY-MM-DD'
            'toDate'            => 'string',
            'orderBy'           => 'string', // wird im Model auf Whitelist geprüft
            'orderDir'          => 'string', // ASC/DESC
            'limit'             => 'int',
            'offset'            => 'int',
        ];

        foreach ($map as $key => $type) {
            if (isset($_GET[$key]) && $_GET[$key] !== '') {
                $val = $_GET[$key];
                if ($type === 'int') {
                    $filter[$key] = (int)$val;
                } else {
                    $filter[$key] = $val;
                }
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($model->selectSighting($filter), JSON_PRETTY_PRINT);
    }

    // Optional: kannst du lassen oder ebenfalls erweitern
    public function getFilteredSighting(): void
    {
        $model = new SightingModel();
        $filter = [];

        // Falls du diese Route weiterhin nutzen willst, hier genauso alle Parameter erlauben:
        $allowed = ['family','animalid','minAnimalCount','minSightingsCount','fromDate','toDate','orderBy','orderDir','limit','offset'];
        foreach ($allowed as $k) {
            if (isset($_GET[$k]) && $_GET[$k] !== '') {
                $filter[$k] = $_GET[$k];
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($model->selectSighting($filter), JSON_PRETTY_PRINT);
    }

    public function writeSighting($data)
    {
        $model = new SightingModel();
        $model->insertSighting($data);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(["success" => true, "msg" => "Animal was posted"]);
    }
}