<?php

namespace NerdWerk\Http;

class CsvResponse extends Response
{

    public function __construct(int $code = 200, $response = [], ?string $mimeType = "text/csv")
    {
        if(is_array($response))
        {
            $csv = fopen("php://memory", "r+");
            foreach($response as $r)
            {
                fputcsv($csv, $r);
            }
            rewind($csv);
            parent::__construct($code, stream_get_contents($csv), $mimeType);
        } else {
            throw new \NerdWerk\Exceptions\HttpResponseException("Data passed to CsvResponse must be an array", 0);
        }
    }

}

