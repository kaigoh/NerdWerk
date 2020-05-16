<?php

namespace NerdWerk\Interfaces;

interface HttpResponse
{

    public function addHeader(string $key = null, ?string $value = null);

    public function getHeaders();

    public function setResponseCode(int $code);

    public function getResponseCode();

    public function setResponse($response);

    public function getResponse();

    public function sendResponse();

    public function __tostring();

}