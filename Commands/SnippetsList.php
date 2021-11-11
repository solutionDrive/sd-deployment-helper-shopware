<?php
declare(strict_types=1);

/*
 * Created by netlogix GmbH & Co. KG
 *
 * @copyright netlogix GmbH & Co. KG
 */

namespace sdDeploymentHelperShopware\Commands;

use sdDeploymentHelperShopware\Services\SnippetsReaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
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

    /**
     * {@inheritdoc}
     */
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
            ]
        );

        $sourceDir = $input->getOption('source');

        $snippets = $this->snippetsReader->readSnippets($sourceDir);
        foreach ($snippets as $namespace => $localeData) {
            foreach ($localeData as $locale => $data) {
                foreach ($data as $key => $value) {
                    if (45 < \strlen($value)) {
                        $value = \substr($value, 0, 45) . '...';
                    }
                    $table->addRow([$namespace, $locale, $key, $value]);
                }
                $table->addRow(new TableSeparator());
            }
        }
        $table->render();
    }
}
