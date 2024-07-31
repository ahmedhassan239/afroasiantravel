<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleXMLElement;

class GenerateImageSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the image sitemap';

    public function handle()
    {
        // Main URL
        $mainUrl = 'https://www.ask-aladdin.com';

        // Create XML Document
        $xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"></urlset>');

        // Iterate through image files
        $directory = public_path('photos');
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $relativePath = str_replace(public_path(), '', $fileInfo->getPathname());
                $url = $xml->addChild('url');
                $url->addChild('loc', $mainUrl . $relativePath);
                $image = $url->addChild('image:image', '', 'http://www.google.com/schemas/sitemap-image/1.1');
                $image->addChild('image:loc', $mainUrl . $relativePath, 'http://www.google.com/schemas/sitemap-image/1.1');
            }
        }

        // Save the XML
        $xml->asXML(public_path('sitemap_images.xml'));
        $this->info('Sitemap generated successfully.');
    }
}
