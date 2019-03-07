<?php
declare(strict_types=1);

/*
 * Created by solutionDrive GmbH
 *
 * @copyright solutionDrive GmbH
 */

namespace sdDeploymentHelperShopware\Services;

use Symfony\Component\Console\Output\OutputInterface;

interface SnippetsReaderInterface
{
    public function setOutput(OutputInterface $output);

    /**
     * @return string[]
     */
    public function readSnippets(
        string $snippetsDir = null
    ): array;
}
