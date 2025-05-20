<?php

namespace Mellooh\PurePermsX\model;

class User{

    private string $name;
    private string $group;

    public function __construct(string $name, string $group = "default") {
        $this->name = strtolower($name);
        $this->group = $group;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getGroup(): string {
        return $this->group;
    }

    public function setGroup(string $group): void {
        $this->group = $group;
    }

}