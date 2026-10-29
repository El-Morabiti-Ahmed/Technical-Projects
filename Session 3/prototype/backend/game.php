<?php

class Game {
    private int $id;
    private string $name;
    private string $platform;

    public function __construct(int $id, string $name, string $platform) {
        $this->id = $id;
        $this->name = $name;
        $this->platform = $platform;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPlatform(): string {
        return $this->platform;
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "platform" => $this->platform
        ];
    }
}
