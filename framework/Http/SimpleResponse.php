<?php

namespace NerdWerk\Http;

class SimpleResponse extends Response
{

    public function __construct(int $code = null, ?string $response = null)
    {
        if($code)
        {
            $this->setResponseCode($code);
        }
        if($response)
        {
            $this->setResponse($response);
        }
    }

}