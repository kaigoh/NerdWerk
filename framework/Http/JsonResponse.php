<?php

namespace NerdWerk\Http;

class JsonResponse extends Response
{

    public function __construct(int $code = 200, $response = null, ?string $mimeType = "application/json")
    {
        parent::__construct($code, json_encode($response), $mimeType);
    }

}