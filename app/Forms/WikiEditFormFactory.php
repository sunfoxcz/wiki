<?php declare(strict_types=1);

namespace App\Forms;

use App\Libs\Config;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\FileSystem;
use Nextras\Forms\Rendering\Bs3FormRenderer;

/**
 * @method onSave()
 */
final class WikiEditFormFactory
{
    use SmartObject;

    /**
     * @var callable[]
     */
    public $onSave = [];

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function create(?string $page, string $document): Form
    {
        $form = new Form;
        $form->setRenderer(new Bs3FormRenderer)
            ->getElementPrototype()->novalidate = 'novalidate';

        $form->addHidden('page', $page);

        $form->addTextArea('document', 'Document');

        $form->addSubmit('save', 'Save');

        $form->setDefaults([
            'document' => $document,
        ]);

        $form->onSuccess[] = [$this, 'formSuccess'];

        return $form;
    }

    public function formSuccess(Form $form, ArrayHash $values): void
    {
        $file = $this->config->getPageFilePath($values->page);
        FileSystem::write($file, $values->document);

        $this->onSave();
    }
}
