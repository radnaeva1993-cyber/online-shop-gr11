<?php

namespace Model;

class Error extends Model
{
    protected function getTableName(): string
    {
        return 'errors';
    }

    public function create(string $message, string $file, int $line): bool
    {
        $stmt = $this->PDO->prepare(
            "INSERT INTO {$this->getTableName()} (message, file, line, data) 
                   VALUES (:message, :file, :line, :data)"
        );
        return $stmt->execute([
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'data' => date('Y-m-d H:i:s')
        ]);
    }
}
