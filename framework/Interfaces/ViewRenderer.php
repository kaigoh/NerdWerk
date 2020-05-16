<?php

namespace NerdWerk\Interfaces;

interface ViewRenderer
{

    public static function renderToString(string $file, ?array $data = null) : string;

    public static function renderToResponse(int $responseCode, string $file, ?array $data = null) : \NerdWerk\Http\Response;

}