<?php
declare(strict_types=1);

/*
 * Created by solutionDrive GmbH
 *
 * @copyright solutionDrive GmbH
 */

namespace sdDeploymentHelperShopware\Commands;

use sdDeploymentHelperShopware\Services\SnippetsReaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SnippetsList extends Command
{
    /** @var SnippetsReaderInterface */
    private $snippetsReader;

    public function __construct(
        SnippetsReaderInterface $snippetsReader
    ) {
        parent::__construct();
        $this->snippetsReader = $snippetsReader;
    }

    protected function configure()
    {
        $this
            ->setName('sd:snippets:list')
            ->setDescription('Lists all snippets that are found in the directory.')
            ->addOption(
                'source',
                null,
                InputOption::VALUE_REQUIRED,
                'The folder from where the snippets should be imported, relative to Shopware\'s root folder',
                'snippets'
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $sourceDir = $input->getOption('source');

        $snippets = $this->snippetsReader->readSnippets($sourceDir);
        foreach ($snippets as $namespace => $data) {
            $output->writeln($namespace);
        }
    }


}
