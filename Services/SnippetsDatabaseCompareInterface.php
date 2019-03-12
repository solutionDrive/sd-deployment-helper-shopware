<?php
declare(strict_types=1);

/*
 * Created by solutionDrive GmbH
 *
 * @copyright solutionDrive GmbH
 */

namespace sdDeploymentHelperShopware\Services;

interface SnippetsDatabaseCompareInterface
{
    /**
     * Compares the given snippets with the database and returns all snippets which are different
     *
     * @param string[][] $snippets
     *
     * @return string[][]
     */
    public function compareSnippetsWithDatabase(array $snippets): array;
}
