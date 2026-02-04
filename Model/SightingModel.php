<?php

namespace ppb\Model;

use ppb\Library\Msg;
use ppb\Model\Database;

class SightingModel extends Database 
{
    public function __construct() {}

    /**
     * Fügt ein Sighting ein.
     * positive/negative/status werden SERVERSEITIG auf 0 gesetzt (unabhängig vom Client).
     * Gibt die neue sightingsid zurück.
     */
    public function insertSighting(array $data): ?int
    {
        try {
            $pdo = $this->linkDB();
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Pflichtfelder prüfen
            if (empty($data['animalid'])) {
                throw new \InvalidArgumentException('animalid ist erforderlich');
            }
            if (empty($data['date'])) {
                // Falls Client kein Datum sendet: heute
                $data['date'] = date('Y-m-d');
            }
            if (!isset($data['ort']) || $data['ort'] === '') {
                throw new \InvalidArgumentException('ort (Adresse/Ort) ist erforderlich');
            }

            // Zählwerte
            $count = isset($data['count']) ? (int)$data['count'] : 0;

            // Koordinaten optional
            $lat = isset($data['lat']) && $data['lat'] !== '' ? (float)$data['lat'] : null;
            $lng = isset($data['lng']) && $data['lng'] !== '' ? (float)$data['lng'] : null;

            // Serverseitig erzwingen:
            $positive = 0;
            $negative = 0;
            $status   = 0;

            $sql = "
                INSERT INTO sightings (animalid, date, ort, positive, negative, status, `count`, lat, lng)
                VALUES (:animalid, :date, :ort, :positive, :negative, :status, :count, :lat, :lng)
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':animalid' => (int)$data['animalid'],
                ':date'     => $data['date'],         // 'YYYY-MM-DD'
                ':ort'      => $data['ort'],
                ':positive' => $positive,             // erzwungen 0
                ':negative' => $negative,             // erzwungen 0
                ':status'   => $status,               // erzwungen 0
                ':count'    => $count,
                ':lat'      => $lat,
                ':lng'      => $lng,
            ]);

            return (int)$pdo->lastInsertId();
        } catch (\Throwable $e) {
            new Msg(true, "Fehler beim Einfügen eines Sightings", $e);
            return null;
        }
    }

    /**
     * Bestehende selectSighting() – ergänze lat/lng im SELECT
     */
    public function selectSighting(array $filter = []): array
    {
        try {
            $pdo = $this->linkDB();

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
                    s.lat,                 
                    s.lng,                 
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

            if (isset($filter['family']) && $filter['family'] !== '') {
                $where[] = "LOWER(a.family) = LOWER(:family)";
                $params[':family'] = $filter['family'];
            }
            if (isset($filter['animalid']) && $filter['animalid'] !== '') {
                $where[] = "s.animalid = :animalid";
                $params[':animalid'] = (int)$filter['animalid'];
            }
            if (isset($filter['minAnimalCount']) && $filter['minAnimalCount'] !== '') {
                $where[] = "a.animalcount >= :minAnimalCount";
                $params[':minAnimalCount'] = (int)$filter['minAnimalCount'];
            }
            if (isset($filter['minSightingsCount']) && $filter['minSightingsCount'] !== '') {
                $where[] = "a.sightingscount >= :minSightingsCount";
                $params[':minSightingsCount'] = (int)$filter['minSightingsCount'];
            }
            if (!empty($filter['fromDate'])) {
                $where[] = "s.date >= :fromDate";
                $params[':fromDate'] = $filter['fromDate'];
            }
            if (!empty($filter['toDate'])) {
                $where[] = "s.date <= :toDate";
                $params[':toDate'] = $filter['toDate'];
            }

            // Optional: nur Einträge mit Koordinaten
            if (isset($filter['hasCoords']) && (int)$filter['hasCoords'] === 1) {
                $where[] = "s.lat IS NOT NULL AND s.lng IS NOT NULL";
            }

            if (!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }

            $orderBy  = $filter['orderBy'] ?? 's.date';
            $orderDir = strtoupper($filter['orderDir'] ?? 'DESC');

            $allowedOrderBy = [
                's.date', 's.animalid', 'a.trivialname', 'a.family', 'a.sightingscount', 'a.animalcount',
                's.lat', 's.lng' // NEU erlaubt
            ];
            if (!in_array($orderBy, $allowedOrderBy, true)) {
                $orderBy = 's.date';
            }
            if (!in_array($orderDir, ['ASC', 'DESC'], true)) {
                $orderDir = 'DESC';
            }
            $sql .= " ORDER BY {$orderBy} {$orderDir}";

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