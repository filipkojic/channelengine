<?php

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use ChannelEngine\PrestaShop\Classes\Business\Services\LoginService;
use ChannelEngine\PrestaShop\Classes\Business\Services\SyncService;
use ChannelEngine\PrestaShop\Classes\Utility\ServiceRegistry;

/**
 * AdminChannelEngineController class
 *
 * This class handles the display and management of the Channel Engine module's
 * admin interface in PrestaShop. It uses a dynamic method invocation approach
 * to handle different actions based on the 'action' parameter in the URL.
 */
class AdminChannelEngineController extends ModuleAdminController
{
    /**
     * Constructor
     *
     * Initializes the controller with Bootstrap styling.
     */
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    /**
     * Init Content
     *
     * This method initializes the content of the page based on the 'action'
     * parameter from the request. If the specified action exists as a method,
     * it is called; otherwise, the defaultAction method is called.
     */
    public function initContent()
    {
        $action = Tools::getValue('action', 'defaultAction'); // Default action is 'defaultAction'

        if (method_exists($this, $action)) {
            $this->$action(); // Call the method corresponding to the action
        } else {
            $this->defaultAction(); // Call the default action if the method does not exist
        }
    }

    /**
     * Default Action
     *
     * This method is the default action, which displays the welcome page of the
     * Channel Engine module. It loads the necessary CSS and JS files and assigns
     * the module directory path to the Smarty template.
     */
    protected function defaultAction()
    {
        // Ako su parametri postavljeni, odmah pozivamo stranicu za sinhronizaciju
        if (ServiceRegistry::getInstance()->get(LoginServiceInterface::class)->isUserLoggedIn()) {
            $this->displaySync();
            return;
        }

        // Add CSS and JS for the welcome page
        $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/welcome.css');
        $this->context->controller->addJS($this->module->getPathUri() . 'views/js/welcome.js');

        // Assign module directory path to Smarty
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        // Fetch the welcome page template and assign it to the content
        $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/configure.tpl');
        $this->context->smarty->assign('content', $output);
    }

    /**
     * Display Login
     *
     * This method displays the login page for the Channel Engine module.
     * It assigns the module directory path to the Smarty template and
     * fetches the login template.
     */
    protected function displayLogin()
    {
        // Assign module directory path to Smarty
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        // Fetch the login page template and assign it to the content
        $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/login.tpl');
        $this->context->smarty->assign('content', $output);
    }

    protected function displaySync()
    {
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        $output = $this->context->smarty->fetch($this->module->getLocalPath() . 'views/templates/admin/sync.tpl');
        $this->context->smarty->assign('content', $output);
    }

    public function handleLogin()
    {
        $apiKey = Tools::getValue('api_key');
        $accountName = Tools::getValue('account_name');

        try {
            if (ServiceRegistry::getInstance()->get(LoginServiceInterface::class)->handleLogin($apiKey, $accountName)) {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminChannelEngine') . '&action=displaySync');
            } else {
                $this->context->smarty->assign([
                    'error' => 'Login failed. Please check your credentials.'
                ]);
                $this->displayLogin();
            }
        } catch (Exception $e) {
            $this->context->smarty->assign([
                'error' => 'Login failed.'
            ]);
            $this->displayLogin();
        }
    }

}
