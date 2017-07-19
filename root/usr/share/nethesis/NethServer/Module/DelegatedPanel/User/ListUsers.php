<?php
namespace NethServer\Module\DelegatedPanel\User;


/**
 * List Users
 *
 * @author stephane de Labrusse <stephdl@de-labrusse.fr>
 */

class ListUsers extends \Nethgui\Adapter\LazyLoaderAdapter
{
    private $platform;
    private $provider;
    private $accounts = array();
    
    private $defaults = array (
        'AdminPanels' => '',
        'AdminAllPanels' => '',
    );

    private function getValue($user, $prop)
    {
        if (isset($this->delegations[$user][$prop])) {
            return $this->delegations[$user][$prop];
        } else {
            return $this->defaults[$prop];
        }
    }


    public function __construct(\Nethgui\System\PlatformInterface $platform)
    {
        $this->platform = $platform;
        $this->provider = new \NethServer\Tool\UserProvider($this->platform);
        $this->users = $this->provider->getUsers();
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
        $this->accounts = $this->platform->getDatabase('accounts')->getAll('user');
        $this->delegations = $this->platform->getDatabase('delegations')->getAll('user');

        foreach ($this->users as $user => $values) {

            if (preg_match ('/(admin)/', $user)) {
               continue;
            }

            $loader[$user]['username'] = $user;
            foreach (array_keys($this->defaults) as $prop) {
                $loader[$user][$prop] = $this->getValue($user, $prop);
            }
        }

        return $loader;
    }

}
