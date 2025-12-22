<?php

declare(strict_types=1);

namespace Mellooh\PurePermsX\utils;

use pocketmine\plugin\Plugin;

final class UpdateChecker{

    public const REMOTE_PLUGIN_YML = "https://raw.githubusercontent.com/Akari-my/PurePermsX/main/plugin.yml";

    public static function check(Plugin $plugin) : void{
        $localVersion = (string) $plugin->getDescription()->getVersion();

        $plugin->getLogger()->info("Checking for updates...");

        $plugin->getServer()->getAsyncPool()->submitTask(
            new UpdateCheckTask(self::REMOTE_PLUGIN_YML, $localVersion, $plugin->getName())
        );
    }

    public static function normalize(string $v) : string{
        $v = trim($v);
        if($v !== "" && ($v[0] === "v" || $v[0] === "V")){
            $v = substr($v, 1);
        }
        return $v;
    }
}