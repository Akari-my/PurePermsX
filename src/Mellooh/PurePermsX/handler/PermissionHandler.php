<?php

namespace Mellooh\PurePermsX\handler;

use Mellooh\PurePermsX\PPX;
use pocketmine\permission\PermissionAttachment;
use pocketmine\player\Player;

class PermissionHandler{

    /** @var PermissionAttachment[] */
    private array $attachments = [];

    public function applyPermissions(Player $player): void {
        $name = strtolower($player->getName());

        if (isset($this->attachments[$name])) {
            $player->removeAttachment($this->attachments[$name]);
            unset($this->attachments[$name]);
        }

        $userGroup = PPX::getInstance()->getUserManager()->getGroup($name);
        $perms = PPX::getInstance()->getGroupManager()->getPermissions($userGroup);

        $attachment = $player->addAttachment(PPX::getInstance());
        foreach ($perms as $perm) {
            $attachment->setPermission($perm, true);
        }

        $this->attachments[$name] = $attachment;
    }

    public function removePermissions(Player $player): void {
        $name = strtolower($player->getName());
        if (isset($this->attachments[$name])) {
            $player->removeAttachment($this->attachments[$name]);
            unset($this->attachments[$name]);
        }
    }
}