<?php

namespace App\Modules\Telegram\DTOs\Request;

use Illuminate\Http\UploadedFile;

class SetWebhookDTO extends BaseDTO
{
    public function __construct(
        public string        $url,
        public ?UploadedFile $certificate = null,
        public ?string       $ip_address = null,
        public int           $max_connections = 40,
        public ?array        $allowed_updates = null,
        public bool          $drop_pending_updates = false,
        public ?string       $secret_token = null
    )
    {
        $this->container = get_defined_vars();
    }
}
