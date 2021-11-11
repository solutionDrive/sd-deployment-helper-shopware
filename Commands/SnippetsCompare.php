<?php
declare(strict_types=1);

/*
 * Created by netlogix GmbH & Co. KG
 *
 * @copyright netlogix GmbH & Co. KG
 */

namespace sdDeploymentHelperShopware\Commands;

use sdDeploymentHelperShopware\Services\SnippetsDatabaseCompareInterface;
use sdDeploymentHelperShopware\Services\SnippetsReaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SnippetsCompare extends Command
{
    /** @var SnippetsReaderInterface */
    private $snippetsReader;

    /** @var SnippetsDatabaseCompareInterface */
    private $snippetsDatabaseCompare;

    public function __construct(
        SnippetsReaderInterface $snippetsReader,
        SnippetsDatabaseCompareInterface $snippetsDatabaseCompare
    ) {
        parent::__construct();
        $this->snippetsReader = $snippetsReader;
        $this->snippetsDatabaseCompare = $snippetsDatabaseCompare;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sd:snippets:compare')
            ->setDescription('Compares all snippets that are found in the directory with the database and fails if they are different.')
            ->addOption(
                'source',
                null,
                InputOption::VALUE_REQUIRED,
                'The folder from where the snippets should be imported, relative to Shopware\'s root folder',
                'snippets'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $table = new Table($output);
        $table->setHeaders(
            [
                'namespace',
                'locale',
                'key',
                'value',
                'database',
            ]
        );

        $sourceDir = $input->getOption('source');

        $snippets = $this->snippetsReader->readSnippets($sourceDir);
        $valuesThatDiffer = $this->snippetsDatabaseCompare->compareSnippetsWithDatabase($snippets);

        $table->setRows($valuesThatDiffer);
        $table->render();

        if (false === empty($valuesThatDiffer)) {
            return 1;
        }
        return 0;
    }
}
