<?php

namespace ChannelEngine\PrestaShop\Classes\Utility;

use Db;
use Tab;
use Language;

/**
 * Class ChannelEngine
 *
 * This class handles the installation,
 * uninstallation, and configuration of the module, as well as registering necessary hooks
 * and managing the module's tab in the back office.
 */
class ChannelEngineInstaller
{
    private $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * Handles the installation of the module.
     */
    public function install()
    {
        return $this->createCredentialsTable() &&
            $this->addMenuItem();
    }

    /**
     * Handles the uninstallation of the module.
     */
    public function uninstall()
    {
        return $this->dropCredentialsTable() && $this->removeMenuItem();
    }

    /**
     * Creates the credentials table.
     */
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
     * Drops the credentials table.
     */
    private function dropCredentialsTable()
    {
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'channelengine_credentials';
        return Db::getInstance()->execute($sql);
    }

    /**
     * Adds a new tab to the PrestaShop admin menu.
     */
    private function addMenuItem()
    {
        $tab = new Tab();
        $tabRepository = $this->module->get('prestashop.core.admin.tab.repository');
        $tab->id_parent = (int) $tabRepository->findOneIdByClassName('AdminParentOrders');
        $tab->class_name = 'AdminChannelEngine';
        $tab->module = $this->module->name;
        $tab->active = 1;

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'ChannelEngine';
        }

        return $tab->add();
    }

    /**
     * Removes the tab from the PrestaShop admin menu.
     */
    private function removeMenuItem()
    {
        $tabRepository = $this->module->get('prestashop.core.admin.tab.repository');
        $id_tab = (int) $tabRepository->findOneIdByClassName('AdminChannelEngine');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return false;
    }
}
