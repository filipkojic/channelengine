<?php

use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\SyncServiceInterface;
use ChannelEngine\PrestaShop\Classes\Business\Services\SyncService;
use ChannelEngine\PrestaShop\Classes\Utility\ServiceRegistry;

/**
 * Class AdminSyncController
 *
 * This class handles the synchronization process of products between PrestaShop
 * and ChannelEngine, invoked from the PrestaShop admin panel.
 */
class AdminSyncController extends ModuleAdminController
{
    /**
     * Constructor
     *
     * Initializes the controller with Bootstrap styling and calls the parent constructor.
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
            $this->sendJsonResponse(false, 'Invalid action: ' . $action);
            return;
        }

        $this->$action();
    }

    /**
     * Starts the synchronization process.
     *
     * This method fetches the language ID from the context, initiates the product synchronization
     * process via the SyncService, and sends a JSON response based on the result.
     *
     * @return void
     */
    public function startSync(): void
    {
        $id_lang = (int)$this->context->language->id;

        try {
            $syncService = ServiceRegistry::getInstance()->get(SyncServiceInterface::class);
            $response = $syncService->startSync($id_lang);

            if ($response !== true) {
                $this->sendJsonResponse(false, 'Synchronization failed: Unknown error occurred');
                return;
            }

            $this->sendJsonResponse(true, 'Synchronization successful!');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Sends a JSON response to the client.
     *
     * This helper function is used to send a standardized JSON response
     * containing a success status and a message.
     *
     * @param bool $success Indicates if the operation was successful.
     * @param string $message The message to be sent in the response.
     * @return void
     */
    private function sendJsonResponse(bool $success, string $message): void
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
