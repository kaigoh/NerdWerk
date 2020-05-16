<?php

namespace NerdWerk;

class Template extends View
{

    public static function renderToString(string $file, ?array $data = null) : string
    {

        $path = NW_APPLICATION_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR;

        $loader = new \Twig\Loader\FilesystemLoader($path);

        $twig = new \Twig\Environment($loader, [
            'cache' => NW_CACHE_PATH.DIRECTORY_SEPARATOR."templates",
        ]);

        if(!file_exists($path.$file))
        {
            // Check if the .htm extension has been missed off...
            if(file_exists($path.$file.".htm"))
            {
                $file .= ".htm";
            } else {
                throw new \NerdWerk\Exceptions\TemplateException("Template file '".$path."' does not exist or is unreadable", 0);
            }
        }

        return $twig->render($file, (is_array($data) ? $data : []));

    }

    public static function renderToResponse(int $responseCode = 200, string $file, ?array $data = null) : \NerdWerk\Http\Response
    {
        return new \NerdWerk\Http\Response($responseCode, self::renderToString($file, $data));
    }

}