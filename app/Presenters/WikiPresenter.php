<?php

namespace App\Presenters;

use App\Libs\Config;
use Carrooi\Menu\IMenuItem;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\DocParser;
use League\CommonMark\HtmlRenderer;


final class WikiPresenter extends BasePresenter
{
    /**
     * @var Config
     * @inject
     */
    public $config;

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
            $currentMenu->addItem($level, $level, function(IMenuItem $item) use ($path) {
                $item->setMenuVisibility(FALSE);
                $item->setAction('Wiki:default', ['page' => implode('/', $path)]);
            });
            $currentMenu = $currentMenu->getItem($level);
        }
    }

    public function actionDefault($page = NULL)
    {
        $file = $this->config->getPageFilePath($page);
        if (!is_file($file)) {
            $this->redirect('WikiEdit:', $page);
        }

        $markdown = file_get_contents($file);
        $this->document = $this->docParser->parse($markdown);
    }

    public function renderDefault()
    {
        $this->template->document = $this->htmlRenderer->renderBlock($this->document);
    }
}
