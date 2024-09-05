<?php

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\SyncServiceInterface;
use ChannelEngine\PrestaShop\Classes\Business\Services\SyncService;
use ChannelEngine\PrestaShop\Classes\Utility\ServiceRegistry;

class AdminSyncController extends ModuleAdminController
{
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
            //$this->defaultAction(); // Call the default action if the method does not exist
        }
    }

    /**
     * Pokreće proces sinhronizacije.
     */
    public function startSync()
    {
        $id_lang = (int)$this->context->language->id;

        try {
            // Preuzimanje i formatiranje proizvoda iz servisa
            $products = ServiceRegistry::getInstance()->get(SyncServiceInterface::class)->getFormattedProducts($id_lang);

            // Pokretanje sinhronizacije putem servisa
            $response = ServiceRegistry::getInstance()->get(SyncServiceInterface::class)->startSync($products);

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
        exit;
    }
}
