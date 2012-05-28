<?php

/*
 * This file is part of Nocline Bundle.
 *
 * (c) 2012 Bruno ABENA < bruno@activdev.com >
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ActivDev\NoclineBundle\Services;

use Symfony\Component\HttpKernel\Kernel;
use ActivDev\NoclineBundle\Services\Util\BaseCommandConfiguration;

class CommandConfiguration extends BaseCommandConfiguration
{
    protected $config;
    
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->config = require  __DIR__ . '/../Resources/config/commands.php';
    }
    
    public function hasConfiguration($command) 
    {
        return $this->getConfiguration($command) !== null;
    }
    
    protected function getConfiguration($command) 
    {
        if(isset($this->config[$command]))
        {
            return $this->config[$command];
        }
        
        return null;
    }
    
    public function getJavascript($command) 
    {
        if(!($config = $this->getConfiguration($command)))
        {
            return null;
        }
        
        if(isset($config['javascript']))
        {
            return file_get_contents(__DIR__ . '/../Javascripts/'.$config['javascript'].'.js');
        }
        
        return null;
    }
    
    public function getArgOptData($command, $arg_opt) 
    {
        if(!($config = $this->getConfiguration($command)))
        {
            return null;
        }
        $data = null;
        
        if(isset($config['data_methods']) && isset($config['data_methods'][$arg_opt]))
        {
            $data = call_user_func($config['data_methods'][$arg_opt]);
        }
        elseif(isset($config['data']) && isset($config['data'][$arg_opt]))
        {
           $data = $config['data'][$arg_opt];
        }
        
        return $data;
    }
}
