<?php

/**
 * Fees Helper Service Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Dvsa\OlcsTest\Api\Service;

use Dvsa\Olcs\Api\Service\FeesHelperService;
use Dvsa\Olcs\Api\Entity\Application\Application as ApplicationEntity;
use Dvsa\Olcs\Api\Entity\Fee\Fee as FeeEntity;
use Dvsa\Olcs\Api\Entity\Fee\FeeType as FeeTypeEntity;
use Dvsa\Olcs\Api\Entity\Licence\Licence as LicenceEntity;
use Dvsa\Olcs\Api\Entity\TrafficArea\TrafficArea as TrafficAreaEntity;
use Dvsa\Olcs\Api\Entity\System\RefData;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery as m;

/**
 * Fees Helper Service Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class FeesHelperServiceTest extends MockeryTestCase
{
    /**
     * @var \Mockery\MockInterface (Dvsa\Olcs\Api\Domain\Repository\Application)
     */
    protected $applicationRepo;

    /**
     * @var \Mockery\MockInterface (Dvsa\Olcs\Api\Domain\Repository\Fee
     */
    protected $feeRepo;

    /**
     * @var \Mockery\MockInterface (Dvsa\Olcs\Api\Domain\Repository\FeeType
     */
    protected $feeTypeRepo;

    /**
     * @var FeesHelperService
     */
    protected $sut;

    public function setUp()
    {
        // Mock the repos
        $this->applicationRepo = m::mock();
        $this->feeRepo = m::mock();
        $this->feeTypeRepo = m::mock();

        // Create service with mocked dependencies
        $this->sut = $this->createService($this->applicationRepo, $this->feeRepo, $this->feeTypeRepo);

        return parent::setUp();
    }

    private function createService($applicationRepo, $feeRepo, $feeTypeRepo)
    {
        $mockRepoManager = m::mock();

        $sm = m::mock('\Zend\ServiceManager\ServiceLocatorInterface');
        $sm
            ->shouldReceive('get')
            ->with('RepositoryServiceManager')
            ->andReturn($mockRepoManager);

        $mockRepoManager
            ->shouldReceive('get')
            ->with('Application')
            ->once()
            ->andReturn($applicationRepo)
            ->shouldReceive('get')
            ->with('Fee')
            ->once()
            ->andReturn($feeRepo)
            ->shouldReceive('get')
            ->with('FeeType')
            ->once()
            ->andReturn($feeTypeRepo);

        $sut = new FeesHelperService();
        return $sut->createService($sm);
    }

    public function testGetOutstandingFeesForApplication()
    {
        $applicationId = 69;
        $licenceId = 7;
        $trafficAreaId = TrafficAreaEntity::NORTH_EASTERN_TRAFFIC_AREA_CODE;

        $applicationFee = $this->getStubFee(99, 99.99);
        $interimFee = $this->getStubFee(101, 99.99);
        $fees = [$applicationFee, $interimFee];

        // mocks
        $goodsOrPsv = $this->refData(LicenceEntity::LICENCE_CATEGORY_GOODS_VEHICLE);
        $licenceType = $this->refData(LicenceEntity::LICENCE_TYPE_STANDARD_NATIONAL);
        $application = m::mock(ApplicationEntity::class)
            ->makePartial()
            ->setId($applicationId)
            ->setGoodsOrPsv($goodsOrPsv)
            ->setLicenceType($licenceType);
        $trafficArea = m::mock(TrafficAreaEntity::class)
            ->makePartial()
            ->setId($trafficAreaId);
        $licence = m::mock(LicenceEntity::class)
            ->makePartial()
            ->setId($licenceId)
            ->setTrafficArea($trafficArea);
        $application->setLicence($licence);

        // expectations
        $this->applicationRepo
            ->shouldReceive('fetchById')
            ->once()
            ->with($applicationId)
            ->andReturn($application);

        $application
            ->shouldReceive('getLatestOutstandingApplicationFee')
            ->once()
            ->andReturn($applicationFee);

        $application
            ->shouldReceive('getLatestOutstandingInterimFee')
            ->once()
            ->andReturn($interimFee);

        $result = $this->sut->getOutstandingFeesForApplication($applicationId);

        $this->assertEquals($fees, $result);
    }

    public function testGetOutstandingFeesForBrandNewApplication()
    {
        $applicationId = 69;
        $licenceId = 7;

        // mocks
        $application = m::mock(ApplicationEntity::class)
            ->makePartial()
            ->setId($applicationId)
            ->setGoodsOrPsv(null)
            ->setLicenceType(null);
        $licence = m::mock(LicenceEntity::class)
            ->makePartial()
            ->setId($licenceId);
        $application->setLicence($licence);

        // expectations
        $this->applicationRepo
            ->shouldReceive('fetchById')
            ->once()
            ->with($applicationId)
            ->andReturn($application);

        $application
            ->shouldReceive('getLatestOutstandingApplicationFee')
            ->once()
            ->andReturn(null);

        $application
            ->shouldReceive('getLatestOutstandingInterimFee')
            ->never(); // only called for Goods

        $result = $this->sut->getOutstandingFeesForApplication($applicationId);

        $this->assertEquals([], $result);
    }

    /**
     * @param string $amount
     * @param array $fees array of FeeEntity
     * @param array $expected allocated amounts e.g. ['97' => '12.45', '98' => '0.05']
     * @dataProvider allocateProvider()
     */
    public function testAllocatePayments($amount, $fees, $expected)
    {
        $this->assertSame($expected, $this->sut->allocatePayments($amount, $fees));
    }

    public function allocateProvider()
    {
        return [
            [
                '0.00',
                [
                    $this->getStubFee('10', '99.99'),
                    $this->getStubFee('11', '100.01'),
                ],
                [
                    '10' => '0.00',
                    '11' => '0.00',
                ]
            ],
            [
                '200.00',
                [
                    $this->getStubFee('10', '99.99'),
                    $this->getStubFee('11', '100.01'),
                ],
                [
                    '10' => '99.99',
                    '11' => '100.01',
                ]
            ],
            [
                '200.00',
                [
                    $this->getStubFee('10', '99.99', '2015-09-04'),
                    $this->getStubFee('11', '50.01', '2015-09-02'),
                    $this->getStubFee('12', '100.00', '2015-09-03'),
                    $this->getStubFee('13', '100.00', '2015-09-05'),
                ],
                [
                    '11' => '50.01',
                    '12' => '100.00',
                    '10' => '49.99',
                    '13' => '0.00',
                ]
            ],
            [
                '200.00',
                [
                    // check tie-break on same invoicedDate
                    $this->getStubFee('1', '100.00', '2015-09-03'),
                    $this->getStubFee('2', '100.00', '2015-09-02'),
                    $this->getStubFee('3', '100.00', '2015-09-02'),
                    $this->getStubFee('4', '100.00', '2015-09-02'),
                ],
                [
                    '2' => '100.00',
                    '3' => '100.00',
                    '4' => '0.00',
                    '1' => '0.00',
                ]
            ]
        ];
    }

    public function testAllocatePaymentsOverPaymentThrowsException()
    {
        $amount = '500';
        $fees = [
            $this->getStubFee('10', '99.99', '2015-09-04'),
            $this->getStubFee('11', '50.01', '2015-09-02'),
            $this->getStubFee('12', '100.00', '2015-09-03'),
            $this->getStubFee('13', '100.00', '2015-09-05'),
        ];

        $this->setExpectedException(\Dvsa\Olcs\Api\Service\Exception::class, 'Overpayments not permitted');

        $this->sut->allocatePayments($amount, $fees);
    }

    public function testGetMinPaymentForFees()
    {
        $fees = [
            $this->getStubFee('1', '5.00', '2015-09-03'),
            $this->getStubFee('2', '10.00', '2015-09-01'),
            $this->getStubFee('3', '15.00', '2015-09-02'),
        ];

        // minimum is total of fee2 + fee3 then 0.01 allocated to fee1
        $this->assertEquals('25.01', $this->sut->getMinPaymentForFees($fees));
    }

    public function testGetTotalOutstanding()
    {
         $fees = [
            $this->getStubFee('1', '99.99'),
            $this->getStubFee('1', '99.99'),
        ];

        $this->assertEquals('199.98', $this->sut->getTotalOutstanding($fees));
    }

    /**
     * @param string $amount
     * @param array $fees array of FeeEntity
     * @param string $expected formatted amount
     * @dataProvider overpaymentProvider()
     */
    public function testGetOverpaymentAmount($amount, $fees, $expected)
    {
        $this->assertSame($expected, $this->sut->getOverpaymentAmount($amount, $fees));
    }

    public function overpaymentProvider()
    {
        return [
            'no fees' => [
                '0.00',
                [],
                '0.00',
            ],
            'underpayment' => [
                '0.00',
                [
                    $this->getStubFee('10', '99.99'),
                    $this->getStubFee('11', '100.01'),
                ],
                '-200.00',
            ],
            'overpayment' => [
                '250',
                [
                    $this->getStubFee('10', '99.99'),
                    $this->getStubFee('11', '100.01'),
                ],
                '50.00',
            ],
        ];
    }

    /**
     * Helper function to generate a stub fee entity
     *
     * @param int $id
     * @param string $amount
     * @return FeeEntity
     */
    private function getStubFee($id, $amount, $invoicedDate = '2015-09-10')
    {
        $fee = m::mock(FeeEntity::class)->makePartial()
            ->setId($id)
            ->setGrossAmount($amount);
        $fee
            ->shouldReceive('getOutstandingAmount')
            ->andReturn($amount)
            ->shouldReceive('getInvoicedDate')
            ->andReturn(new \DateTime($invoicedDate));

        return $fee;
    }

    private function refData($id)
    {
        return m::mock(RefData::class)
            ->makePartial()
            ->setId($id);
    }
}
