<?php

namespace jaetooledev\redirector\controllers;

use jaetooledev\redirector\records\RedirectRecord;
use jaetooledev\redirector\Redirector;

use Craft;
use craft\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class DashboardController extends Controller
{

    public function actionIndex()
    {
        $redirects = Redirector::getInstance()->redirectService->getRedirectsForSite(
            Craft::$app->getSites()->currentSite->id
        );

        return $this->renderTemplate('redirector/index', [
            'redirects' => $redirects
        ]);
    }

    /**
     * @throws \yii\web\ForbiddenHttpException
     * @throws BadRequestHttpException
     */
    public function actionEdit(?int $redirectId = null, ?RedirectRecord $redirect = null): Response
    {
        $this->requirePermission('edit-redirects');
        if (!$redirect) {
            if ($redirectId) {
                $redirect = Redirector::getInstance()->redirectService->getRedirectById($redirectId);
                if (!$redirect) {
                    throw new BadRequestHttpException('Invalid Redirect ID: ' . $redirectId);
                }
            } else {
                $redirect = new RedirectRecord();
            }
        }

        $types = [
            '301' => '301',
            '302' => '302'
        ];

        return $this->renderTemplate('redirector/redirects/_edit', [
            'redirect' => $redirect,
            'types' => $types
        ]);
    }

    /**
     * @throws \yii\web\ForbiddenHttpException
     * @throws BadRequestHttpException
     */
    public function actionSave(): ?Response
    {
        $this->requirePermission('edit-redirects');
        $redirectId = $this->request->getBodyParam('redirectId');

        if ($redirectId) {
            $redirect = Redirector::getInstance()->redirectService->getRedirectById($redirectId);
            if (!$redirect) {
                throw new BadRequestHttpException('Invalid Redirect ID: ' . $redirectId);
            }
        } else {
            $redirect = new RedirectRecord();
        }

        $redirect->fromUrl = $this->request->getBodyParam('fromUrl');
        $redirect->toUrl = $this->request->getBodyParam('toUrl');
        $redirect->type = $this->request->getBodyParam('type');
        $redirect->siteId = Craft::$app->getSites()->currentSite->id;

        if (!Redirector::getInstance()->redirectService->save($redirect)) {
            if ($this->request->getAcceptsJson()) {
                return $this->asJson(
                    [
                        'errors' => $redirect->getErrors()
                    ]
                );
            }
            $this->setFailFlash(Craft::t('redirector', 'Error when saving Redirect'));

            Craft::$app->urlManager->setRouteParams(
                [
                    'redirect' => $redirect
                ]
            );
            return null;
        }

        if ($this->request->getAcceptsJson()) {
            return $this->asJson(
                [
                    'success' => true
                ]
            );
        }

        $this->setSuccessFlash(Craft::t('redirector', 'Redirect saved'));
        return $this->redirectToPostedUrl($redirect);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $redirectId)
    {
        if (!Redirector::getInstance()->redirectService->deleteRedirectById($redirectId)) { //TODO: Change this to allow better errors.
            if ($this->request->getAcceptsJson()) {
                return $this->asJson(
                    [
                        'errors' => [
                            Craft::t('redirector', 'Unable to delete Redirect')
                        ]
                    ]
                );
            }
            $this->setFailFlash(Craft::t('redirector', 'Error when deleting Redirect'));
            return null;
        }

        if ($this->request->getAcceptsJson()) {
            return $this->asJson(
                [
                    'success' => true
                ]
            );
        }

        $this->setSuccessFlash(Craft::t('redirector', 'Redirect deleted'));
        return $this->goBack('actions/redirector/dashboard/index');
    }
}
