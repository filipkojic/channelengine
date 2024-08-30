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

class ChannelEngine extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'channelengine';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Filip Kojic';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Channel Engine');
        $this->description = $this->l('Filip\'s Channel Engine.');

        $this->ps_versions_compliancy = ['min' => '8.1', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->installTab('AdminParentOrders', 'AdminChannelEngine', 'ChannelEngine');
    }

    private function installTab($parent, $class_name, $name)
    {
        $tab = new Tab();
        $tabRepository = $this->get('prestashop.core.admin.tab.repository');
        $tab->id_parent = (int) $tabRepository->findOneIdByClassName('AdminParentOrders');
        $tab->class_name = $class_name;
        $tab->module = $this->name;
        $tab->active = 1;

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }

        return $tab->add();
    }

    public function uninstall()
    {
        Configuration::deleteByName('CHANNELENGINE_LIVE_MODE');
        return parent::uninstall() && $this->uninstallTab('AdminChannelEngine');
    }

    private function uninstallTab($class_name)
    {
        $tabRepository = $this->get('prestashop.core.admin.tab.repository');
        $id_tab = (int) $tabRepository->findOneIdByClassName('AdminParentOrders');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return false;
    }

    public function getContent()
    {
//        if (Tools::isSubmit('submitChannelengineModule')) {
//            $this->postProcess();
//        }

        $this->context->smarty->assign([
            'content' => 'Hello World!',
        ]);

        // Prikazivanje hello world (configure.tpl) stranice
        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
