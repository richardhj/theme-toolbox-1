<?php

/**
 * This file is part of erdmannfreunde/theme-toolbox.
 *
 * (c) 2018-2018 Erdmann & Freunde.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    erdmannfreunde/theme-toolbox
 * @author     Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright  2018-2018 Erdmann & Freunde.
 * @license    https://github.com/erdmannfreunde/theme-toolbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace ErdmannFreunde\ThemeToolboxBundle\Backend\Maintenance;

use Contao\Backend;
use Contao\Config;
use Contao\Controller;
use Contao\Environment;
use Contao\Input;

/**
 * Class Maintenance
 *
 * @package ErdmannFreunde\ThemeToolboxBundle\Backend\Maintenance
 */
class BypassScriptCache extends Backend implements \executable
{

    /**
     * Return true if the module is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return false;
    }

    /**
     * Generate the module
     *
     * @return string
     */
    public function run()
    {
        $formSubmit = 'tl_bypass_script_cache';

        $template = new \BackendTemplate('be_maintenance_script_cache');

        $template->formSubmit = $formSubmit;
        $template->action     = ampersand(Environment::get('request'));
        $template->headline   = $GLOBALS['TL_LANG']['tl_maintenance']['bypassScriptCacheMode'];
        $template->isActive   = $this->isActive();

        // Toggle the maintenance mode
        if ($formSubmit === Input::post('FORM_SUBMIT')) {
            Config::persist('bypassScriptCache', !Config::get('bypassScriptCache'));
            Controller::reload();
        }

        if (Config::get('bypassScriptCache')) {
            $template->class   = 'tl_error';
            $template->explain = $GLOBALS['TL_LANG']['MSC']['bypassScriptCacheEnabled'];
            $template->submit  = $GLOBALS['TL_LANG']['tl_maintenance']['bypassScriptCacheDisable'];
        } else {
            $template->class  = 'tl_info';
            $template->submit = $GLOBALS['TL_LANG']['tl_maintenance']['bypassScriptCacheEnable'];
        }

        return $template->parse();
    }
}
