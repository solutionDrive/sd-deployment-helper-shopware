<?php
declare(strict_types=1);

/*
 * Created by solutionDrive GmbH
 *
 * @copyright solutionDrive GmbH
 */

namespace spec\sdDeploymentHelperShopware\Services;

use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use sdDeploymentHelperShopware\Services\SnippetsReader;
use Symfony\Component\Console\Output\OutputInterface;

class SnippetsReaderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(SnippetsReader::class);
    }

    public function it_implements_correct_interface()
    {
        $this->shouldImplement(SnippetsReaderInterface::class);
    }

    // For the moment nothing to test here anymore because there is much shopware logic which is not specable at the moment
}
