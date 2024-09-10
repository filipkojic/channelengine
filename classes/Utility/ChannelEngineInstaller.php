<?php

namespace ChannelEngine\PrestaShop\Classes\Utility;

use Db;
use Module;
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
    /**
     * @var Module The module instance.
     */
    private $module;

    /**
     * @var array The list of hooks that this module will register.
     */
    private static $hooks = [
        'actionProductUpdate'
    ];

    /**
     * Constructor for the ChannelEngineInstaller.
     *
     * @param Module $module The module instance used for installation/uninstallation.
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Handles the installation of the module.
     *
     * This method calls the necessary steps to install the module,
     * including creating a credentials table, adding a menu item, and registering hooks.
     *
     * @return bool True if the installation was successful, false otherwise.
     */
    public function install(): bool
    {
        return $this->addMenuItem() && $this->addHooks();
    }

    /**
     * Handles the uninstallation of the module.
     *
     * This method performs the steps necessary to uninstall the module,
     * such as dropping the credentials table, removing the menu item, and unregistering hooks.
     *
     * @return bool True if the uninstallation was successful, false otherwise.
     */
    public function uninstall(): bool
    {
        return $this->removeMenuItem() && $this->removeHooks();
    }

    /**
     * Adds a new tab to the PrestaShop admin menu.
     *
     * This method adds a new tab (menu item) under the "Orders" section in the PrestaShop back office,
     * linking to the ChannelEngine module.
     *
     * @return bool True if the tab was successfully added, false otherwise.
     */
    private function addMenuItem(): bool
    {
        $tab = new Tab();
        $tabRepository = $this->module->get('prestashop.core.admin.tab.repository');
        $tab->id_parent = (int)$tabRepository->findOneIdByClassName('AdminParentOrders');
        $tab->class_name = 'AdminChannelEngine';
        $tab->module = $this->module->name;
        $tab->active = 1;

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'ChannelEngine';
        }

        return $tab->add();
    }

    /**
     * Removes the tab from the PrestaShop admin menu during uninstallation.
     *
     * @return bool True if the tab was successfully removed, false otherwise.
     */
    private function removeMenuItem(): bool
    {
        $tabRepository = $this->module->get('prestashop.core.admin.tab.repository');
        $id_tab = (int)$tabRepository->findOneIdByClassName('AdminChannelEngine');

        if ($id_tab) {
            $tab = new Tab($id_tab);

            return $tab->delete();
        }

        return false;
    }

    /**
     * Registers necessary hooks for the module.
     *
     * @return bool True if all hooks were successfully registered, false otherwise.
     */
    private function addHooks(): bool
    {
        $result = true;

        foreach (self::$hooks as $hook) {
            $result = $result && $this->module->registerHook($hook);
        }

        return $result;
    }

    /**
     * Unregisters the hooks during uninstallation.
     *
     * @return bool True if all hooks were successfully unregistered, false otherwise.
     */
    private function removeHooks(): bool
    {
        $result = true;

        foreach (self::$hooks as $hook) {
            $result = $result && $this->module->unregisterHook($hook);
        }

        return $result;
    }
}
