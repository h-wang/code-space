<?php

namespace Wispiring\CodeSpace\Exporter;

class NginxConfExporter
{
    protected $repositories;
    protected $path = null;
    const FILENAME_PREFIX = 'code-space.';
    const PORT = 80;
    const TEMPLATE_SYMFONY_4 = '

';

    public function __construct($repositories)
    {
        $this->repositories = $repositories;
    }

    public function export()
    {
        $res = [];
        foreach ($this->repositories as $r) {
            if (file_exists($r->getPath().'/web/app.php')) {
                $res[] = [
                    'app' => 'Symfony',
                    'version' => 3,
                    'name' => self::FILENAME_PREFIX.str_replace('/', '__', $r->getName()).'.conf',
                    'shortName' => $r->getShortName(),
                    'path' => $r->getPath().'/web',
                    'logPath' => '',
                ];
            } elseif (file_exists($r->getPath().'/public/index.php') && file_exists($r->getPath().'/.env')) {
                $res[] = [
                    'app' => 'Symfony',
                    'version' => 4,
                    'name' => self::FILENAME_PREFIX.str_replace('/', '__', $r->getName()).'.conf',
                    'shortName' => $r->getShortName(),
                    'path' => $r->getPath().'/public',
                ];
            }
        }

        return $res;
    }

    public function exportToFile()
    {
        if ($path = $this->getPath()) {
            $repos = $this->export();
            foreach ($repos as $e) {
                $template = file_get_contents(
                    __DIR__.'/templates/nginx.'.strtolower($e['app']).'.'.$e['version'].'.conf'
                );
                $template = str_replace('{PORT}', self::PORT, $template);
                $template = str_replace('{SERVER_NAME}', $e['shortName'].'.localhost', $template);
                $template = str_replace('{ROOT}', $e['path'], $template);
                $template = str_replace('{LOG_PATH}', $path['log'], $template);
                file_put_contents($path['conf'].'/'.$e['name'], $template);
            }
        }

        return false;
    }

    public function getPath()
    {
        if (null === $this->path) {
            $this->path = false;
            switch (strtolower(substr(PHP_OS, 0, 5))) {
                case 'darwi':
                    $brewV = shell_exec('brew -v');
                    if ('homebrew' === strtolower(substr($brewV, 0, 8))) {
                        $this->path = [
                            'conf' => '/usr/local/etc/nginx/servers',
                            'log' => '/usr/local/var/log/nginx',
                        ];
                    }
                    break;
                case 'linux':
                    $this->path = [
                        'conf' => '/etc/nginx/site-enabled',
                        'log' => '/var/log/nginx',
                    ];
                    break;
                default:
                    break;
            }
        }

        return $this->path;
    }
}
