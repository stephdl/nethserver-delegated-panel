<?php
namespace NethServer\Module\DelegatedPanel\Group;


use Nethgui\System\PlatformInterface as Validate;
use Nethgui\Controller\Table\Modify as Table;

/**
 * CRUD actions on group delegation  records
 *
 */
class Modify extends \Nethgui\Controller\Table\Modify
{

    public function initialize()
    {
        $parameterSchema = array(
            array('groupname', Validate::ANYTHING, Table::KEY),
            array('AdminPanels', Validate::ANYTHING, Table::FIELD, 'AdminPanels',','),
            array('AdminAllPanels', $this->createValidator()->memberOf('enabled','disabled'), Table::FIELD),
        );
        $this->setSchema($parameterSchema);

        parent::initialize();
    }

    private function readModules()
    {
      $values = array();
      $path = '/usr/share/nethesis/NethServer/Module/';
      $values = glob( $path . '*.{php}', GLOB_BRACE);
      $values= str_replace($path,"",$values);
      $values= str_replace(".php","",$values);
      #we remove some panels 
      $values= array_diff($values, array ('FirstConfigWiz'));
      return $values;
    }

    private function saveProps()

    {
        $props = array();
        $db = $this->getPlatform()->getDatabase('delegations');
        $group = $this->parameters['groupname'];
        $allpanels = $this->parameters['AdminAllPanels'];
        $panels = implode (',',(json_decode(json_encode($this->parameters['AdminPanels']),true)));

        $db->setKey($group, 'group', array(
            'AdminPanels' => $panels,
            'AdminAllPanels' => $allpanels));
    }

    public function process()
    {
        if ($this->getRequest()->isMutation()) {
             $this->saveProps();
             $this->getParent()->getAdapter()->flush();
             $this->getPlatform()->signalEvent('nethserver-delegated-panel-save');
        }
    }

    public function prepareView(\Nethgui\View\ViewInterface $view)
    {
        parent::prepareView($view);
        if (!$this->templates) {
            $this->templates = $this->readModules();
        }
        $view['AdminPanelsDatasource'] = array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt));
        }, $this->templates);

    }

}
