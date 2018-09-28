<?php

namespace Wispiring\CodeSpace\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Wispiring\CodeSpace\Exporter\NginxConfExporter;

class NginxConfExportCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('export:nginx-conf')
            ->setDescription('Generate Nginx server config files')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'The path to scan'
            )
            ->addOption(
                'apply',
                null,
                InputOption::VALUE_NONE,
                'Apply changes'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $spaces = $this->scanPath($input);
        $apply = (bool) $input->getOption('apply');

        $exporter = new NginxConfExporter($spaces);
        if (!$exporter->getPath()) {
            $output->writeln('<error>Unsupported Nginx environment</>: <comment>'.php_uname().'</>');

            return;
        }

        $o = $this->getStyler($input, $output);
        $o->title('CodeSpace: Exporting Nginx configuration files');
        $o->section("\xF0\x9F\x93\x81 : [<info>".$this->scanPath.'</>]');

        $toExport = $exporter->export();
        foreach ($toExport as $e) {
            $o->text(
                '<info>'.$e['shortName'].'</>: <comment>'.$e['app'].' '.$e['version'].
                '</> - <question>['.$e['name'].']</> -> <question>'.$e['path'].'</>'
            );
        }
        if ($apply) {
            $exporter->exportToFile();
        }

        $o->success(
            "\xF0\x9F\x93\xA3  ".count($toExport).' exportables '.
            ($apply ? 'exported' : 'found. Use --apply to put configs in place').
            ". \xF0\x9F\x8E\x89"
        );
    }
}
