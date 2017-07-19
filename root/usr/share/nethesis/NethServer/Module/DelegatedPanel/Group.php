<?php

namespace NethServer\Module\DelegatedPanel;

/**
 * Description of nethserver-delegated-panel
 *
 * @author stephane de Labrusse <stephdl@de-labrusse.fr>
 */

class Group extends \Nethgui\Controller\TableController
{
    public function initialize()
    {
        $columns = array(
            'Key',
            'Actions'
        );

        $this
            ->setTableAdapter(new Group\ListGroups($this->getPlatform()))
            ->setColumns($columns)
            ->addRowAction(new Group\Modify('update'))
            ->addTableAction(new \Nethgui\Controller\Table\Help('Help'))

        ;
        parent::initialize();
    }


}

