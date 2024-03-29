<?php
declare(strict_types=1);

/*
 * Created by netlogix GmbH & Co. KG
 *
 * @copyright netlogix GmbH & Co. KG
 */

namespace sdDeploymentHelperShopware\Services;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

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

    public function setOutput(OutputInterface $output): void
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
        $snippetsDir = $this->getSnippetsDir($snippetsDir);
        if (!\file_exists($snippetsDir)) {
            $this->checkSnippetsDir($snippetsDir);

            return [];
        }

        $inputAdapter = new \Enlight_Config_Adapter_File([
            'configDir' => $snippetsDir,
        ]);

        $finder = new Finder();
        $finder->files()->in($snippetsDir);

        foreach ($finder as $file) {
            $filePath = $file->getRelativePathname();
            if ('ini' === $file->getExtension()) {
                $namespace = \substr($filePath, 0, -4);
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
     */
    private function printNotice(string $message): void
    {
        if ($this->output && $this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->output->writeln($message);
        }
    }

    /**
     * Prints given $message if output interface is set
     */
    private function printWarning(string $message): void
    {
        if ($this->output) {
            $this->output->writeln($message);
        }
    }

    private function getSnippetsDir(
        string $snippetsDir = null
    ): string {
        if ($snippetsDir) {
            return $this->kernelRootDir . DIRECTORY_SEPARATOR . $snippetsDir . DIRECTORY_SEPARATOR;
        }
        return $this->kernelRootDir . DIRECTORY_SEPARATOR . 'snippets' . DIRECTORY_SEPARATOR;
    }

    private function checkSnippetsDir(
        string $snippetsDir
    ): void {
        if ($snippetsDir === ($this->kernelRootDir . DIRECTORY_SEPARATOR . 'snippets' . DIRECTORY_SEPARATOR)) {
            $this->printWarning('<info>No snippets folder found in Shopware core, skipping</info>');
        }
    }
}
