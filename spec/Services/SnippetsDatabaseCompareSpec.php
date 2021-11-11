<?php
declare(strict_types=1);

/*
 * Created by netlogix GmbH & Co. KG
 *
 * @copyright netlogix GmbH & Co. KG
 */

namespace spec\sdDeploymentHelperShopware\Services;

use Doctrine\ORM\EntityRepository;
use PhpSpec\ObjectBehavior;
use sdDeploymentHelperShopware\Services\SnippetsDatabaseCompare;
use sdDeploymentHelperShopware\Services\SnippetsDatabaseCompareInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Shop\Locale;
use Shopware\Models\Snippet\Snippet;

class SnippetsDatabaseCompareSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(SnippetsDatabaseCompare::class);
    }

    public function it_implements_correct_interface(): void
    {
        $this->shouldImplement(SnippetsDatabaseCompareInterface::class);
    }

    public function let(
        ModelManager $entityManager,
        EntityRepository $localeRepository,
        EntityRepository $snippetRepository
    ): void {
        $this->beConstructedWith(
            $entityManager
        );

        $entityManager->getRepository(Locale::class)
            ->willReturn($localeRepository);

        $entityManager->getRepository(Snippet::class)
            ->willReturn($snippetRepository);
    }

    public function it_can_compare_snippets_with_the_database(
        EntityRepository $localeRepository,
        Locale $deLocale,
        Locale $enLocale,
        EntityRepository $snippetRepository,
        Snippet $deSnippet,
        Snippet $enSnippet
    ): void {
        $snippets = [
            'name/space' => [
                'de_DE' => [
                    'keyDE1' => 'valueDE1',
                ],
                'en_EN' => [
                    'keyEN1' => 'valueEN1',
                ],
            ],
        ];

        $deLocale->getId()
            ->willReturn(1);
        $enLocale->getId()
            ->willReturn(2);

        $deSnippet->getValue()
            ->willReturn('valueDE1');

        $enSnippet->getValue()
            ->willReturn('valueEN1');

        $localeRepository->findOneBy(['locale' => 'de_DE'])
            ->willReturn($deLocale);

        $localeRepository->findOneBy(['locale' => 'en_EN'])
            ->willReturn($enLocale);

        $snippetRepository->findOneBy(['namespace' => 'name/space', 'localeId' => 1, 'name' => 'keyDE1'])
            ->willReturn($deSnippet);
        $snippetRepository->findOneBy(['namespace' => 'name/space', 'localeId' => 2, 'name' => 'keyEN1'])
            ->willReturn($enSnippet);

        $this->compareSnippetsWithDatabase($snippets)
            ->shouldReturn([]);
    }

    public function it_will_return_snippets_that_differ_from_database(
        EntityRepository $localeRepository,
        Locale $deLocale,
        Locale $enLocale,
        EntityRepository $snippetRepository,
        Snippet $deSnippet,
        Snippet $enSnippet
    ): void {
        $snippets = [
            'name/space' => [
                'de_DE' => [
                    'keyDE1' => 'valueDE1',
                ],
                'en_EN' => [
                    'keyEN1' => 'valueEN1',
                ],
            ],
        ];

        $deLocale->getId()
            ->willReturn(1);
        $enLocale->getId()
            ->willReturn(2);

        $deSnippet->getValue()
            ->willReturn('differentDE1');

        $enSnippet->getValue()
            ->willReturn('valueEN1');

        $localeRepository->findOneBy(['locale' => 'de_DE'])
            ->willReturn($deLocale);

        $localeRepository->findOneBy(['locale' => 'en_EN'])
            ->willReturn($enLocale);

        $snippetRepository->findOneBy(['namespace' => 'name/space', 'localeId' => 1, 'name' => 'keyDE1'])
            ->willReturn($deSnippet);
        $snippetRepository->findOneBy(['namespace' => 'name/space', 'localeId' => 2, 'name' => 'keyEN1'])
            ->willReturn($enSnippet);

        $this->compareSnippetsWithDatabase($snippets)
            ->shouldReturn(
                [
                    [
                        'namespace' => 'name/space',
                        'locale'    => 'de_DE',
                        'key'       => 'keyDE1',
                        'value'     => 'valueDE1',
                        'database'  => 'differentDE1',
                    ],
                ]
            );
    }
}
