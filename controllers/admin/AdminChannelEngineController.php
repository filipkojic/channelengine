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

        // Prikazivanje hello world (configure.tpl) stranice
        $this->context->smarty->assign([
            'content' => 'Hello World!',
        ]);

        // Direktno prikazivanje šablona koristeći display metod
        //$this->display(__FILE__, 'views/templates/admin/configure.tpl');

        // Prikaz hello world stranice
        $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/configure.tpl');
        $this->context->smarty->assign('content', $output);
    }
}
