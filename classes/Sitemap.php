<?php namespace Yamobile\Seo\Classes;

use System\Classes\PluginManager;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use Carbon\Carbon;

class Sitemap {

    private $xml;
    private $urlSet;
    const MAX_URLS = 50000;
    protected $urlCount = 0;

    function generate() {

        $pages = Page::listInTheme(Theme::getEditTheme());
        $models = [];

        foreach($pages as $page) {
            if (!$page->use_in_sitemap) continue;

            $modelClass = str_replace(' ', '', $page['model_class']);

            if($modelClass) {
                foreach(explode(",", $modelClass) as $itemClass) {
                    if(class_exists($itemClass)) {
                        $models = $itemClass::all();
                        foreach ($models as $model) {
                            $this->addItemToSet(Item::asCmsPage($page, $model));
                        }
                    }
                }
            } else {
                if ($page->hasComponent('blogPost')) {
                    $itemClass = "Rainlab\Blog\Models\Post";
                    $models = $itemClass::all();
                    foreach ($models as $model) {
                        if (!(integer)$model['published']) continue;
                        if (!(integer)$model->metadata['use_in_sitemap']) continue;
                        $this->addItemToSet(Item::asPost($page, $model));
                    }
                }
                else {
                    $this->addItemToSet(Item::asCmsPage($page));
                }
            }

        }

        if (PluginManager::instance()->hasPlugin('RainLab.Pages')) {
            $staticPages = \RainLab\Pages\Classes\Page::listInTheme(Theme::getActiveTheme());
            foreach ($staticPages as $staticPage) {
                if (!$staticPage->getViewBag()->property('use_in_sitemap')) continue;
                $this->addItemToSet(Item::asStaticPage($staticPage));
            }
        }

        return $this->make();
    }

    protected function makeRoot() {
        if ($this->xml !== null) return $this->xml;

        $xml = new \DOMDocument;
        $xml->encoding = 'UTF-8';

        return $this->xml = $xml;
    }

    protected function makeUrlSet() {
        if ($this->urlSet !== null) return $this->urlSet;

        $xml = $this->makeRoot();
        $urlSet = $xml->createElement('urlset');
        $urlSet->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlSet->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlSet->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
        $xml->appendChild($urlSet);

        return $this->urlSet = $urlSet;
    }

    protected function addItemToSet(Item $item, $url = null, $mtime = null) {
        $xml = $this->makeRoot();
        $urlSet = $this->makeUrlSet();
        $this_url = url($item->loc);
		if($item->loc == '/')$this_url = url($item->loc).'/';

        $urlElement = $this->makeUrlElement(
            $xml,
            $this_url, // make sure output is a valid url
            $item->lastModified,
            $item->priority,
            $item->changefreq
        );

        if ($urlElement) {
            $urlSet->appendChild($urlElement);
        }

        return $urlSet;
    }

    protected function makeUrlElement($xml, $pageUrl, $lastModified, $priority, $changefreq) {

        if ($this->urlCount >= self::MAX_URLS) {
            return false;
        }

        $this->urlCount++;

        $url = $xml->createElement('url');
        $pageUrl && $url->appendChild($xml->createElement('loc', $pageUrl));
        $lastModified && $url->appendChild($xml->createElement('lastmod', $lastModified));
        $changefreq && $url->appendChild($xml->createElement('changefreq', $changefreq));
        $priority && $url->appendChild($xml->createElement('priority', $priority));

        return $url;
    }

    protected function make() {
        $this->makeUrlSet();
        return $this->xml->saveXML();
    }
}

class Item {
    public $loc, $lastModified, $priority, $changefreq;

    function __construct($url=null, $lastModified=null, $priority=null, $changefreq=null) {
        $this->loc = Self::replaceUrl($url);
        $this->lastModified = $lastModified;
        $this->priority = $priority;
        $this->changefreq = $changefreq;
    }

    public static function replaceUrl($url, $model = null) {
        if (! is_string($url)) throw new \ApplicationException("Parameter \$url must be a string");
        $params = [];
        $reg = '/:(\w+)/';
        if(!$model) $reg = '/:(\w+)[?]/';
        preg_match_all($reg, $url, $params, PREG_SET_ORDER);
        $extract =  array_pluck($params, '1', '0'); // ex: [':slug' => 'slug' ]

        $replacedUrl = $url;

        foreach ($extract as $param => $prop) {
            if($model) {
                $replacedUrl = str_replace_first($param, $model->$prop, $replacedUrl);
            } else {
                $replacedUrl = str_replace($param, '', $replacedUrl);
            }
        }

        return $replacedUrl;
    }

    public static function asCmsPage($page, $model = null) {
        if ($model) {
            return new Self(
                url(Self::replaceUrl($page->url, $model)),
                $model->updated_at ? $model->updated_at->format('c') : \Carbon\Carbon::createFromTimestamp($page->mtime)->format('c'),
                $page->priority,
                $page->changefreq
            );
        }
        return new Self(
            $page->url,
            \Carbon\Carbon::createFromTimestamp($page->mtime)->format('c'),
            $page->priority,
            $page->changefreq
        );
    }

    public static function asStaticPage($staticPage) {
        return new Self(
            url($staticPage->url),
            \Carbon\Carbon::createFromTimestamp($staticPage->mtime)->format('c'),
            $staticPage->priority,
            $staticPage->changefreq
        );
    }

    public static function asPost($page, $post) {
        return new Self(
            url(Self::replaceUrl($page->url, $post)),
            $post->updated_at->format('c'),
            $page->priority,
            $page->changefreq
        );
    }
}