<?php

namespace Wispiring\CodeSpace;

class Scanner
{
    const SKIP_DIR_NAME = [
        '.',
        '..',
        '.git',
        'vendor',
        'node_modules',
        '.vscode',
        '.c9',
        '.metadata',
    ];

    public function scan($path)
    {
        $dirs = $projects = [];
        $this->scanRecursive($path, $dirs);

        foreach ($dirs as $dir) {
            $projects[] = (new Project())
                ->setShortName(basename($dir))
                ->setGroup(basename(dirname($dir)))
                ->setName(basename(dirname($dir)).'/'.basename($dir))
                ->setPath($dir);
        }

        return $projects;
    }

    private function scanRecursive($path, &$dirs)
    {
        $files = scandir($path);
        foreach ($files as $filename) {
            $fullPath = $path.'/'.$filename;
            if (in_array($filename, self::SKIP_DIR_NAME) || !is_dir($fullPath)) {
                continue;
            }
            if (file_exists($fullPath.'/.git/HEAD')) {
                $dirs[] = $fullPath;
            } else {
                $this->scanRecursive($fullPath, $dirs);
            }
        }
    }
}
