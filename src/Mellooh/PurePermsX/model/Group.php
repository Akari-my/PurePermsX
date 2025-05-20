<?php

namespace Mellooh\PurePermsX\model;

class Group{

    private string $name;
    private array $permissions;

    public function __construct(string $name, array $permissions = []) {
        $this->name = strtolower($name);
        $this->permissions = $permissions;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPermissions(): array {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): void {
        $this->permissions = $permissions;
    }

    public function addPermission(string $permission): void {
        if (!in_array($permission, $this->permissions)) {
            $this->permissions[] = $permission;
        }
    }

    public function removePermission(string $permission): void {
        $index = array_search($permission, $this->permissions);
        if ($index !== false) {
            unset($this->permissions[$index]);
            $this->permissions = array_values($this->permissions);
        }
    }

}