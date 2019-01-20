<?php
/**
 * MegaMerge plugin for Craft CMS 3.x
 *
 * Merge this that and the other...
 *
 * @link      http://marion.newlevant.com
 * @copyright Copyright (c) 2018 Marion Newlevant
 */

namespace marionnewlevant\megamerge\services;

use marionnewlevant\megamerge\MegaMerge;

use Craft;
use craft\base\Component;
use craft\web\View;

/**
 * @author    Marion Newlevant
 * @package   MegaMerge
 * @since     1.0.0
 */
class MegaMergeService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return string
     */
    public function parse(string $val, $context = [])
    {
        if (false === strpos($val, '{'))
        { // handle the case of no twig
            return $val;
        }
        // ok, parse it as twig
        // much of this code from preparseField

        // Enable generateTransformsBeforePageLoad always

        $parsedValue = '';

        $generateTransformsBeforePageLoad = Craft::$app->config->general->generateTransformsBeforePageLoad;
        Craft::$app->config->general->generateTransformsBeforePageLoad = true;

        // save cp template path and set to site templates
        $oldMode = Craft::$app->view->getTemplateMode();
        Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_SITE);

        // render value from the field template
        try {
            $parsedValue = Craft::$app->view->renderString($val, $context);
        } catch (\Exception $e) {
            Craft::error('Couldn’t render value for element with value “'.$val.'”  ('.$e->getMessage().').', __METHOD__);
        }

        // restore cp template paths
        Craft::$app->view->setTemplateMode($oldMode);

        // set generateTransformsBeforePageLoad back to whatever it was
        Craft::$app->config->general->generateTransformsBeforePageLoad = $generateTransformsBeforePageLoad;

        return $parsedValue;
    }
}
