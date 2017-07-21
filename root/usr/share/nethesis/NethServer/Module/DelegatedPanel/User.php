<?php

namespace NethServer\Module\DelegatedPanel;

/**
 * Description of nethserver-delegation
 *
 * @author stephane de Labrusse <stephdl@de-labrusse.fr>
 */

class User extends \Nethgui\Controller\TableController
{
    public function initialize()
    {
        $columns = array(
            'Key',
            'Actions'
        );

        $this
            ->setTableAdapter(new User\ListUsers($this->getPlatform()))
            ->setColumns($columns)
            ->addRowAction(new User\Modify('update'))
            ->addTableAction(new \Nethgui\Controller\Table\Help('Help'))

        ;
        parent::initialize();
    }


}

