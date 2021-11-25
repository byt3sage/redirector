<?php

namespace jaetooledev\redirector;

use jaetooledev\redirector\services\RedirectService;
use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use yii\base\Event;

class Redirector extends Plugin
{

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Redirector::$plugin
     *
     * @var Redirector
     */
    public static $plugin;

    public $schemaVersion = '1.0.0';

    public $hasCpSettings = false;

    public $hasCpSection = true;

    public function init()
    {
        parent::init();
        $this->setComponents(
            [
                'redirectService' => RedirectService::class
            ]
        );
        self::$plugin = $this;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $redirects = self::getInstance()->redirectService->getRedirectsForSite(
                    Craft::$app->getSites()->currentSite->id
                );
                $redirectRules = [];
                foreach ($redirects as $redirect) {
                    $redirectRules[$redirect['fromUrl']] = [
                        'route' => 'redirector/redirect/index',
                        'params' => [
                            'redirectId' => $redirect['id']
                        ]
                    ];
                }

                $event->rules += $redirectRules;
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['redirector'] = 'redirector/dashboard/index';
                $event->rules['redirector/redirect/new'] = 'redirector/dashboard/edit';
                $event->rules['redirector/redirect/<redirectId:\d+>'] = 'redirector/dashboard/edit';
                $event->rules['redirector/redirect/<redirectId:\d+>/delete'] = 'redirector/dashboard/delete';
            }
        );

        Craft::info(
            Craft::t(
                'redirector',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    public function getCpNavItem(): ?array
    {
        $item = parent::getCpNavItem();
        $item['url'] = 'redirector';
        $item['icon'] = '@jaetooledev/redirector/redirector.svg';
        return $item;
    }
}
