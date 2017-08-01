<?php

namespace AcWikiParser;

class XPathLoader {

    protected static $domDocument=null;
    protected static $domXPath=null;

    public static function getDomDocument($filepath)
    {
        if(self::$domDocument == null && is_readable($filepath))
        {
            self::$domDocument = new \DOMDocument();
            self::$domDocument->loadHTMLFile($filepath);
        }

        return self::$domDocument;
    }

    public static function getDomXPath($filepath)
    {
        if(self::$domXPath == null)
        {
            $domDocument = self::getDomDocument($filepath);
            self::$domXPath = new \DOMXPath($domDocument);
        }

        return self::$domXPath;
    }
}