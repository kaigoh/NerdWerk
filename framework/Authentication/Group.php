<?php

namespace NerdWerk\Authentication;

class Group
{
    private $name;
    private $permissions;
    private $members;

    public function __construct($name, $permissions = [])
    {
        $this->name = $name;
        $this->permissions = array_values(array_unique($permissions));
    }

    public function getName()
    {
        return $this->name;
    }

    public function grantPermission(string $permission)
    {
        if(!$this->hasPermission($permission))
        {
            $this->permissions[] = $permission;
            return true;
        }
        return false;
    }

    public function revokePermission(string $permission)
    {
        if($k = array_search($permission, $this->permissions))
        {
            unset($this->permissions[$k]);
            return true;
        }
        return false;
    }

    public function hasPermission(string $permission)
    {
        return in_array($permission, $this->permissions);
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function addMember(User $user)
    {
        if(!in_array($user->username, $this->members))
        {
            $this->members[] = $user->username;
            return true;
        }
        return false;
    }

    public function removeMember(User $user)
    {
        if($k = array_search($user->username, $this->members))
        {
            unset($this->users[$k]);
            return true;
        }
        return false;
    }

}