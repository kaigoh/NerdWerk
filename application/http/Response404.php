<?php

namespace NerdWerkApp\Http;

class Response404 extends \NerdWerk\Http\SimpleResponse
{

    public function __construct()
    {
        parent::__construct(404, "Not Found");
    }

}