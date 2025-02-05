<?php

/**
 * Db Query Service Manager Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain;

use Dvsa\Olcs\Api\Domain\Validation\Handlers\HandlerInterface;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Dvsa\Olcs\Api\Domain\DbQueryServiceManager;
use Laminas\ServiceManager\ConfigInterface;

/**
 * Db Query Service Manager Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class DbQueryServiceManagerTest extends MockeryTestCase
{
    /**
     * @var DbQueryServiceManager
     */
    protected $sut;

    public function setUp(): void
    {
        $config = m::mock(ConfigInterface::class);
        $config->shouldReceive('configureServiceManager')->with(m::type(DbQueryServiceManager::class));

        $this->sut = new DbQueryServiceManager($config);
    }

    public function testGet()
    {
        $mock = m::mock(HandlerInterface::class);

        $this->sut->setService('Foo', $mock);

        $this->assertSame($mock, $this->sut->get('Foo'));
    }

    public function testValidate()
    {
        $this->assertNull($this->sut->validate(null));
    }

    /**
     * @todo To be removed as part of OLCS-28149
     */
    public function testValidatePlugin()
    {
        $this->assertNull($this->sut->validatePlugin(null));
    }
}
