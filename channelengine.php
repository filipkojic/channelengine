<?php
/**
 * 2007-2024 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2024 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

use ChannelEngine\PrestaShop\Classes\Bootstrap;
use ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces\SyncServiceInterface;
use ChannelEngine\PrestaShop\Classes\Utility\ChannelEngineInstaller;
use ChannelEngine\PrestaShop\Classes\Utility\ServiceRegistry;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Class ChannelEngine
 *
 * This class represents the ChannelEngine module in PrestaShop. It handles the installation,
 * uninstallation, and configuration of the module, as well as registering necessary hooks
 * and managing the module's tab in the back office.
 */
class ChannelEngine extends Module
{
    /**
     * @var bool Indicates whether the configuration form is enabled.
     */
    protected $config_form = false;

    /**
     * Constructor for the ChannelEngine module.
     *
     * Initializes the module with its name, description, and version, and ensures
     * that the Bootstrap system is initialized. Additionally, it sets the minimum
     * and maximum compatible PrestaShop versions.
     *
     * @throws Exception If there is an error during the initialization of the module.
     */
    public function __construct()
    {
        $this->name = 'channelengine';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Filip Kojic';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Channel Engine');
        $this->description = $this->l('Filip\'s Channel Engine.');

        $this->ps_versions_compliancy = ['min' => '8.1', 'max' => _PS_VERSION_];
        // $this->registerHook('actionProductSave');
        Bootstrap::initialize();
    }

    /**
     * Install the module.
     *
     * This method installs the module, registers necessary hooks, and sets up the
     * database tables for storing credentials and product synchronization data.
     *
     * @return bool True if installation was successful, false otherwise.
     */
    public function install(): bool
    {
        $installer = new ChannelEngineInstaller($this);
        return parent::install() && $installer->install();
    }

    /**
     * Uninstall the module.
     *
     * This method uninstalls the module, unregisters hooks, and removes the
     * database tables related to ChannelEngine.
     *
     * @return bool True if uninstallation was successful, false otherwise.
     */
    public function uninstall(): bool
    {
        $installer = new ChannelEngineInstaller($this);
        return $installer->uninstall() && parent::uninstall();
    }

    /**
     * Redirects to the module's admin configuration page.
     *
     * This method handles the redirection to the module's admin interface where the
     * user can configure the ChannelEngine settings.
     *
     * @return void
     */
    public function getContent(): void
    {
        $link = $this->context->link->getAdminLink('AdminChannelEngine');
        Tools::redirectAdmin($link);
    }

    /**
     * Hook that is triggered when a product is updated.
     *
     * This method listens for the `actionProductUpdate` hook and synchronizes the updated
     * product with ChannelEngine. If synchronization is successful, a log is created. If
     * an error occurs, it is logged as well.
     *
     * @param array $params Parameters passed by the hook, including the product ID.
     * @return void
     */
    public function hookActionProductUpdate(array $params): void
    {
        if (isset($params['id_product'])) {
            $productId = (int)$params['id_product'];

            try {
                $syncService = ServiceRegistry::getInstance()->get(SyncServiceInterface::class);
                $syncService->syncSingleProduct($productId);

                PrestaShopLogger::addLog('Synchronization successful for product ID: ' . $productId, 1);
            } catch (Exception $e) {
                PrestaShopLogger::addLog('Error during synchronization for product ID: ' . $productId . ' - ' . $e->getMessage(), 3);
            }
        }
    }
}
