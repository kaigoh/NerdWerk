<?php

namespace NerdWerk\Http\ResponseRenderers;

class CsvRenderer implements \NerdWerk\Interfaces\ResponseRenderer
{

    public static function render($data) : string
    {
        if(is_array($data))
        {
            $csv = fopen("php://memory", "r+");
            foreach($data as $r)
            {
                fputcsv($csv, $r);
            }
            rewind($csv);
            return stream_get_contents($csv);
        } else {
            throw new \NerdWerk\Exceptions\ResponseRendererException("Data passed to CSV ResponseRenderer must be an array", 0);
        }
    }

}

