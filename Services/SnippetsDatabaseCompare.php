<?php
declare(strict_types=1);

/*
 * Created by solutionDrive GmbH
 *
 * @copyright solutionDrive GmbH
 */

namespace sdDeploymentHelperShopware\Services;

use Doctrine\ORM\EntityRepository;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Shop\Locale;
use Shopware\Models\Snippet\Snippet;

class SnippetsDatabaseCompare implements SnippetsDatabaseCompareInterface
{
    /** @var ModelManager */
    private $entityManager;

    /** @var EntityRepository */
    private $localeRepository;

    /** @var EntityRepository */
    private $snippetRepository;

    public function __construct(
        ModelManager $entityManager
    ) {
        $this->entityManager = $entityManager;

        $this->localeRepository = $entityManager->getRepository(Locale::class);
        $this->snippetRepository = $entityManager->getRepository(Snippet::class);
    }

    /**
     * {@inheritdoc}
     */
    public function compareSnippetsWithDatabase(
        array $snippets
    ): array {
        $valuesThatDiffer = [];

        foreach ($snippets as $namespace=> $localeData) {
            foreach ($localeData as $locale => $data) {
                $localeObject = $this->localeRepository->findOneBy(['locale' => $locale]);
                if (null === $localeObject) {
                    continue;
                }

                foreach ($data as $key => $value) {
                    $databaseSnippet = $this->snippetRepository->findOneBy(['namespace' => $namespace, 'localeId' => $localeObject->getId(), 'name' => $key]);
                    if (null === $databaseSnippet) {
                        continue;
                    }

                    $databaseValue = $databaseSnippet->getValue();
                    if ($databaseValue !== $value) {
                        $valuesThatDiffer[] = [
                            'namespace' => $namespace,
                            'locale'    => $locale,
                            'key'       => $key,
                            'value'     => $value,
                            'database'  => $databaseValue,
                        ];
                    }
                }
            }
        }

        return $valuesThatDiffer;
    }
}
