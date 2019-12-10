<?php
declare(strict_types=1);

/*
 * Created by netlogix GmbH & Co. KG
 *
 * @copyright netlogix GmbH & Co. KG
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
