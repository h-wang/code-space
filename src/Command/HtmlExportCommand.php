<?php

namespace Wispiring\CodeSpace\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Wispiring\CodeSpace\Exporter\HtmlExporter;

class HtmlExportCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('export:html')
            ->setDescription('Export code repositories')
            ->addArgument(
                'target_path',
                InputArgument::REQUIRED,
                'Target path to put the exported file'
            )
            ->addOption(
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'The path to scan'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $spaces = $this->scanPath($input);
        $targetPath = $input->getArgument('target_path');

        $o = $this->getStyler($input, $output);
        $o->title('CodeSpace: Exporting repositories');
        $o->section("\xF0\x9F\x93\x81 : [<info>".$this->scanPath.'</>]');
        $o->section("\xF0\x9F\x93\x84 : [<info>".$targetPath.'</>]');

        $exporter = new HtmlExporter($spaces);
        $exporter->exportToFile($targetPath);

        $o->success("\xF0\x9F\x93\xA3  ".count($spaces)." repositories exported. \xF0\x9F\x8E\x89");
    }
}
