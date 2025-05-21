<?php

namespace Mellooh\PurePermsX\handler;

use Mellooh\PurePermsX\PPX;
use pocketmine\permission\PermissionAttachment;
use pocketmine\player\Player;

class PermissionHandler{

    private PPX $plugin;

    public function __construct(PPX $plugin){
        $this->plugin = $plugin;
    }

    /** @var PermissionAttachment[] */
    private array $attachments = [];

    public function applyPermissions(Player $player): void {
        $name = strtolower($player->getName());

        if (isset($this->attachments[$name])) {
            $player->removeAttachment($this->attachments[$name]);
            unset($this->attachments[$name]);
        }

        $userGroup = $this->plugin->getUserManager()->getGroup($name);
        $perms = $this->plugin->getGroupManager()->getPermissions($userGroup);

        $attachment = $player->addAttachment($this->plugin);
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