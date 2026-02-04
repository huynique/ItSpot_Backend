<?php

namespace ppb\Model;

use ppb\Library\Msg;
use ppb\Model\Database;

class SightingModel extends Database 
{
    public function __construct()
    {
        // linkDB kommt aus der Basisklasse
    }

    /**
     * Lädt Sightings und erlaubt Filter, inkl. family (aus animals).
     *
     * Unterstützte Filter:
     *  - family: string ('mammal', 'bird', ...)
     *  - animalid: int
     *  - minAnimalCount: int  (aus animals.animalcount)
     *  - minSightingsCount: int (aus animals.sightingscount)
     *  - fromDate: 'YYYY-MM-DD'
     *  - toDate:   'YYYY-MM-DD'
     *  - orderBy:  'date'|'lastseen'|... (Whitelist, siehe unten)
     *  - orderDir: 'ASC'|'DESC'
     */
    public function selectSighting(array $filter = []): array
    {
        try {
            $pdo = $this->linkDB();

            // Basis-SELECT: Sightings + relevante Animal-Felder
            $sql = "
                SELECT
                    s.sightingsid,
                    s.date,
                    s.animalid,
                    s.ort,
                    s.positive,
                    s.negative,
                    s.status,
                    s.`count`,
                    a.trivialname,
                    a.sciencename,
                    a.family,
                    a.sightingscount,
                    a.animalcount,
                    a.lastseen
                FROM sightings s
                LEFT JOIN animals a ON a.animalid = s.animalid
            ";

            $where  = [];
            $params = [];

            // family (aus animals)
            if (isset($filter['family']) && $filter['family'] !== '') {
                // optional: case-insensitive Vergleich
                $where[] = "LOWER(a.family) = LOWER(:family)";
                $params[':family'] = $filter['family'];
            }

            // animalid (primärschlüssel)
            if (isset($filter['animalid']) && $filter['animalid'] !== '') {
                $where[] = "s.animalid = :animalid";
                $params[':animalid'] = (int)$filter['animalid'];
            }

            // animals-Counts (liegen in der animals-Tabelle)
            if (isset($filter['minAnimalCount']) && $filter['minAnimalCount'] !== '') {
                $where[] = "a.animalcount >= :minAnimalCount";
                $params[':minAnimalCount'] = (int)$filter['minAnimalCount'];
            }

            if (isset($filter['minSightingsCount']) && $filter['minSightingsCount'] !== '') {
                $where[] = "a.sightingscount >= :minSightingsCount";
                $params[':minSightingsCount'] = (int)$filter['minSightingsCount'];
            }

            // Zeitliche Filter (liegen in sightings.date)
            if (!empty($filter['fromDate'])) {
                $where[] = "s.date >= :fromDate";
                $params[':fromDate'] = $filter['fromDate']; // 'YYYY-MM-DD'
            }

            if (!empty($filter['toDate'])) {
                $where[] = "s.date <= :toDate";
                $params[':toDate'] = $filter['toDate'];
            }

            if (!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }

            // Sortierung (Whitelist gegen SQL-Injection)
            $orderBy  = $filter['orderBy'] ?? 's.date';
            $orderDir = strtoupper($filter['orderDir'] ?? 'DESC');

            $allowedOrderBy = [
                's.date', 's.animalid', 'a.trivialname', 'a.family', 'a.sightingscount', 'a.animalcount'
            ];
            if (!in_array($orderBy, $allowedOrderBy, true)) {
                $orderBy = 's.date';
            }
            if (!in_array($orderDir, ['ASC', 'DESC'], true)) {
                $orderDir = 'DESC';
            }
            $sql .= " ORDER BY {$orderBy} {$orderDir}";

            // Optional: Pagination
            if (isset($filter['limit'])) {
                $limit = max(1, (int)$filter['limit']);
                $sql .= " LIMIT {$limit}";
                if (isset($filter['offset'])) {
                    $offset = max(0, (int)$filter['offset']);
                    $sql .= " OFFSET {$offset}";
                }
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            new Msg(true, "Ein Fehler", $e);
            return [];
        }
    }
}