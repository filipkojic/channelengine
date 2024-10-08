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
     *
     * @return void
     */
    public function initContent(): void
    {
        $action = Tools::getValue('action', 'defaultAction');

        if (!method_exists($this, $action)) {
            $this->defaultAction();

            return;
        }

        $this->$action();
    }

    /**
     * Default Action
     *
     * This method is the default action, which displays the welcome page of the
     * Channel Engine module. It loads the necessary CSS and JS files and assigns
     * the module directory path to the Smarty template.
     *
     * @return void
     */
    protected function defaultAction(): void
    {
        if (ServiceRegistry::getInstance()->get(LoginServiceInterface::class)->isUserLoggedIn()) {
            $this->displaySync();

            return;
        }

        $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/welcome.css');
        $this->context->controller->addJS($this->module->getPathUri() . 'views/js/welcome.js');

        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        $output = $this->context->smarty
            ->fetch($this->module->getLocalPath() . 'views/templates/admin/configure.tpl');

        $this->context->smarty->assign('content', $output);
    }

    /**
     * Display Login
     *
     * This method displays the login page for the Channel Engine module.
     * It assigns the module directory path to the Smarty template and
     * fetches the login template.
     *
     * @return void
     */
    protected function displayLogin(): void
    {
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        $output = $this->context->smarty
            ->fetch($this->module->getLocalPath() . 'views/templates/admin/login.tpl');

        $this->context->smarty->assign('content', $output);
    }

    /**
     * Display Sync
     *
     * Displays the sync page for the Channel Engine module.
     *
     * @return void
     */
    protected function displaySync(): void
    {
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        $output = $this->context->smarty
            ->fetch($this->module->getLocalPath() . 'views/templates/admin/sync.tpl');
        $this->context->smarty->assign('content', $output);
    }

    /**
     * Handle Login
     *
     * Handles the login form submission and validates the provided API key and account name.
     * If the login is successful, redirects to the sync page. Otherwise, displays an error message.
     *
     * @return void
     */
    public function handleLogin(): void
    {
        $apiKey = Tools::getValue('api_key');
        $accountName = Tools::getValue('account_name');

        try {
            if (!ServiceRegistry::getInstance()->get(LoginServiceInterface::class)
                ->handleLogin($apiKey, $accountName)) {
                $this->context->smarty->assign([
                    'error' => 'Login failed. Please check your credentials.'
                ]);
                $this->displayLogin();

                return;
            }

            Tools::redirectAdmin($this->context->link
                    ->getAdminLink('AdminChannelEngine') . '&action=displaySync');
        } catch (Exception $e) {
            $this->context->smarty->assign([
                'error' => 'Login failed.'
            ]);
            $this->displayLogin();
        }
    }
}
