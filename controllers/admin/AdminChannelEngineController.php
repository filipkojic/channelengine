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
        $action = Tools::getValue('action', 'defaultAction');

        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            $this->defaultAction();
        }
    }

    protected function defaultAction()
    {
        $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/welcome.css');
        $this->context->controller->addJS($this->module->getPathUri() . 'views/js/welcome.js');

        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/configure.tpl');
        $this->context->smarty->assign('content', $output);
    }

    protected function displayLogin()
    {
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/login.tpl');
        $this->context->smarty->assign('content', $output);
    }
}
