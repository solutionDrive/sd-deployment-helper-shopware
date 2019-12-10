<?php
declare(strict_types=1);

/*
 * Created by netlogix GmbH & Co. KG
 *
 * @copyright netlogix GmbH & Co. KG
 */

namespace spec\sdDeploymentHelperShopware\Services;

use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;
use sdDeploymentHelperShopware\Services\SnippetsReader;
use sdDeploymentHelperShopware\Services\SnippetsReaderInterface;

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

    public function let()
    {
        $kernelRootDir = vfsStream::setup('/kernel/');

        $this->beConstructedWith(
            $kernelRootDir->url()
        );
    }

    // For the moment nothing to test here anymore because there is much shopware logic which is not specable at the moment
}
