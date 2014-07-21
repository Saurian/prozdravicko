<?php

namespace App\Doctrine\Console;

use Doctrine;
use Kdyby;
use Nette;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tracy\Debugger;


if (!class_exists('Tracy\Debugger')) {
    class_alias('Nette\Diagnostics\Debugger', 'Tracy\Debugger');
}


/**
 * @author Pavel Paulik <pavel.paulik@seznam.cz>
 */
class MigrationsVersionCommand extends Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand
{

    /**
     * @var \Kdyby\Doctrine\Tools\CacheCleaner
     * @inject
     */
    public $cacheCleaner;


    public function __construct()
    {
        parent::__construct();
    }


    protected function configure()
    {
        parent::configure();
        $this->addOption('debug-mode', NULL, InputOption::VALUE_OPTIONAL, "Force Tracy debug mode", TRUE);
    }


    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        Debugger::$productionMode = !$input->getOption('debug-mode');
        $this->cacheCleaner->invalidate();
    }

}
