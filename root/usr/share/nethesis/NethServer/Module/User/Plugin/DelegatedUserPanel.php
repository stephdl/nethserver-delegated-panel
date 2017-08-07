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
        return new \NethServer\Tool\CustomModuleAttributesProvider($base, array(
            'languageCatalog' => array('NethServer_Module_DelegationOfAuthority'))
        );

        return \Nethgui\Module\SimpleModuleAttributesProvider::extendModuleAttributes($base, 'DelegatedUserPanel', 20);
    }


    public function initialize()
    {
        
        $schema = array(
            array('AdminPanels', Validate::ANYTHING, Table::FIELD, 'AdminPanels',','),
            array('AdminAllPanels', $this->createValidator()->memberOf('enabled','disabled'), Table::FIELD),
            array('sudo', $this->createValidator()->memberOf('enabled','disabled'), Table::FIELD),
            array('sudoCommands', Validate::ANYTHING, Table::FIELD),
            array('panelsDelegation', $this->createValidator()->memberOf('enabled','disabled'), Table::FIELD),
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



    public function bind(\Nethgui\Controller\RequestInterface $request)
    {
        parent::bind($request);
        if($request->isMutation() && $request->hasParameter('sudoCommands')) {
            $this->parameters['sudoCommands'] = implode(",", self::splitLines($request->getParameter('sudoCommands')));
        }
    }

    public static function splitLines($text)
    {
        return array_filter(preg_split("/[,;\s]+/", $text));
    }

    public function validate(\Nethgui\Controller\ValidationReportInterface $report)
    {
        parent::validate($report);
            $forwards = $this->parameters['sudoCommands'];
            if($forwards) {
                foreach(explode(',', $forwards) as $param) {
                    $mustStart = preg_match ("/^\/\w+/", $param);
//                  if (!file_exists($param)) {
                    if (!is_executable($param) or ! $mustStart){
                        $report->addValidationErrorMessage($this, 'ErrorSudoCommand',
                            'valid_Custom_Binary_Exclusion', array($param));
                    }
                }
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

        if(isset($this->parameters['sudoCommands'])) {
            $view['sudoCommands'] = implode("\r\n", explode(',', $this->parameters['sudoCommands']));
        }
    }

}
