<?php

namespace App\Helpers;

class AssetVersioning
{
    public static function version($path)
    {
        if (!file_exists(public_path($path))) {
            return asset($path);
        }

        $timestamp = filemtime(public_path($path));
        $version = '?v=' . $timestamp;

        return asset($path) . $version;
    }
} 