<?php

namespace NerdWerk\Http;

class Response implements ResponseInterface
{

    private $httpVersion = "HTTP/1.1";

    private $httpResponseCodes = [
        100 => "Continue",
        101 => "Switching Protocols",
        102 => "Processing",
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-Status",
        208 => "Already Reported",
        226 => "IM Used",
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Timeout",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Payload Too Large",
        414 => "Request-URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Requested Range Not Satisfiable",
        417 => "Expectation Failed",
        418 => "I'm a teapot",
        421 => "Misdirected Request",
        422 => "Unprocessable Entity",
        423 => "Locked",
        424 => "Failed Dependency",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        444 => "Connection Closed Without Response",
        451 => "Unavailable For Legal Reasons",
        499 => "Client Closed Request",
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Timeout",
        505 => "HTTP Version Not Supported",
        506 => "Variant Also Negotiates",
        507 => "Insufficient Storage",
        508 => "Loop Detected",
        510 => "Not Extended",
        511 => "Network Authentication Required",
        599 => "Network Connect Timeout Error",
    ];

    private $headers = [];

    private $code = 200;

    private $response = null;

    private $mimeType = false;

    public function __construct(int $code = null, $response = null, ?string $mimeType = null)
    {
        if($code)
        {
            $this->setResponseCode($code);
        }
        if($response)
        {
            $this->setResponse($response);
        }
        if($mimeType)
        {
            $this->setMimeType($mimeType);
        }
    }

    public function addHeader(string $key = null, ?string $value = null)
    {
        if($value)
        {
            $this->headers[$key] = $value;
        } else {
            // Try and split the header into a key / value pair...
            $kv = explode(":", $key, 2);
            $this->headers[$kv[0]] = (isset($kv[1]) ? trim($kv[1]) : null);
        }
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setResponseCode(int $code)
    {
        $this->code = $code;
    }

    public function getResponseCode()
    {
        return $this->code;
    }

    public function setMimeType(string $mime)
    {
        $this->mimeType = $mime;
        $this->addHeader("content-type", $this->mimeType);
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    private function renderResponse()
    {
        switch(strtolower($this->mimeType))
        {
            case "text/json":
            case "application/json":
                return json_encode($this->response);
            break;

            case "text/xml":
            case "application/xml":
                $xml = new \SimpleXMLElement("<response/>");
                array_walk_recursive(array_flip($this->response), array($xml, "addChild"));
                return $xml->asXML();
            break;

            case "text/csv":
                $csv = fopen("php://memory", "r+");
                foreach($this->response as $r)
                {
                    fputcsv($csv, $r);
                }
                rewind($csv);
                return stream_get_contents($csv);
            break;
        }
        if(is_string($this->response))
        {
            return $this->response;
        } else {
            throw new \NerdWerk\Exceptions\NerdWerkHttpResponseException("Response passed is not a string type, and no MIME-type has been specified", 600);
        }
    }

    public function getResponse()
    {
        return $this->renderResponse();
    }

    public function __tostring()
    {
        $output = [];
        if(isset($this->code))
        {
            $output[] = $this->httpVersion." ".$this->code." ".$this->httpResponseCodes[$this->code];
        }
        foreach($this->headers as $key => $value)
        {
            $output[] = $key.": ".$value;
        }
        return implode("\r\n", array_merge($output, [null, $this->renderResponse()]));
    }

    public function sendResponse()
    {
        // Send response code
        if(isset($this->code))
        {
            http_response_code($this->code);
        }

        // Send headers
        foreach($this->headers as $key => $value)
        {
            header($key.": ".$value);
        }

        // Send body
        die($this->renderResponse());
    }

}