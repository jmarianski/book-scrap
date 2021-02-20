<?php

namespace BookScrap;

use Jackin\Controller\BaseConsoleController;

class ConsoleController extends BaseConsoleController
{
    public $params = [
        'url' => self::STRING,
        'depth' => self::INT,
    ];


    public static function getRoute(): string {
        return 'crawl';
    }

    public function run()
    {
        $LinkGetter = new LinkGetter();
        $Crawler = new Crawler($LinkGetter);
        $params = $this->getParams();
        $urls = $Crawler->startCrawling($params['url'], 'mobi', $params['depth'] ?? 3);
        $urlsString = join(PHP_EOL, $urls);
        file_put_contents('urls.txt', $urlsString);
        mkdir('download');
        foreach ($urls as $url) {
            $contents = file_get_contents($url);
            $urlParts = explode('/', $url);
            $fileName = end($urlParts);
            file_put_contents(__DIR__.'/download/'.$fileName, $contents);
        }
    }
}