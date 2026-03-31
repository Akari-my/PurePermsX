<?php

namespace Mellooh\PurePermsX\listener;

use Mellooh\PurePermsX\PPX;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener{

    private PPX $plugin;

    public function __construct(PPX $plugin){
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $name = strtolower($player->getName());

        $userManager = $this->plugin->getUserManager();
        $groupManager = $this->plugin->getGroupManager();

        $userGroup = $userManager->getGroup($name) ?? "guest";
        if (!$groupManager->groupExists($userGroup)) {
            $userManager->setGroup($name, "guest");
        }

        $this->plugin->getPermissionHandler()->applyPermissions($player);
    }

    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        $this->plugin->getPermissionHandler()->onQuit($player);
    }
}