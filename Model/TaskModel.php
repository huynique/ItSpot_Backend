<?php

namespace ppb\Model;

use ppb\Library\Msg;
use ppb\Model\Database;

class TaskModel extends Database 
{
    public function __construct()
    {
        //link

    }
    public function selectTask(): array
    { try {
        
        $linkDB = $this->linkDB();   

        $stmt = $linkDB->query("SELECT task.id, task.title, task.expense, task.dueDate, task.done, priority.description FROM task INNER JOIN priority on task.priorityId = priority.id");



        return $stmt ->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) 
    {
        new \ppb\Library\Msg(true, "Ein Fehler" , $e);
    }
    }
    
    public function insertTask(array $data): void
    {
    try {
        $pdo = $this->linkDB();

        $stmt = $pdo->prepare("
            INSERT INTO task (id, title, expense, dueDate, priorityId)
            VALUES (:id, :title, :expense, :dueDate, :priorityId)
        ");

        // UUID erzeugen (du hast ja createUUID() in Database)
        $uuid = $this->createUUID();

        $stmt->execute([
            ':id' => $uuid,
            ':title' => $data['title'],
            ':expense' => $data['expense'],
            ':dueDate' => $data['dueDate'],
            'priorityId' => $data['priorityId'],
        ]);

    } catch (\PDOException $e) {
        new \ppb\Library\Msg(true, "Fehler beim Speichern", $e);
    }
}
}
