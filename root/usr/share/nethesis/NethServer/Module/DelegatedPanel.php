<?php

namespace NethServer\Module;

/**
 * Description of nethserver-delegated-panel
 *
 * @author stephane de Labrusse <stephdl@de-labrusse.fr>
 */

class DelegatedPanel extends \Nethgui\Controller\TabsController
{
    protected function initializeAttributes(\Nethgui\Module\ModuleAttributesInterface $base)
    {
        return new \NethServer\Tool\CustomModuleAttributesProvider($base, array(            
            'category' => 'Management')
        );
    }

    public function initialize()
    {
        parent::initialize();
        $this->addChild(new \NethServer\Module\DelegatedPanel\User());
        $this->addChild(new \NethServer\Module\DelegatedPanel\Group());
    }

}
