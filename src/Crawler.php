<?php

namespace BookScrap;

class Crawler
{
    /**
     * @var LinkGetter 
     */
    protected $LinkGetter;

    function __construct(LinkGetter $LinkGetter)
    {
        $this->LinkGetter = $LinkGetter;
    }

    public function startCrawling(string $startUrl, string $extension, int $maxDepth = 2): array 
    {
        $urls = [$startUrl];
        $usedUrls = [];
        $extensionUrls = [];
        for ($i = 0; $i < $maxDepth; $i++) {
            [$newUrls, $newWithExtension] = $this->getLinks($urls, $extension);
            $usedUrls = array_merge($usedUrls, $urls);
            $urls = array_diff($newUrls, $usedUrls);
            $extensionUrls = array_merge($extensionUrls, $newWithExtension);
        }

        return $extensionUrls;
    }

    public function getLinks(array $links, string $extension): array
    {
        $newUrls = [];
        $newWithExtension = [];
        foreach ($links as $link) {
            $domain = substr($link, 0, strpos($link, '/', 8));
            $content = file_get_contents($link);
            $newUrls = array_merge($newUrls, $this->LinkGetter->getLinks($domain, $content));
            $newWithExtension = array_merge($newWithExtension, $this->LinkGetter->getLinks($domain, $content, $extension));
        }
        $newUrls = array_diff(array_unique($newUrls), array_unique($newWithExtension));

        return [$newUrls, $newWithExtension];
    }
}