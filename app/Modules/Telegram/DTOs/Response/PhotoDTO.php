<?php

namespace App\Modules\Telegram\DTOs\Response;

use Illuminate\Support\Collection;

class PhotoDTO
{
    public function __construct(
        public string $file_id,
        public string $file_unique_id,
        public int    $file_size,
        public int    $width,
        public int    $height,
    )
    {
    }

    public static function fromArray(array $photos): Collection
    {
        return collect($photos)
            ->map(
                fn(array $photo) => new PhotoDTO(
                    file_id: $photo['file_id'],
                    file_unique_id: $photo['file_unique_id'],
                    file_size: $photo['file_size'],
                    width: $photo['width'],
                    height: $photo['height'],
                )
            );
    }
}
