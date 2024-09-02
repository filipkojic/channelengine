<?php

class AdminChannelEngineController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();

        $action = Tools::getValue('action');

        if ($action == 'displayLogin') {
            $this->context->smarty->assign([
                'module_dir' => $this->module->getPathUri(),
            ]);

            $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/login.tpl');
            $this->context->smarty->assign('content', $output);
        } else {
            $this->context->smarty->assign([
                'module_dir' => $this->module->getPathUri(),
            ]);

            $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/configure.tpl');
            $this->context->smarty->assign('content', $output);
        }

        return $output;
    }


    public function displayLogin()
    {
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        $this->setTemplate('admin/login.tpl');
    }
}
