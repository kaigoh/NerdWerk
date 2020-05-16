<?php

namespace NerdWerk;

class View implements \NerdWerk\Interfaces\ViewRenderer
{

    public static function renderToString(string $file, ?array $data = null) : string
    {
        $path = NW_APPLICATION_PATH.DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.$file;

        if(!file_exists($path))
        {
            // Check if the .php extension has been missed off...
            if(file_exists($path.".php"))
            {
                $path .= ".php";
            } else {
                throw new \NerdWerk\Exceptions\ViewException("View file '".$path."' does not exist or is unreadable", 0);
            }
        }

        ob_start();
        if($data)
        {
            extract($data, EXTR_SKIP);
        }
        include($path);
        return ob_get_clean();
    }

    public static function renderToResponse(int $responseCode = 200, string $file, ?array $data = null) : \NerdWerk\Http\Response
    {
        return new \NerdWerk\Http\Response($responseCode, self::renderToString($file, $data));
    }

}