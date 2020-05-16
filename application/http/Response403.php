<?php

namespace NerdWerkApp\Http;

class Response403 extends \NerdWerk\Http\SimpleResponse
{

    public function __construct()
    {
        parent::__construct(403, "Not Authorised");
        $this->sendResponse();
    }

}