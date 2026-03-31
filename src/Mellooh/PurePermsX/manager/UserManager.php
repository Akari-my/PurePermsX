<?php

namespace Mellooh\PurePermsX\manager;

use Mellooh\PurePermsX\model\User;
use Mellooh\PurePermsX\PPX;
use pocketmine\utils\Config;

class UserManager{


    private string $userDirectory;
    private array $users = [];

    public function __construct(PPX $plugin) {
        $this->userDirectory = $plugin->getDataFolder() . "users/";
        @mkdir($this->userDirectory);

        foreach (scandir($this->userDirectory) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === "yml") {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $cfg = new Config($this->userDirectory . $file, Config::YAML);
                $group = $cfg->get("group", "guest");
                $this->users[$name] = new User($name, $group);
            }
        }
    }

    public function getUser(string $name): User {
        $name = strtolower($name);
        if (!isset($this->users[$name])) {
            $user = $this->loadUserFromFile($name);
            $this->users[$name] = $user;
        }
        return $this->users[$name];
    }

    private function loadUserFromFile(string $name): User {
        $file = $this->userDirectory . $name . ".yml";
        if (file_exists($file)) {
            $cfg = new Config($file, Config::YAML);
            $group = $cfg->get("group", "guest");
            return new User($name, $group);
        }
        return new User($name, "guest");
    }

    public function setGroup(string $name, string $group): void {
        $name = strtolower($name);
        $user = $this->getUser($name);
        $user->setGroup($group);
        $this->saveUser($user);
    }

    public function getGroup(string $name): ?string {
        return $this->getUser($name)->getGroup();
    }

    public function saveUser(User $user): void {
        $cfg = new Config($this->userDirectory . strtolower($user->getName()) . ".yml", Config::YAML);
        $cfg->set("group", $user->getGroup());
        $cfg->save();
    }
}