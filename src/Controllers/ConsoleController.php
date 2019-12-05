<?php
/**
 * 2019-present Friends of Presta community
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @author Friends of Presta community
 * @copyright 2019-present Friends of Presta community
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace FOP\Console\Controllers;

use Controller;

/**
 * Controller used in Console environment.
 */
class ConsoleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->id = 0;
        $this->controller_type = 'console';
    }

    /**
     * {@inheritdoc}
     */
    public function checkAccess()
    {
        // TODO: Implement checkAccess() method.
    }

    /**
     * {@inheritdoc}
     */
    public function viewAccess()
    {
        // TODO: Implement viewAccess() method.
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess()
    {
        // TODO: Implement postProcess() method.
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function setMedia()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function initHeader()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function initContent()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function initCursedPage()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function initFooter()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    protected function redirect()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildContainer()
    {
        // @todo: Should we return the back office container here ?
        return null;
    }
}
