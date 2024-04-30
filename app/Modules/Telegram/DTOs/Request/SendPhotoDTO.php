<?php

namespace App\Modules\Telegram\DTOs\Request;

use Illuminate\Http\UploadedFile;

class SendPhotoDTO extends BaseFileDTO
{
    public function __construct(
        public int                 $chat_id,
        public UploadedFile|string $photo,
        public ?string             $caption = null,
        public ?string             $parse_mode = 'html',
        public ?string             $caption_entities = null,
        public ?string             $reply_markup = null,
        public array               $reply_parameters = [],
    )
    {
        $this->container = get_defined_vars();
        $this->file = $this->photo;
    }
}
