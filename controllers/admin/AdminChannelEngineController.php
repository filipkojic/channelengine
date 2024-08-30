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
            'module_dir' => $this->module->getPathUri(),  // Popravka module_dir
            //'ps_version' => _PS_VERSION_,
        ]);

        // Fetch the content
        $output = $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/configure.tpl');
        $this->context->smarty->assign('content', $output);

        // Return the output
        return $output;
    }

}
