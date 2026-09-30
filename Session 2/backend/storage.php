<?php

class StorageManager {
    private string $filePath;

    public function __construct(string $filename) {
        $dir = __DIR__ . '/../data';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->filePath = $dir . '/' . $filename;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    public function all(): array {
        $content = file_get_contents($this->filePath);
        return json_decode($content, true) ?? [];
    }

    public function create(array $data): array {
        $records = $this->all();

        $maxId = 0;
        foreach ($records as $record) {
            if (isset($record['id']) && $record['id'] > $maxId) {
                $maxId = $record['id'];
            }
        }

        $data['id'] = $maxId + 1;
        $records[] = $data;

        file_put_contents($this->filePath, json_encode($records, JSON_PRETTY_PRINT));
        return $data;
    }
}