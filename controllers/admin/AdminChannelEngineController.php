<?php

use ChannelEngine\PrestaShop\Classes\Proxy\ChannelEngineProxy;

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

    public function handleLogin()
    {
        $apiKey = Tools::getValue('api_key');
        $accountName = Tools::getValue('account_name');

        $channelEngineProxy = new ChannelEngineProxy();

        try {
            // Validacija kredencijala putem proxy-ja
            $responseData = $channelEngineProxy->validateCredentials($apiKey);

            // Ako je validacija uspešna, čuvamo API ključ i naziv naloga u PrestaShop konfiguraciji
            Configuration::updateValue('CHANNELENGINE_ACCOUNT_NAME', $accountName);
            Configuration::updateValue('CHANNELENGINE_API_KEY', $apiKey);

            // Redirekcija na sinhronizaciju
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminChannelEngine') . '&action=displaySync');
        } catch (Exception $e) {
            // U slučaju greške, prikazujemo poruku o grešci na login stranici
            $this->context->smarty->assign([
                'error' => 'Login failed. Please check your credentials: '
            ]);
            $this->displayLogin();
        }
    }

    protected function displaySync()
    {
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        $output = $this->context->smarty->fetch($this->module->getLocalPath() . 'views/templates/admin/sync.tpl');
        $this->context->smarty->assign('content', $output);
    }

    public function startSync()
    {
        try {
            $id_lang = (int)$this->context->language->id; // ID jezika iz konteksta

            // Dobavljanje liste proizvoda
            $allProducts = Product::getProducts($id_lang, 0, 0, 'id_product', 'ASC');

            // Formiranje podataka u formatu koji zahteva ChannelEngine API
            $products = [];
            foreach ($allProducts as $productData) {
                $product = [
                    'MerchantProductNo' => $productData['id_product'], // Unique identifier in PrestaShop
                    'Name' => $productData['name'],
                    'Description' => $productData['description'] ?? '',
                    'Price' => (float)$productData['price'],
                ];

                $products[] = $product;
            }

            // Inicijalizacija ChannelEngineProxy-a
            $channelEngineProxy = new ChannelEngineProxy();

            // Slanje proizvoda putem proxy-ja
            $response = $channelEngineProxy->syncProducts($products);

            // Provera uspeha zahteva prema API-ju
            if ($response === true) {
                $this->sendJsonResponse(true, 'Synchronization successful!');
            } else {
                $errorMessage = $response['Message'] ?? 'Unknown error occurred';
                $this->sendJsonResponse(false, 'Synchronization failed: ' . $errorMessage);
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Helper funkcija za slanje JSON odgovora.
     *
     * @param bool $success
     * @param string $message
     */
    private function sendJsonResponse(bool $success, string $message)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

}
