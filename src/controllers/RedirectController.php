<?php

namespace jaetooledev\redirector\controllers;

use craft\helpers\UrlHelper;
use jaetooledev\redirector\Redirector;
use Craft;
use craft\web\Controller;

class RedirectController extends Controller
{

    public function actionIndex()
    {
        $redirect = Redirector::getInstance()->redirectService->getRedirectById(
            Craft::$app->getUrlManager()->getRouteParams()['redirectId']
        );

        if (strpos($redirect->toUrl, '://') === false) {
            $redirect->toUrl = UrlHelper::baseUrl() . ltrim($redirect->toUrl, '/');
        }

        return $this->redirect($redirect->toUrl, $redirect->type);
    }
}
