<?php

namespace App\Modules\Telegram\DTOs\Response;

class DocumentDTO
{
    public function __construct(
        public string $file_name,
        public string $mime_type,
        public string $file_id,
        public string $file_unique_id,
        public int    $file_size,
    )
    {
    }

    public static function fromArray(array $document): static
    {
        return new static(
            file_name: $document['file_name'],
            mime_type: $document['mime_type'],
            file_id: $document['file_id'],
            file_unique_id: $document['file_unique_id'],
            file_size: $document['file_size'],
        );
    }
}
