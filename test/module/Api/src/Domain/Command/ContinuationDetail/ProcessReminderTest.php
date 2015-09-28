<?php

/**
 * Process Reminder Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\Command\ContinuationDetail;

use Dvsa\Olcs\Api\Domain\Command\ContinuationDetail\ProcessReminder;
use PHPUnit_Framework_TestCase;

/**
 * Process Reminder Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class ProcessReminderTest extends PHPUnit_Framework_TestCase
{
    public function testStructure()
    {
        $command = ProcessReminder::create(
            [
                'id' => 1,
                'user' => 2
            ]
        );

        $this->assertEquals(1, $command->getId());
        $this->assertEquals(2, $command->getUser());
    }
}
