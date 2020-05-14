<?php

namespace NerdWerk\Authentication;

class User
{

    private $domain;
    private $permissions = [];
    private $groups = [];
    private $mergedPermissons = [];

    public $username;
    private $password;
    public $name;
    public $email;

    public function __construct(Domain $domain, string $username, string $password, string $name = null, string $email = null, array $permissions = [], array $groups = [])
    {
        $this->domain = $domain;
        $this->username = $username;
        $this->password = $password; // Must be pre-hashed using SHA256!
        $this->name = $name;
        $this->email = $email;
        $this->permissions = array_unique($permissions);
        $this->groups = array_unique($groups);
        $this->mergePermissions();
    }

    public function testPassword($hash)
    {
        return ($this->password == $hash);
    }

    private function mergePermissions()
    {
        $merged_permissons = $this->permissions;
        foreach($this->groups as $g)
        {
            if($group = $this->domain->groupExists($g))
            {
                $merged_permissons = array_merge($merged_permissons, $group->getPermissions());
            } else {
                $this->leaveGroup($group);
            }
        }
        $this->mergedPermissions = array_unique($merged_permissons);
    }

    public function grantPermission(string $permission)
    {
        if(!$this->hasPermission($permission))
        {
            $this->permissions[] = $permission;
            $this->mergePermissions();
            return true;
        }
        return false;
    }

    public function revokePermission(string $permission)
    {
        if($k = array_search($permission, $this->permissions))
        {
            unset($this->permissions[$k]);
            $this->mergePermissions();
            return true;
        }
        return false;
    }

    public function hasPermission(string $permission)
    {
        return in_array($permission, $this->mergedPermissions);
    }

    public function getPermissions()
    {
        return $this->mergedPermissions;
    }

    public function joinGroup($group)
    {
        if($group = $this->domain->groupExists($g))
        {
            $this->groups = array_unique(array_merge($this->groups, [$group]));
            $group->addMember($this->username);
            $this->mergePermissions();
            return true;
        }
        return false;
    }

    public function leaveGroup($group)
    {
        if($k = array_search($group, $this->groups))
        {
            unset($this->groups[$k]);
            if($group = $this->domain->groupExists($g))
            {
                $group->removeMember($this->username);
            }
            $this->mergePermissions();
            return true;
        }
        return false;
    }
    
}