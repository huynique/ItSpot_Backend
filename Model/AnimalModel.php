<?php

namespace ppb\Model;

use ppb\Library\Msg;
use ppb\Model\Database;

class AnimalModel extends Database 
{
    public function __construct()
    {
        //link
    }

    
public function selectAnimal(array $filter = []): array
{
    try {
        $pdo = $this->linkDB();

        $query = "SELECT animalid, trivialname, sciencename, lastseen, sightingscount, animalcount FROM animals";

        $where = [];
        $params = [];

        /*
        if (isset($filter['minAnimalCount'])) {
            $where[] = "animalcount >= :minAnimalCount"; 
            $params[':minAnimalCount'] = (int)$filter['minAnimalCount'];
        }
            */
        if (isset($filter['minSightingsCount'])) {
            $where[] = "sightingscount >= :minSightingsCount"; 
            $params[':minSightingsCount'] = (int)$filter['minSightingsCount'];
        }

        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params); // Parameter übergeben!

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    } catch (\PDOException $e) {
        new \ppb\Library\Msg(true, "Ein Fehler", $e);
        return [];
    }
}


    public function insertAnimal(array $data): void
    {
    try {
        $pdo = $this->linkDB();

        $stmt = $pdo->prepare("
            INSERT INTO animals (animalid, trivialname, sciencename, lastseen, sightingscount, animalcount)
            VALUES (:animalid, :trivialname, :sciencename, :lastseen, :sightingscount, :animalcount)
        ");

        // UUID erzeugen (du hast ja createUUID() in Database)
        $uuid = $this->createUUID();
        $dt = \DateTime::createFromFormat('d.m.Y', $data['lastseen']);

        $stmt->execute([
            ':animalid' => $data['animalid'],
            ':trivialname' => $data['trivialname'],
            ':sciencename' => $data['sciencename'],
            ':lastseen' => $dt ? $dt->format('Y-m-d') : null,
            ':sightingscount' => $data['sightingscount'],
            ':animalcount' => $data['animalcount'],
        ]); 

    } catch (\PDOException $e) {
        new \ppb\Library\Msg(true, "Fehler beim Speichern", $e);
    }
    }

}
