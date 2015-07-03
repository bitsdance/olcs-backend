<?php

/**
 * Create From Grant Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\Command\InspectionRequest;

use Dvsa\Olcs\Api\Domain\Command\InspectionRequest\CreateFromGrant;
use PHPUnit_Framework_TestCase;

/**
 * Create From Grant Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class CreateFromGrantTest extends PHPUnit_Framework_TestCase
{
    public function testStructure()
    {
        $command = CreateFromGrant::create(
            [
                'application' => 1,
                'duePeriod' => 2,
                'caseworkerNotes' => 'notes',
            ]
        );

        $this->assertEquals(1, $command->getApplication());
        $this->assertEquals(2, $command->getDuePeriod());
        $this->assertEquals('notes', $command->getCaseworkerNotes());
    }
}
