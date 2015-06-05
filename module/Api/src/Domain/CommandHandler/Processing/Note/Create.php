<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\Processing\Note;

use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\Repository\Note as NoteRepository;
use Dvsa\Olcs\Transfer\Command\Processing\Note\Create as CreateCommand;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Exception;
use Doctrine\ORM\Query;

use Dvsa\Olcs\Api\Entity;
use Dvsa\Olcs\Api\Entity\System\RefData;

/**
 * Create a Note
 */
final class Create extends CreateUpdateAbstract
{
    /**
     * @var String
     * @Transfer\Filter({"name":"Zend\Filter\StringTrim"})
     * @Transfer\Validator({
     *     "name":"Zend\Validator\InArray",
     *     "options": {
     *          "haystack": {
     *              "note_t_app",
     *              "note_t_bus",
     *              "note_t_case",
     *              "note_t_lic",
     *              "note_t_org",
     *              "note_t_person",
     *              "note_t_tm"
     *          }
     *      }
     * })
     */
    protected $noteType;

    /**
     * @param CreateCommand $command
     * @throws Exception
     * @return Result
     */
    public function handleCommand(CommandInterface $command)
    {
        $result = new Result();

        /** @var NoteRepository $repo */
        $repo = $this->getRepo();

        try {

            $repo->beginTransaction();

            $note = $this->getNoteEntity($command);
            $note->setComment($command->getComment());

            $this->getRepo()->save($note);
            $this->getRepo()->commit();

            $result->addId('note', $note->getId());
            $result->addMessage('Note created');

            return $result;

        } catch (\Exception $ex) {

            $this->getRepo()->rollback();
            throw $ex;
        }
    }

    /**
     * @param CommandInterface $command
     * @return Entity\Note\Note
     */
    protected function retrieveEntity(CommandInterface $command)
    {
        return new Entity\Note\Note();
    }
}
