<?php

require_once __DIR__ . '/game.php';

class GameManager {
    private string $JSONpath;

    public function __construct(string $JSONpath) {
        $this->JSONpath = $JSONpath;
    }

    private function read(): array {
        $content = file_get_contents($this->JSONpath);

        if ($content === false) {
            throw new RuntimeException("Could not read the games file.");
        }

        $data = json_decode($content, true);

        if (!is_array($data)) {
            throw new RuntimeException("The games file contains invalid JSON.");
        }

        return $data;
    }

    private function save(array $games): void {
        $result = file_put_contents(
            $this->JSONpath,
            json_encode($games, JSON_PRETTY_PRINT),
            LOCK_EX
        );

        if ($result === false) {
            throw new RuntimeException("Could not save the games file.");
        }
    }

    public function getAll(): array {
        $data = $this->read();
        $games = [];

        foreach ($data as $game) {
            $games[] = new Game(
                (int) $game['id'],
                (string) $game['name'],
                (string) $game['platform']
            );
        }

        return $games;
    }

    public function create(string $name, string $platform): Game {
        $name = trim($name);
        $platform = trim($platform);

        if ($name === '' || $platform === '') {
            throw new InvalidArgumentException("All fields are required.");
        }

        $games = $this->read();

        $id = empty($games)
            ? 1
            : max(array_column($games, 'id')) + 1;

        $game = new Game($id, $name, $platform);

        $games[] = $game->toArray();

        $this->save($games);

        return $game;
    }
}
