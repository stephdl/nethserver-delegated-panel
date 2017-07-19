<?php
namespace NethServer\Module\DelegatedPanel\Group;


/**
 * List groups
 *
 * @author stephane de Labrusse <stephdl@de-labrusse.fr>
 */

class ListGroups extends \Nethgui\Adapter\LazyLoaderAdapter
{
    private $platform;
    private $provider;
    private $accounts = array();
    
    private $defaults = array (
        'AdminPanels' => '',
        'AdminAllPanels' => '',
    );

    private function getValue($group, $prop)
    {
        if (isset($this->delegations[$group][$prop])) {
            return $this->delegations[$group][$prop];
        } else {
            return $this->defaults[$prop];
        }
    }


    public function __construct(\Nethgui\System\PlatformInterface $platform)
    {
        $this->platform = $platform;
        $this->provider = new \NethServer\Tool\GroupProvider($this->platform);
        $this->groups = $this->provider->getGroups();
        parent::__construct(array($this, 'readMailboxes'));
    }

    public function flush()
    {
        $this->data = NULL;
        return $this;
    }

    public function readMailboxes()
    {
        $loader = new \ArrayObject();
        $this->accounts = $this->platform->getDatabase('accounts')->getAll('group');
        $this->delegations = $this->platform->getDatabase('delegations')->getAll('group');

        foreach ($this->groups as $group => $values) {

            if (preg_match ('/(admin)/', $group)) {
               continue;
            }

            $loader[$group]['groupname'] = $group;
            foreach (array_keys($this->defaults) as $prop) {
                $loader[$group][$prop] = $this->getValue($group, $prop);
            }
        }

        return $loader;
    }

}
