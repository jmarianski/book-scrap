<?php

declare (strict_types = 1);

namespace BookScrap;

use DOMDocument;
use SimpleXMLElement;

class LinkGetter 
{
    public function getLinks(string $domain, string $source, string $extension = null) : array
    {
        $SimpleXmlElement = $this->getSimpleXMLElement($source);
        if (is_null($SimpleXmlElement)) {
            return [];
        }
        $anchors = $SimpleXmlElement->xpath('//a');
        if ($anchors === false) {
            return [];
        }
        $links = [];
        foreach ($anchors as $Anchor) {
            $attributes = $Anchor->attributes();
            $url = trim((string) $attributes->href);
            if (strpos($url, '/') === 0) {
                $url = $domain.$url;
            }
            if (strpos($url, $domain) === false) {
                continue;
            }
            if ($this->isLinkValid($url, $extension)) {
                if ($extension !== null) {
                    echo $url.PHP_EOL;
                }
                $links[] = (string) $url;
            }
        }

        return $links;
    }

    protected function isLinkValid(string $url, string $extension = null): bool
    {
        return (is_null($extension) || preg_match(sprintf('/.%s$/', $extension), $url))
            && filter_var($url, FILTER_VALIDATE_URL);

    }

    protected function getSimpleXMLElement(string $source): ?SimpleXMLElement
    {
        libxml_use_internal_errors(true);
        $DomDocument = new DOMDocument();
        $DomDocument->loadHTML($source);

        $SimpleXMLElement = simplexml_import_dom($DomDocument);
        libxml_use_internal_errors(false);
        if ($SimpleXMLElement !== false) {
            return $SimpleXMLElement;
        }

        return null;
    }
}