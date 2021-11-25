<?php
/**
 * Redirector plugin for Craft CMS 3.x
 *
 * A simple way to add 301/302 redirects within CraftCMS
 *
 * @link      https://github.com/jaetooledev
 * @copyright Copyright (c) 2021 Jae Toole
 */

namespace jaetooledev\redirector\services;

use craft\helpers\Db;
use jaetooledev\redirector\records\RedirectRecord;
use jaetooledev\redirector\Redirector;

use Craft;
use craft\base\Component;

/**
 * RedirectService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Jae Toole
 * @package   Redirector
 * @since     1.0.0
 */
class RedirectService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Redirector::$plugin->redirectorService->exampleService()
     *
     * @return mixed
     */

    /**
     * Fetch all redirects for a given site.
     * @param int $siteId
     * @return array
     */
    public function getRedirectsForSite(int $siteId): array
    {
        return RedirectRecord::find()->where(['siteId' => $siteId])->all();
    }

    public function getRedirectById(int $redirectId): ?RedirectRecord
    {
        return RedirectRecord::findOne($redirectId);
    }

    /**
     * @throws \yii\db\StaleObjectException
     * @throws \Throwable
     */
    public function deleteRedirectById(int $redirectId): bool
    {
        return RedirectRecord::findOne($redirectId)->delete();
    }

    /**
     * Add a new redirect.
     * @param RedirectRecord $redirect
     * @return bool
     */
    public function save(RedirectRecord $redirect): bool
    {
       return $redirect->save();
    }
}
