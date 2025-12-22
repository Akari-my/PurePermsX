<?php

declare(strict_types=1);

namespace Mellooh\PurePermsX\utils;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use function preg_match;
use function stream_context_create;
use function trim;
use function file_get_contents;

final class UpdateCheckTask extends AsyncTask{

    public function __construct(
        private readonly string $url,
        private readonly string $localVersion,
        private readonly string $pluginName
    ){}

    public function onRun() : void{
        $ctx = stream_context_create([
            "http" => [
                "timeout" => 10,
                "header" => "User-Agent: {$this->pluginName}-UpdateChecker\r\n",
                "ignore_errors" => true
            ],
            "ssl" => [
                "verify_peer" => true,
                "verify_peer_name" => true
            ]
        ]);

        $data = @file_get_contents($this->url, false, $ctx);

        $usedInsecure = false;
        if($data === false || $data === null){
            $ctx2 = stream_context_create([
                "http" => [
                    "timeout" => 10,
                    "header" => "User-Agent: {$this->pluginName}-UpdateChecker\r\n",
                    "ignore_errors" => true
                ],
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                ]
            ]);
            $data = @file_get_contents($this->url, false, $ctx2);
            $usedInsecure = true;
        }

        if($data === false || $data === null){
            $this->setResult([
                "ok" => false,
                "error" => "HTTP request failed (file_get_contents). Your PHP/Windows SSL CA store is likely missing."
            ]);
            return;
        }

        $remoteVersion = null;
        if(preg_match('/^\s*version\s*:\s*"?([^"\r\n]+)"?\s*$/mi', $data, $m) === 1){
            $remoteVersion = trim($m[1]);
        }

        if($remoteVersion === null || $remoteVersion === ""){
            $this->setResult([
                "ok" => false,
                "error" => "Could not parse 'version:' from remote plugin.yml"
            ]);
            return;
        }

        $this->setResult([
            "ok" => true,
            "local" => $this->localVersion,
            "remote" => $remoteVersion,
            "insecure" => $usedInsecure
        ]);
    }

    public function onCompletion() : void{
        $server = Server::getInstance();
        $r = $this->getResult();

        if(!is_array($r) || !($r["ok"] ?? false)){
            $err = is_array($r) ? (string)($r["error"] ?? "unknown error") : "invalid result";
            $server->getLogger()->warning("Update check failed: " . $err);
            return;
        }

        $local = (string) $r["local"];
        $remote = (string) $r["remote"];
        $insecure = (bool) ($r["insecure"] ?? false);

        if($insecure){
            $server->getLogger()->warning("HTTPS certificate verification failed; used insecure fallback. Fix CA bundle for a secure check.");
        }

        if(version_compare(UpdateChecker::normalize($remote), UpdateChecker::normalize($local), ">")){
            $server->getLogger()->notice("[PPX Checker] Your version of PurePermsX is out of date.");
            $server->getLogger()->notice("[PPX Checker] Current: {$local}");
            $server->getLogger()->notice("[PPX Checker] Latest: {$remote}");
            $server->getLogger()->notice("[PPX Checker] Download Here!: https://github.com/Akari-my/PurePermsX/releases");
        }else{
            // never mind
        }
    }
}