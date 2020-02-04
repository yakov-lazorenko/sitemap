<?php

namespace App\Services\Sitemap;

use Illuminate\Filesystem\Filesystem;


class SitemapFile
{
    public $sitemapFilePath;

    public $createGzFile;


    public function __construct(
        $sitemapFilePath,
        $createGzFile = true
    ) {
        $this->sitemapFilePath = $sitemapFilePath;
        $this->createGzFile = $createGzFile;
    }


    public function create($locations)
    {
        $this->prepareSitemapFileDir();
        if ($this->createGzFile) {
            $this->saveToGzFile(
                $this->render($locations)
            );
        } else {
            $this->saveToFile(
                $this->render($locations)
            );            
        }
    }


    public function render($locations)
    {
        $xml_text = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml_text .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($locations as $location) {
            $xml_text .= $this->renderUrl(
                $location['url'],
                $location['lastmod'],
                $location['changefreq'],
                $location['priority']
            );
        }
        $xml_text .= '</urlset>';
        return $xml_text;
    }


    public function renderUrl($url, $lastmod, $changefreq, $priority)
    {
        $xml_text =  '    <url>' . "\n";
        $xml_text .= '        <loc>' . $url . '</loc>' . "\n";
        $xml_text .= '        <lastmod>' . $lastmod . '</lastmod>' . "\n";
        $xml_text .= '        <changefreq>' . $changefreq . '</changefreq>' . "\n";
        $xml_text .= '        <priority>' . $priority . '</priority>' . "\n";
        $xml_text .= '    </url>' . "\n";
        return $xml_text;
    }


    public function saveToGzFile($content)
    {
        $f = gzopen($this->sitemapFilePath, 'wb');
        gzwrite($f, $content);
        gzclose($f);
    }


    public function saveToFile($content)
    {
        (new Filesystem)->put($this->sitemapFilePath, $content);
    }


    public function prepareSitemapFileDir()
    {
        $dirPath = dirname($this->sitemapFilePath);
        if (!file_exists($dirPath)){
            mkdir($dirPath, 0777);
        }
    }
}
