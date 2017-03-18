<?php

namespace App\Presenters;

use App\Forms\WikiEditFormFactory;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;


final class WikiEditPresenter extends BasePresenter
{
    /**
     * @var WikiEditFormFactory
     * @inject
     */
    public $wikiEditFormFactory;

    /**
     * @var string
     */
    private $document;

    protected function startup()
    {
        parent::startup();

        $page = $this->getParameter('page');
        if (!$page) {
            return;
        }

        $path = [];
        $currentMenu = $this->getMenu()->getItem('wikiEdit');
        foreach (explode('/', $page) as $level) {
            $path[] = $level;
            $currentMenu = $currentMenu->addItem($level, 'WikiEdit:default', ['page' => implode('/', $path)])
                ->setVisual(FALSE);
        }
    }

    protected function createComponentWikiEditForm(): Form
    {
        $this->wikiEditFormFactory->onSave[] = function () {
            $this->redirect('Wiki:', $this->getParameter('page'));
        };

        return $this->wikiEditFormFactory->create($this->getParameter('page', 'Wiki'), $this->document);
    }

    public function actionDefault($page = NULL)
    {
        if ($page === NULL) {
            $page = 'Wiki';
        }

        $file = __DIR__ . "/../../pages/{$page}.md";
        if (!is_file($file)) {
            throw new BadRequestException;
        }

        $this->document = file_get_contents(__DIR__ . "/../../pages/{$page}.md");
    }
}
