<?php

namespace Wispiring\CodeSpace\Exporter;

class VscodeProjectJsonExporter
{
    protected $repositories;

    public function __construct($repositories)
    {
        $this->repositories = $repositories;
    }

    public function getConfigFilePath()
    {
        switch (strtoupper(substr(PHP_OS, 0, 3))) {
            case 'DAR':
                return getenv('HOME').'/Library/Application Support/Code/User/projects.json';
            case 'WIN':
                return '%APPDATA%\Code\User\projects.json';
            case 'LIN':
            default:
                return getenv('HOME').'/.config/Code/User/projects.json';
        }
    }

    public function generateConfig()
    {
        $o = '';
        foreach ($this->repositories as $r) {
            $o .= PHP_EOL.'  {'.PHP_EOL;
            $o .= '    "name": "'.$r->getName().'",'.PHP_EOL;
            $o .= '    "rootPath": "'.$r->getPath().'",'.PHP_EOL;
            $o .= '    "paths": "[]",'.PHP_EOL;
            $o .= '    "group": ""'.PHP_EOL;
            $o .= '  },';
        }

        return '['.rtrim($o, ',').PHP_EOL.']'.PHP_EOL;
    }

    public function export()
    {
        return file_put_contents($this->getConfigFilePath(), $this->generateConfig());
    }
}
