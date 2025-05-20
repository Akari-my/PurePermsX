<?php

namespace Mellooh\PurePermsX\listener;

use Mellooh\PurePermsX\PPX;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $name = strtolower($player->getName());

        $userManager = PPX::getInstance()->getUserManager();
        $groupManager = PPX::getInstance()->getGroupManager();

        if (!$groupManager->groupExists($userManager->getGroup($name))) {
            $userManager->setGroup($name, "guest");
        }

        PPX::getInstance()->getPermissionHandler()->applyPermissions($player);
    }
}