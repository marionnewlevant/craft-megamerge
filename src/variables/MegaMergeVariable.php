<?php
/**
 * MegaMerge plugin for Craft CMS 3.x
 *
 * Merge this that and the other...
 *
 * @link      http://marion.newlevant.com
 * @copyright Copyright (c) 2018 Marion Newlevant
 */

namespace marionnewlevant\megamerge\variables;

use marionnewlevant\megamerge\MegaMerge;

use Craft;

/**
 * @author    Marion Newlevant
 * @package   MegaMerge
 * @since     1.0.0
 */
class MegaMergeVariable
{
    private $keyVals = [];
    private $parsedVals = [];
    private $context = [];

    // Public Methods
    // =========================================================================

    /**
     * @param null $optional
     * @return null
     */
    public function init($keyVals = [], $context = [])
    {
        $this->parsedVals = [];

        foreach ($keyVals as $keyValCollection)
        {
            if ($keyValCollection) {
                // figure out if it is twig assoc array
                // or megamerge table
                if (array_key_exists(0, $keyValCollection))
                {
                    // this one is megamerge table
                    $simpleKeyVal = [];
                    foreach ($keyValCollection as $pair)
                    {
                        $simpleKeyVal[$pair['key']] = $pair['value'];
                    }
                    $this->keyVals = array_merge($this->keyVals, $simpleKeyVal);
                }
                else
                {
                    $this->keyVals = array_merge($this->keyVals, $keyValCollection);
                }
            }
        }
        $this->context = array_merge($this->context, $context);
        return null;
    }

    public function reset()
    {
        $this->keyVals = [];
        $this->context = [];
        $this->parsedVals = [];
    }

    public function value($key)
    {
        if (array_key_exists($key, $this->parsedVals))
        {
            return $this->parsedVals[$key];
        }
        if (array_key_exists($key, $this->keyVals))
        {
            $parsed = MegaMerge::$plugin->megaMergeService->parse($this->keyVals[$key], $this->context);
            $this->parsedVals[$key] = $parsed;
            return $parsed;
        }
        return null;
    }
}
