<?php

namespace Wispiring\CodeSpace\Exporter;

class AtomProjectsCsonExporter
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
            case 'LIN':
                return getenv('HOME').'/.atom/projects.cson';
            case 'WIN':
            default:
                throw new \Exception('Win OS is not supported yet', 1);
        }
    }

    public function generateConfig()
    {
        $o = '';
        foreach ($this->repositories as $r) {
            $o .= PHP_EOL.'  {'.PHP_EOL;
            // $o .= '    title: "'.$r->getShortName().'"'.PHP_EOL;
            $o .= '    title: "'.$r->getName().'"'.PHP_EOL;
            $o .= '    group: "'.$r->getGroup().'"'.PHP_EOL;
            $o .= '    paths: ['.PHP_EOL.'        "'.$r->getPath().'"'.PHP_EOL.'    ]'.PHP_EOL;
            $o .= '    icon: "icon-repo"'.PHP_EOL;
            $o .= '  }';
        }

        return '['.$o.PHP_EOL.']';
    }

    public function export()
    {
        return file_put_contents($this->getConfigFilePath(), $this->generateConfig());
    }
}
