<?php

namespace App\Utils;

trait Json {
    public function result($status_code, $payload)
    {
        return [ 'payload' => $payload, 'status_code' => $status_code ];
    }
}