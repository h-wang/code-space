<?php

namespace Wispiring\CodeSpace\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ScanCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('scan')
            ->setDescription('Scans your directory for code repositories.')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'The path to scan. Optional, default ~/code'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $spaces = $this->scanPath($input);

        $o = $this->getStyler($input, $output);
        $o->title('CodeSpace: Code repository scan');
        $o->section("\xF0\x9F\x93\x81 : [<info>".$this->scanPath."</>]");
        $o->listing(
            array_map(
                function ($c) {
                    return '<comment>'.$c->getGroup().'</>/<info>'.$c->getShortName().'</> '.$c->getPath();
                },
                $spaces
            )
        );
        $o->success("\xF0\x9F\x93\xA3  ".count($spaces)." code repositories found. \xF0\x9F\x8E\x89");
    }
}
