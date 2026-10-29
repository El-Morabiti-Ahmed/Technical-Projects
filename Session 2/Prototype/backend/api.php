<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . '/storage.php';

class ApiHandler {
    private array $storage;

    public function __construct() {
        $this->storage = [
            'platforms' => new StorageManager('platforms.json'),
            'games'     => new StorageManager('games.json')
        ];
    }

    public function handleRequest(): void {
        $entity = $_GET['entity'] ?? null;

        if (!$entity || !array_key_exists($entity, $this->storage)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid entity endpoint"]);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            echo json_encode($this->storage[$entity]->all());
        } elseif ($method === 'POST') {
            $rawInput = file_get_contents("php://input");
            $data = json_decode($rawInput, true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid JSON payload"]);
                return;
            }

            $created = $this->storage[$entity]->create($data);
            http_response_code(201);
            echo json_encode($created);
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
        }
    }
}

$api = new ApiHandler();
$api->handleRequest();