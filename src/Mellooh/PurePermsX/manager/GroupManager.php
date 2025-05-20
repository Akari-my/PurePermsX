<?php

namespace Mellooh\PurePermsX\manager;

use Mellooh\PurePermsX\PPX;
use pocketmine\utils\Config;

class GroupManager {

    private string $dataFolder;
    private array $groups = [];

    public function __construct(PPX $plugin) {
        $this->dataFolder = $plugin->getDataFolder() . "groups/";

        @mkdir($this->dataFolder);

        foreach (scandir($this->dataFolder) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === "yml") {
                $groupName = pathinfo($file, PATHINFO_FILENAME);
                $config = new Config($this->dataFolder . $file, Config::YAML);
                $this->groups[$groupName] = $config->get("permissions", []);
            }
        }
    }

    public function getGroups(): array {
        return array_keys($this->groups);
    }

    public function createGroup(string $name): bool {
        $file = $this->dataFolder . $name . ".yml";
        if (file_exists($file)) return false;

        $cfg = new Config($file, Config::YAML);
        $cfg->set("permissions", []);
        $cfg->save();
        $this->groups[$name] = [];
        return true;
    }

    public function deleteGroup(string $group): bool {
        if (!isset($this->groups[$group])) return false;

        $file = $this->dataFolder . $group . ".yml";
        if (file_exists($file)) @unlink($file);

        unset($this->groups[$group]);
        return true;
    }

    public function groupExists(string $group): bool {
        return isset($this->groups[$group]);
    }

    public function addPermission(string $group, string $permission): bool {
        if (!isset($this->groups[$group])) return false;

        if (!in_array($permission, $this->groups[$group])) {
            $this->groups[$group][] = $permission;
            $cfg = new Config($this->dataFolder . $group . ".yml", Config::YAML);
            $cfg->set("permissions", $this->groups[$group]);
            $cfg->save();
        }
        return true;
    }

    public function removePermission(string $group, string $permission): bool {
        if (!isset($this->groups[$group])) return false;

        $index = array_search($permission, $this->groups[$group]);
        if ($index === false) return false;

        unset($this->groups[$group][$index]);
        $this->groups[$group] = array_values($this->groups[$group]);

        $cfg = new Config($this->dataFolder . $group . ".yml", Config::YAML);
        $cfg->set("permissions", $this->groups[$group]);
        $cfg->save();
        return true;
    }

    public function getPermissions(string $group): array {
        return $this->groups[$group] ?? [];
    }

    public function setPermissions(string $group, array $permissions): bool {
        if (!isset($this->groups[$group])) return false;

        $this->groups[$group] = $permissions;
        $cfg = new Config($this->dataFolder . $group . ".yml", Config::YAML);
        $cfg->set("permissions", $permissions);
        $cfg->save();
        return true;
    }
}