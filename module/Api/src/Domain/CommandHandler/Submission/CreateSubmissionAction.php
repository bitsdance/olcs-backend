<?php

/**
 * Create SubmissionAction
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Submission;

use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Entity\Submission\SubmissionAction;
use Dvsa\Olcs\Api\Entity\Submission\Submission;
use Dvsa\Olcs\Api\Entity\Pi\Reason;
use Dvsa\Olcs\Transfer\Command\Submission\CreateSubmissionAction as Cmd;

/**
 * Create SubmissionAction
 */
final class CreateSubmissionAction extends AbstractCommandHandler
{
    protected $repoServiceName = 'SubmissionAction';

    public function handleCommand(CommandInterface $command)
    {
        $submissionAction = $this->createSubmissionAction($command);

        $this->getRepo()->save($submissionAction);

        $result = new Result();
        $result->addId('submissionAction', $submissionAction->getId());
        $result->addMessage('Submission Action created successfully');

        return $result;
    }

    /**
     * @param Cmd $command
     * @return SubmissionAction
     */
    private function createSubmissionAction(Cmd $command)
    {
        $actionTypes = array_map(
            function ($actionTypeId) {
                return $this->getRepo()->getRefdataReference($actionTypeId);
            },
            $command->getActionTypes()
        );

        $submissionAction = new SubmissionAction(
            $this->getRepo()->getReference(Submission::class, $command->getSubmission()),
            $command->getIsDecision(),
            $actionTypes,
            $command->getComment()
        );

        if ($command->getReasons() !== null) {
            $reasons = array_map(
                function ($reasonId) {
                    return $this->getRepo()->getReference(Reason::class, $reasonId);
                },
                $command->getReasons()
            );
            $submissionAction->setReasons($reasons);
        }

        return $submissionAction;
    }
}
