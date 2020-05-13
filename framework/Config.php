<?php

namespace NerdWerk;

class Config
{

    private $configFileExtension = ".conf.php";

    private $config = [];

    public function __construct()
    {
        // Load up configuration files from the config directory...
        if(file_exists(NW_CONFIG_PATH) && is_dir(NW_CONFIG_PATH))
        {
            // Include each file in the config directory (that is a .conf.php file...)
            $confDir = new \DirectoryIterator(NW_CONFIG_PATH);
            foreach($confDir as $confFile)
            {
                if(!$confFile->isDot() && $confFile->getFilename())
                {
                    if($this->isConfigFile($confFile->getFilename()))
                    {
                        include(NW_CONFIG_PATH.DIRECTORY_SEPARATOR.$confFile->getFilename());
                    }
                }
            }
        } else {
            throw new NerdWerk\Exceptions\NerdWerkConfigDirectoryNotFoundException("Framework config directory not found", 101);
        }
    }

    public function __toString()
    {
        return json_encode($this->config);
    }

    public function __get($k)
    {
        return (isset($this->config[$k]) ? $this->config[$k] : []);
    }

    public function __isset($k)
    {
        return isset($this->config[$k]);
    }

    // Test that the supplied filename ends with .conf.php
    private function isConfigFile($filename)
    {
        return substr($filename, -strlen($this->configFileExtension)) == $this->configFileExtension;
    }

}