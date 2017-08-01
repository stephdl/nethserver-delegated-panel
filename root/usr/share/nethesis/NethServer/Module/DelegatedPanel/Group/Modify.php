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
            array('sudo', $this->createValidator()->memberOf('enabled','disabled'), Table::FIELD),
            array('sudoCommands', Validate::ANYTHING, Table::FIELD),
            array('panelsDelegation', $this->createValidator()->memberOf('enabled','disabled'), Table::FIELD),
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
        $sudo = $this->parameters['sudo'];
        $sudoCommands = $this->parameters['sudoCommands'];
        $panels = implode (',',(json_decode(json_encode($this->parameters['AdminPanels']),true)));
        $delegation = $this->parameters['panelsDelegation'];

        $db->setKey($group, 'group', array(
            'AdminPanels' => $panels,
            'AdminAllPanels' => $allpanels,
            'sudo' => $sudo,
            'sudoCommands' => $sudoCommands,
            'panelsDelegation' => $delegation));
    }

    public function process()
    {
        if ($this->getRequest()->isMutation()) {
             $this->saveProps();
             $this->getParent()->getAdapter()->flush();
             $this->getPlatform()->signalEvent('nethserver-delegation-save');
        }
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
                  //if (!file_exists($param)) {
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

        if(isset($this->parameters['sudoCommands'])) {
            $view['sudoCommands'] = implode("\r\n", explode(',', $this->parameters['sudoCommands']));
        }

        $view['AdminPanelsDatasource'] = array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt));
        }, $this->templates);

    }

}
