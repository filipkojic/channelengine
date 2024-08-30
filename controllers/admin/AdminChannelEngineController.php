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

        // Dodavanje promenljive 'content' u Smarty
        $this->context->smarty->assign(array(
            'content' => 'Hello World',
        ));

        // Učitavanje i prikaz šablona

        return $this->display(__FILE__  , 'hello.tpl');
        $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/hello.tpl');

        // Postavljanje prikazanog sadržaja kao sadržaja stranice
        $this->context->smarty->assign('content', $output);
    }
}
