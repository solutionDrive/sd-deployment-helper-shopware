<?php
declare(strict_types=1);

/*
 * Created by netlogix GmbH & Co. KG
 *
 * @copyright netlogix GmbH & Co. KG
 */

namespace sdDeploymentHelperShopware\Services;

use Symfony\Component\Console\Output\OutputInterface;

interface SnippetsReaderInterface
{
    public function setOutput(OutputInterface $output): void;

    /**
     * @return string[]
     */
    public function readSnippets(
        string $snippetsDir = null
    ): array;
}
