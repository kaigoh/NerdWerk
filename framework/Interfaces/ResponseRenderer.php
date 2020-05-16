<?php

namespace NerdWerk\Interfaces;

interface ResponseRenderer
{

    public static function render($data) : string;

}