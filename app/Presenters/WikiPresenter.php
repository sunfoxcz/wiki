<?php declare(strict_types=1);

namespace App\Presenters;

use App\Forms\WikiEditFormFactory;
use App\Libs\Config;
use Carrooi\Menu\IMenuItem;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\DocParser;
use League\CommonMark\HtmlRenderer;
use Nette\Application\UI\Form;

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
     * @var WikiEditFormFactory
     * @inject
     */
    public $wikiEditFormFactory;

    /**
     * @var Document
     */
    private $document;

    protected function startup(): void
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
            $name = str_replace('-', '_', $level);
            $title = str_replace('_', ' ', $level);
            $currentMenu->addItem($name, $title, function (IMenuItem $item) use ($path): void {
                $item->setMenuVisibility(FALSE);
                $item->setAction('Wiki:default', ['page' => implode('/', $path)]);
            });
            $currentMenu = $currentMenu->getItem($name);
        }
    }

    /**
     * @return Form
     */
    protected function createComponentWikiEditForm()
    {
        $this->wikiEditFormFactory->onSave[] = function (): void {
            $this->redirect('Wiki:', $this->getParameter('page'));
        };

        return $this->wikiEditFormFactory->create($this->getParameter('page'), $this->document);
    }

    public function actionDefault($page = NULL, $edit = FALSE): void
    {
        $file = $this->config->getPageFilePath($page);
        if (!is_file($file)) {
            $edit = TRUE;
        }

        if ($edit) {
            $this->document = is_file($file) ? file_get_contents($file) : '';
            $this->setView('edit');
        } else {
            $this->document = $this->docParser->parse(file_get_contents($file));
        }
    }

    public function renderDefault(): void
    {
        $this->template->document = $this->htmlRenderer->renderBlock($this->document);
    }

    public function renderEdit(): void
    {
    }
}
