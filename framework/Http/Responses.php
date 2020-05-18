<?php

namespace NerdWerk\Http;

class Responses
{

    public static function Json($response, $code = 200)
    {
        return new \NerdWerk\Http\JsonResponse($code, $response);
    }

    public static function Xml($response, $code = 200)
    {
        return new \NerdWerk\Http\XmlResponse($code, $response);
    }

    public static function Csv($response, $code = 200)
    {
        return new \NerdWerk\Http\CsvResponse($code, $response);
    }

}