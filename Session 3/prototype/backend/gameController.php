<?php

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/gameManager.php';

class GameController {
    private GameManager $manager;

    public function __construct() {
        $this->manager = new GameManager(
            __DIR__ . '/data/games.json'
        );
    }

    public function handle(): void {
        try {
            $method = $_SERVER['REQUEST_METHOD'];

            if ($method === 'GET') {
                $games = $this->manager->getAll();

                echo json_encode(
                    array_map(
                        fn($game) => $game->toArray(),
                        $games
                    )
                );

                return;
            }

            if ($method === 'POST') {
                $input = json_decode(
                    file_get_contents("php://input"),
                    true
                );

                if (!is_array($input)) {
                    throw new InvalidArgumentException("Invalid request data.");
                }

                $game = $this->manager->create(
                    $input['name'] ?? '',
                    $input['platform'] ?? ''
                );

                http_response_code(201);

                echo json_encode([
                    "message" => "Game created successfully.",
                    "game" => $game->toArray()
                ]);

                return;
            }

            http_response_code(405);

            echo json_encode([
                "error" => "Method not allowed."
            ]);

        } catch (InvalidArgumentException $error) {
            http_response_code(422);

            echo json_encode([
                "error" => $error->getMessage()
            ]);

        } catch (Throwable $error) {
            http_response_code(500);

            echo json_encode([
                "error" => "Internal server error."
            ]);
        }
    }
}

$controller = new GameController();
$controller->handle();
