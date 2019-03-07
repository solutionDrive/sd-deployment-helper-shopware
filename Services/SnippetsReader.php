<?php
declare(strict_types=1);

/*
 * Created by solutionDrive GmbH
 *
 * @copyright solutionDrive GmbH
 */

namespace sdDeploymentHelperShopware\Services;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Inspired by \Shopware\Components\Snippet\DatabaseHandler
 */
class SnippetsReader implements SnippetsReaderInterface
{
    /** @var string */
    private $kernelRootDir;

    /** @var OutputInterface */
    private $output;

    public function __construct(
        string $kernelRootDir
    ) {
        $this->kernelRootDir = $kernelRootDir;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function readSnippets(
        string $snippetsDir = null
    ): array {
        $snippets = [];
        $snippetsDir = $snippetsDir ?: $this->kernelRootDir . '/snippets/';
        if (!file_exists($snippetsDir)) {
            if ($snippetsDir == ($this->kernelRootDir . '/snippets/')) {
                $this->printWarning('<info>No snippets folder found in Shopware core, skipping</info>');
            }

            return;
        }

        $inputAdapter = new \Enlight_Config_Adapter_File([
            'configDir' => $snippetsDir,
        ]);

        $finder = new Finder();
        $finder->files()->in($snippetsDir);

        foreach ($finder as $file) {
            $filePath = $file->getRelativePathname();
            if (strpos($filePath, '.ini') == strlen($filePath) - 4) {
                $namespace = substr($filePath, 0, -4);
            } else {
                continue;
            }

            $this->printNotice('<info>Reading ' . $namespace . ' namespace</info>');

            $namespaceData = new \Enlight_Components_Snippet_Namespace([
                'adapter' => $inputAdapter,
                'name' => $namespace,
            ]);

            $snippets[$namespace] = $namespaceData->read()->toArray();
        }

        return $snippets;
    }

    /**
     * Prints given $message if output interface is set and it is verbose
     *
     * @param string $message
     */
    private function printNotice($message)
    {
        if ($this->output && $this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->output->writeln($message);
        }
    }

    /**
     * Prints given $message if output interface is set
     *
     * @param string $message
     */
    private function printWarning($message)
    {
        if ($this->output) {
            $this->output->writeln($message);
        }
    }
}
