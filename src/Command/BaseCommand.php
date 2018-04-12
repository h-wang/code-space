<?php

namespace Wispiring\CodeSpace\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Wispiring\CodeSpace\Scanner;
use RuntimeException;

class BaseCommand extends Command
{
    protected $styler = null;
    protected $scanPath;

    protected function getStyler(InputInterface $input, OutputInterface $output)
    {
        if (null === $this->styler) {
            $this->styler = new SymfonyStyle($input, $output);
        }

        return $this->styler;
    }

    protected function setOutputStyle(OutputInterface &$output)
    {
        $output->getFormatter()->setStyle(
            'header',
            new OutputFormatterStyle('white', 'green', array('bold'))
        );
    }

    protected function getHomeDirectory()
    {
        return getenv("HOME");
    }

    protected function getScanPath(InputInterface $input)
    {
        if (!$this->scanPath) {
            $home = $this->getHomeDirectory();
            $path = $input->getOption('path');
            if ($path) {
                if (0 === strpos($path, '~/')) {
                    $path = $home.substr($path, 1);
                }
            } else {
                $path = $home.'/code';
                if (!is_dir($path)) {
                    $path = $home.'/git';
                }
            }
            $this->scanPath = rtrim($path, '/');
        }


        return $this->scanPath;
    }

    protected function scanPath(InputInterface $input)
    {
        $scanner = new Scanner();

        return $scanner->scan($this->getScanPath($input));
    }
}
