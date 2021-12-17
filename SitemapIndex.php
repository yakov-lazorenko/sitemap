<?php

namespace App\Services\Sitemap;

use Illuminate\Filesystem\Filesystem;


class SitemapIndex
{
    public $sitemapIndexFilePath;
    public $sitemapFiles;


    public function __construct($sitemapIndexFilePath, $sitemapFiles)
    {
        $this->sitemapIndexFilePath = $sitemapIndexFilePath;
        $this->sitemapFiles = $sitemapFiles;
    }


    public function create($lastmod = null)
    {
        $this->saveToFile($this->render($lastmod));
    }

    public function render($lastmod = null)
    {
        $xml_text = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml_text .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $lastmod = $lastmod ?? $this->getDefaultLastmod();
        foreach ($this->sitemapFiles as $file) {
            $xml_text .= '    <sitemap>' . "\n";
            $xml_text .= "        <loc>https://example.com/sitemap/$file</loc>" . "\n";
            $xml_text .= "        <lastmod>$lastmod</lastmod>" . "\n";
            $xml_text .= '    </sitemap>' . "\n";
        }
        $xml_text .= '</sitemapindex>' . "\n";
        return $xml_text;
    }


    public function saveToFile($content)
    {
        (new Filesystem)->put($this->sitemapIndexFilePath, $content);
    }


    public function getDefaultLastmod()
    {
        return date('Y-m-d');
    }
}
