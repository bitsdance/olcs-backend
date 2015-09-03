<?php

/**
 * Create Letter
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Document;

use Dvsa\Olcs\Api\Domain\AuthAwareInterface;
use Dvsa\Olcs\Api\Domain\AuthAwareTrait;
use Dvsa\Olcs\Api\Domain\Command\Document\CreateDocumentSpecific as CreateDocumentSpecificCmd;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Api\Domain\DocumentGeneratorAwareInterface;
use Dvsa\Olcs\Api\Domain\DocumentGeneratorAwareTrait;
use Dvsa\Olcs\Api\Domain\Util\DateTime\DateTime;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Entity\Doc\DocTemplate as Entity;
use Dvsa\Olcs\Transfer\Command\Document\CreateLetter as Cmd;

/**
 * Create Application
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
final class CreateLetter extends AbstractCommandHandler implements
    TransactionedInterface,
    DocumentGeneratorAwareInterface,
    AuthAwareInterface
{
    use DocumentGeneratorAwareTrait,
        AuthAwareTrait;

    protected $repoServiceName = 'DocTemplate';

    /**
     * @param Cmd $command
     */
    public function handleCommand(CommandInterface $command)
    {
        $queryData = $command->getData();

        $queryData['user'] = $this->getUser()->getId();

        /** @var Entity $template */
        $template = $this->getRepo()->fetchById($command->getTemplate());

        $docId = $template->getDocument()->getIdentifier();

        // Swap spaces for underscores
        $identifier = str_replace(' ', '_', $docId);

        $date = new DateTime();

        $content = $this->getDocumentGenerator()->generateFromTemplateIdentifier($identifier, $queryData);
        $fileName = $date->format('YmdHis') . '_' . $this->formatFilename($template->getDescription()) . '.rtf';
        $file = $this->getDocumentGenerator()->uploadGeneratedContent($content, null, $fileName);

        $data = [
            'identifier' => $file->getIdentifier(),
            'description' => $template->getDescription(),
            'filename' => $fileName,
            'category' => $queryData['details']['category'],
            'subCategory' => $queryData['details']['documentSubCategory'],
            'isExternal' => false,
            'isScan' => false,
            'metadata' => $command->getMeta(),
            'size' => $file->getSize()
        ];

        $this->result->merge($this->handleSideEffect(CreateDocumentSpecificCmd::create($data)));
        $this->result->addMessage('File created');

        return $this->result;
    }

    private function formatFilename($input)
    {
        $input = str_replace([' ', '/'], '_', $input);

        // Only allow alpha-num plus "_()"
        return preg_replace('/[^a-zA-Z0-9_\(\)\-]/', '', $input);
    }
}
