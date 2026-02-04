<?php

namespace ppb\Controller;

use ppb\Model\SightingModel;

class SightingController {

    public function __construct() {}

    /**
     * GET /sighting?[family=...]&[orderBy=...] ...
     * (Gute Praxis: alle Query-Params whitelisten & an Model weitergeben)
     */
    public function getSighting(): void
    {
        $model = new SightingModel();
        $filter = [];

        $allowed = [
            'family','animalid','minAnimalCount','minSightingsCount',
            'fromDate','toDate','orderBy','orderDir','limit','offset','hasCoords'
        ];
        foreach ($allowed as $k) {
            if (isset($_GET[$k]) && $_GET[$k] !== '') {
                $filter[$k] = $_GET[$k];
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($model->selectSighting($filter), JSON_PRETTY_PRINT);
    }

    /**
     * POST /sighting
     * Body: JSON { animalid, date, ort, count, lat?, lng? }
     * Server setzt positive/negative/status => 0
     */
    public function writeSighting(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            // JSON einlesen
            $raw = file_get_contents('php://input');
            $payload = json_decode($raw, true);
            if (!is_array($payload)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'msg' => 'Invalid JSON']);
                return;
            }

            // Pflichtfelder minimal prüfen (detaillierte Prüfung macht insertSighting auch)
            if (empty($payload['animalid']) || empty($payload['date']) || !isset($payload['ort'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'msg' => 'animalid, date und ort sind erforderlich']);
                return;
            }

            // Egal was der Client sendet → serverseitig stets 0 setzen:
            $payload['positive'] = 0;
            $payload['negative'] = 0;
            $payload['status']   = 0;

            $model = new SightingModel();
            $newId = $model->insertSighting($payload);

            if ($newId === null) {
                http_response_code(500);
                echo json_encode(['success' => false, 'msg' => 'Insert fehlgeschlagen']);
                return;
            }

            echo json_encode([
                'success' => true,
                'id'      => $newId
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'msg' => 'Serverfehler', 'error' => $e->getMessage()]);
        }
    }
}