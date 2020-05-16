<?php

namespace NerdWerk\Authentication\AuthenticationProviders;

use NerdWerk\Authentication\Directory as Directory;

class ConfigFile extends \NerdWerk\Authentication\AuthenticationProvider
{

    private $directory = null;

    public function __construct(\NerdWerk\Config $config = null)
    {

        $this->directory = new Directory();

        // Throw an exception if config not passed
        if(!$config)
        {
            throw new \NerdWerk\Exceptions\ConfigException("Application configuration not passed to constructor", 100);
        }

        // Load users and groups from config...
        if(isset($config->authentication) && count($config->authentication) > 0)
        {
            foreach($config->authentication as $k => $v)
            {
                switch(strtolower($k))
                {
                    case "domains":
                        foreach($v as $domain)
                        {
                            array_change_key_case($domain, CASE_LOWER);
                            if(isset($domain['domain']))
                            {
                                $this->directory->createDomain($domain['domain'], (isset($domain['name']) ? $domain['name'] : null));
                            } else {
                                throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("Domain configuration not valid - Missing 'domain' key", 401);
                            }
                        }
                    break;

                    case "groups":
                        foreach($v as $group)
                        {
                            array_change_key_case($group, CASE_LOWER);
                            if(isset($group['domain']))
                            {
                                if(isset($group['name']))
                                {
                                    // Check that the domain this group belongs to has been defined...
                                    if(!$this->directory->domainExists($group['domain']))
                                    {
                                        $this->directory->createDomain($group['domain']);
                                    }

                                    // Add the group to the domain...
                                    $this->directory->domain($group['domain'])->createGroup($group['name'], (isset($group['permissions']) && is_array($group['permissions']) ? $group['permissions'] : []));

                                } else {
                                    throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("Group configuration not valid - Missing 'name' key", 403);
                                }
                            } else {
                                throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("Group configuration not valid - Missing 'domain' key", 402);
                            }
                        }
                    break;

                    case "users":
                        foreach($v as $user)
                        {
                            array_change_key_case($user, CASE_LOWER);
                            if(isset($user['domain']))
                            {
                                if(isset($user['username']))
                                {
                                    // Check that a password has been defined...
                                    if(!isset($user['password']))
                                    {
                                        throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("User configuration not valid - No password has been defined for '".$user['username']."'", 407);
                                    }

                                    // Check that the domain this user belongs to has been defined...
                                    if(!$this->directory->domainExists($user['domain']))
                                    {
                                        $this->directory->createDomain($user['domain']);
                                    }

                                    // Add the user to the domain...
                                    if(!$this->directory->domain($user['domain'])->userExists($user['username']))
                                    {
                                        $this->directory->domain($group['domain'])->createUser(
                                            $user['username'],
                                            $user['password'],
                                            (isset($user['name']) ? $user['name'] : $user['username']),
                                            (isset($user['email']) ? $user['email'] : ""),
                                            (isset($user['permissions']) && is_array($user['permissions']) ? $user['permissions'] : []),
                                            (isset($user['groups']) && is_array($user['groups']) ? $user['groups'] : [])
                                        );
                                    } else {
                                        throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("User configuration not valid - Duplicate username '".$user['username']."'", 406);
                                    }
                                } else {
                                    throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("User configuration not valid - Missing 'username' key", 405);
                                }
                            } else {
                                throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("User configuration not valid - Missing 'domain' key", 404);
                            }
                        }
                    break;

                    default:
                        throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("Authentication configuration not valid - Unknown key '".$k."'", 400);
                    break;

                }
            }
        } else {
            throw new \NerdWerk\Exceptions\FileAuthenticationConfigException("Authentication configuration empty", 400);
        }

    }

    public function authenticate($domain, $username, $password) : ?\NerdWerk\Authentication\User
    {
        if($d = $this->directory->domain($domain))
        {
            if($u = $d->userExists($username))
            {
                if($u->testPassword($password))
                {
                    return $u;
                }
            }
        }
        return null;
    }

}