<?php

namespace App\Forms;

use App\Libs\Config;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Nextras;

/**
 * @method onSave()
 */
final class WikiEditFormFactory
{
    use Nette\SmartObject;

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

    /**
     * @param null|string $page
     * @param string      $document
     *
     * @return Form
     */
    public function create($page, $document)
    {
        $form = new Form;
        $form->setRenderer(new Nextras\Forms\Rendering\Bs3FormRenderer)
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

    public function formSuccess(Form $form, ArrayHash $values)
    {
        $file = $this->config->getPageFilePath($values->page);
        Nette\Utils\FileSystem::write($file, $values->document);

        $this->onSave();
    }
}
