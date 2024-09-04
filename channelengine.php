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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2024 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

use PrestaShopBundle\Entity\Repository\TabRepository;

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
    protected $config_form = false;

    /**
     * ChannelEngine constructor.
     *
     * Initializes the module with basic information such as name, tab, version, and author.
     * It also sets the bootstrap to true to ensure compatibility with PrestaShop's bootstrap framework.
     */
    public function __construct()
    {
        $this->name = 'channelengine';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Filip Kojic';
        $this->need_instance = 0;

        $this->bootstrap = true; // Ensure compatibility with bootstrap

        parent::__construct();

        $this->displayName = $this->l('Channel Engine');
        $this->description = $this->l('Filip\'s Channel Engine.');

        $this->ps_versions_compliancy = ['min' => '8.1', 'max' => _PS_VERSION_];
    }

    /**
     * Install the module.
     *
     * This method handles the installation of the module, including registering hooks and adding a tab
     * to the back office.
     *
     * @return bool True if installation was successful, false otherwise.
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->installTab('AdminParentOrders', 'AdminChannelEngine', 'ChannelEngine')
            && $this->createCredentialsTable();
    }

    private function createCredentialsTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'channelengine_credentials (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                account_name VARCHAR(255) NOT NULL,
                api_key VARCHAR(255) NOT NULL,
                date_add DATETIME NOT NULL
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Install a tab in the back office.
     *
     * This method adds a new tab under a specified parent tab in the back office.
     *
     * @param string $parent The parent tab's class name.
     * @param string $class_name The class name of the new tab.
     * @param string $name The name of the new tab.
     * @return bool True if the tab was added successfully, false otherwise.
     */
    private function installTab($parent, $class_name, $name)
    {
        $tab = new Tab();
        $tabRepository = $this->get('prestashop.core.admin.tab.repository');
        $tab->id_parent = (int) $tabRepository->findOneIdByClassName($parent);
        $tab->class_name = $class_name;
        $tab->module = $this->name;
        $tab->active = 1;

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }

        return $tab->add();
    }

    /**
     * Uninstall the module.
     *
     * This method handles the uninstallation of the module, including removing the tab
     * and any configuration values.
     *
     * @return bool True if uninstallation was successful, false otherwise.
     */
    public function uninstall()
    {
        Configuration::deleteByName('CHANNELENGINE_LIVE_MODE');
        return parent::uninstall() && $this->uninstallTab('AdminChannelEngine')
             && $this->dropCredentialsTable();
    }

    private function dropCredentialsTable()
    {
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'channelengine_credentials';
        return Db::getInstance()->execute($sql);
    }

    /**
     * Uninstall a tab from the back office.
     *
     * This method removes a tab from the back office.
     *
     * @param string $class_name The class name of the tab to remove.
     * @return bool True if the tab was removed successfully, false otherwise.
     */
    private function uninstallTab($class_name)
    {
        $tabRepository = $this->get('prestashop.core.admin.tab.repository');
        $id_tab = (int) $tabRepository->findOneIdByClassName($class_name);
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return false;
    }


    /**
     * Get content for the configuration page.
     *
     * This method redirects the user to the AdminChannelEngine controller for further configuration.
     *
     * @return void
     */
    public function getContent()
    {
        $link = $this->context->link->getAdminLink('AdminChannelEngine');
        Tools::redirectAdmin($link);
    }

    /**
     * Hook for displaying content in the back office header.
     *
     * This method loads specific CSS and JS files when the module's configuration page is accessed.
     *
     * @return void
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Hook for displaying content in the front office header.
     *
     * This method loads specific CSS and JS files on the front end when needed.
     *
     * @return void
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
