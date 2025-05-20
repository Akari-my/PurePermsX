<?php

namespace Mellooh\PurePermsX\utils;

class FileUtils{

    public static function ensureDirectory(string $path): void {
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
    }
}