<?php

namespace App\Presenters;

use League\CommonMark\Block\Element\Document;
use League\CommonMark\DocParser;
use League\CommonMark\HtmlRenderer;
use Nette\Application\BadRequestException;


final class WikiPresenter extends BasePresenter
{
    /**
     * @var DocParser
     * @inject
     */
    public $docParser;

    /**
     * @var HtmlRenderer
     * @inject
     */
    public $htmlRenderer;

    /**
     * @var Document
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
        $currentMenu = $this->getMenu()->getItem('wiki');
        foreach (explode('/', $page) as $level) {
            $path[] = $level;
            $currentMenu = $currentMenu->addItem($level, 'Wiki:default', ['page' => implode('/', $path)])
                ->setVisual(FALSE);
        }
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

        $markdown = file_get_contents(__DIR__ . "/../../pages/{$page}.md");
        $this->document = $this->docParser->parse($markdown);
    }

    public function renderDefault()
    {
        $this->template->document = $this->htmlRenderer->renderBlock($this->document);
    }
}
