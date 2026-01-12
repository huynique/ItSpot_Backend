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

    public function selectAnimal(): array
    { try {
        
        $linkDB = $this->linkDB();   
        
        $stmt = $linkDB->query("SELECT animalid, trivialname, sciencename, lastseen, sightingscount, animalcount FROM animals");

        return $stmt ->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) 
    {
        new \ppb\Library\Msg(true, "Ein Fehler" , $e);
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

        $stmt->execute([
            ':animalid' => $data['animalid'],
            ':trivialname' => $data['trivialname'],
            ':sciencename' => $data['sciencename'],
            ':lastseen' => \DateTime::createFromFormat('d.m.Y', $data['lastseen']),
            ':sightingscount' => $data['sightingscount'],
            ':animalcount' => $data['animalcount'],
        ]); 

    } catch (\PDOException $e) {
        new \ppb\Library\Msg(true, "Fehler beim Speichern", $e);
    }
    }

}
