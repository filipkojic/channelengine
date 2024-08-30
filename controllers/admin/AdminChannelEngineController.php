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

        // Provera parametra 'action' iz URL-a
        $action = Tools::getValue('action');

        if ($action == 'displayLogin') {
            // Prikazivanje login stranice
            $this->context->smarty->assign([
                'module_dir' => $this->module->getPathUri(),
            ]);

            // Fetch login.tpl template
            $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/login.tpl');
            $this->context->smarty->assign('content', $output);
        } else {
            // Prikazivanje welcome stranice (configure.tpl)
            $this->context->smarty->assign([
                'module_dir' => $this->module->getPathUri(),
            ]);

            // Fetch configure.tpl template
            $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/configure.tpl');
            $this->context->smarty->assign('content', $output);
        }

        // Return the output
        return $output;
    }


    public function displayLogin()
    {
        // Assign Smarty variables if needed
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
        ]);

        // Display the login page
        $this->setTemplate('admin/login.tpl');
    }


}
