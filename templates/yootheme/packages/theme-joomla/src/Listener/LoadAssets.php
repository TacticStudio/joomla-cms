<?php

namespace YOOtheme\Theme\Joomla\Listener;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\HtmlDocument;
use YOOtheme\Application;
use YOOtheme\Joomla\Platform;

class LoadAssets
{
    public Application $app;
    public CMSApplication $joomla;

    public function __construct(Application $app, CMSApplication $joomla)
    {
        $this->app = $app;
        $this->joomla = $joomla;
    }

    /**
     * Make assets cacheable (e.g. maps.min.js).
     */
    public function handle(): void
    {
        if ($this->joomla->getDocument() instanceof HtmlDocument && $this->joomla->get('caching')) {
            $this->app->call([Platform::class, 'registerAssets']);
        }
    }
}
