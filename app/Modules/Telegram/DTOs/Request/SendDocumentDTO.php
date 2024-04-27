<?php

namespace App\Modules\Telegram\DTOs\Request;

use Illuminate\Http\UploadedFile;

class SendDocumentDTO extends BaseFileDTO
{
    public function __construct(
        public int                      $chat_id,
        public UploadedFile|string      $document,
        public UploadedFile|string|null $thumbnail = null,
        public ?string                  $caption = null,
        public string                   $parse_mode = 'html',
        public ?string                  $reply_markup = null,
        public array                    $reply_parameters = [],
    )
    {
        $this->container = get_defined_vars();
        $this->file = $this->document;
    }
}
