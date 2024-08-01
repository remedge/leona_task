<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

function deleteDirectoryRecursively(string $dir): bool {
    if (! is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            deleteDirectoryRecursively($filePath);
        } else {
            unlink($filePath);
        }
    }

    return rmdir($dir);
}

$clearDir = __DIR__ . '/db';

if (is_dir($clearDir)) {
    deleteDirectoryRecursively($clearDir);
}