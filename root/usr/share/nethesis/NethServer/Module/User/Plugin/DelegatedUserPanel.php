<?php
namespace NethServer\Module\User\Plugin;


use Nethgui\System\PlatformInterface as Validate;
use Nethgui\Controller\Table\Modify as Table;

/**
 * delegated user plugin panel
 *
 * @author stephane de labrusse <stephdl@de-labrusse.fr>
 */
class DelegatedUserPanel extends \Nethgui\Controller\Table\RowPluginAction
{

    protected function initializeAttributes(\Nethgui\Module\ModuleAttributesInterface $base)
    {
        return \Nethgui\Module\SimpleModuleAttributesProvider::extendModuleAttributes($base, 'DelegatedUserPanel', 20);
    }

    public function initialize()
    {
        
        $schema = array(
            array('AdminPanels', Validate::ANYTHING, Table::FIELD, 'AdminPanels',','),
            array('AdminAllPanels', $this->createValidator()->memberOf('enabled','disabled'), Table::FIELD),
        );

        $this->setSchemaAddition($schema);
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
