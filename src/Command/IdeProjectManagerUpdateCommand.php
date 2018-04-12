<?php

namespace Wispiring\CodeSpace\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Wispiring\CodeSpace\Scanner;
use Wispiring\CodeSpace\Exporter\VscodeProjectJsonExporter;
use Wispiring\CodeSpace\Exporter\AtomProjectsCsonExporter;
use RuntimeException;

class IdeProjectManagerUpdateCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('ide:pm')
            ->setDescription('Updates IDE\'s project manager configuration file. Supports Atom and VSCode.')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'The path to scan'
            )
            ->addOption(
                'ide',
                null,
                InputOption::VALUE_OPTIONAL,
                'The name of the IDE, only "Atom" and "VSCode" are supported for now'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $spaces = $this->scanPath($input);
        $ideName = $input->getOption('ide');

        $o = $this->getStyler($input, $output);
        $o->title("CodeSpace: Scanning project in \xF0\x9F\x93\x81 : [<info>".$this->scanPath."</>]");

        switch (strtolower($ideName)) {
            case 'atom':
                $this->export('Atom', (new AtomProjectsCsonExporter($spaces)), $o, count($spaces));
                break;
            case 'vscode':
                $this->export('VSCode', (new VscodeProjectJsonExporter($spaces)), $o, count($spaces));
                break;
            default:
                $this->export('Atom', (new AtomProjectsCsonExporter($spaces)), $o, count($spaces));
                $this->export('VSCode', (new VscodeProjectJsonExporter($spaces)), $o, count($spaces));
                break;
        }
    }

    protected function export($ideName, $exporter, $styler, $projectCount)
    {
        $exporter->export();
        $styler->section($ideName.' - [<info>'.$exporter->getConfigFilePath().'</>]');
        $styler->success("\xF0\x9F\x93\xA3  $projectCount $ideName projects added. \xF0\x9F\x8E\x89");
    }
}
