<?php

namespace Mellooh\PurePermsX\listener;

use Mellooh\PurePermsX\PPX;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

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

        if (!$groupManager->groupExists($userManager->getGroup($name))) {
            $userManager->setGroup($name, "guest");
        }

        $this->plugin->getPermissionHandler()->applyPermissions($player);
    }
}