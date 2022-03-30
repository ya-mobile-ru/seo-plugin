<?php namespace Yamobile\Seo\Components;

use Cms\Classes\ComponentBase;

use Request;
use Yamobile\Seo\Models\Settings;
use Cms\Classes\Page;
use Cms\Components\ViewBag;

class Meta extends ComponentBase {

    public $settings;

    public function onRender() {
        $this->settings = Settings::instance();
        $thisPage = $this->page->page;

        if (!$this->page['viewBag']) $this->page['viewBag'] = new ViewBag;

        if($this->page->page->hasComponent('blogPost')) // blog post
        {
            $post = $this->page['post'];
            $this->page['viewBag']->setProperties(array_merge(
              $this->page["viewBag"]->getProperties(),
              $post->attributes,
              $post->metadata ?: []
            ));

        } else if (isset($this->page->apiBag['staticPage'])) { // static page
            $this->page['viewBag'] = $this->page->controller->vars['page']->viewBag;
        } else { // cms page
            $this->page['viewBag']->setProperties(array_merge($this->page['viewBag']->getProperties(), $this->page->settings));
        }

    }

    public function componentDetails() {
        return [
            'name' => 'META',
            'description' => 'yamobile.seo::lang.component.description'
        ];
    }
}
