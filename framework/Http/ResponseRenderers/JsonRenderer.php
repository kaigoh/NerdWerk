<?php

namespace NerdWerk\Http\ResponseRenderers;

class JsonRenderer implements \NerdWerk\Interfaces\ResponseRenderer
{

    public static function render($data) : string
    {
        return json_encode($data);
    }

}