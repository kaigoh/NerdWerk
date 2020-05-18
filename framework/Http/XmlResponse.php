<?php

namespace NerdWerk\Http;

class XmlResponse extends Response
{

    public function __construct(int $code = 200, $response = [], ?string $mimeType = "application/xml")
    {
        if(is_array($response))
        {
            $xml = new \SimpleXMLElement("<".(defined("NW_RENDERERS_XML_ROOT_TAG") ? NW_RENDERERS_XML_ROOT_TAG : "response")."/>");
            array_walk_recursive(array_flip($response), array($xml, "addChild"));
            parent::__construct($code, $xml->asXML(), $mimeType);
        } else {
            throw new \NerdWerk\Exceptions\HttpResponseException("Data passed to XmlResponse must be an array", 0);
        }
    }

}