<?php

namespace NerdWerk\Http\ResponseRenderers;

class XmlRenderer implements \NerdWerk\Interfaces\ResponseRenderer
{

    public static function render($data) : string
    {
        if(is_array($data))
        {
            $xml = new \SimpleXMLElement("<".(defined("NW_RENDERERS_XML_ROOT_TAG") ? NW_RENDERERS_XML_ROOT_TAG : "response")."/>");
            array_walk_recursive(array_flip($data), array($xml, "addChild"));
            return $xml->asXML();
        } else {
            throw new \NerdWerk\Exceptions\ResponseRendererException("Data passed to XML ResponseRenderer must be an array", 0);
        }
    }

}