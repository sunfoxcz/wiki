<?php declare(strict_types=1);

namespace App\Presenters;

use App\Forms\WikiEditFormFactory;
use App\Libs\Config;
use Contributte\MenuControl\IMenuItem;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContentInterface;
use Nette\Application\UI\Form;

final class WikiPresenter extends BasePresenter
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var MarkdownConverter
     */
    private $markdownConverter;

    /**
     * @var WikiEditFormFactory
     */
    private $wikiEditFormFactory;

    /**
     * @var RenderedContentInterface
     */
    private $document;

    /**
     * @var string
     */
    private $documentContent;

    public function __construct(
        Config $config,
        MarkdownConverter $markdownConverter,
        WikiEditFormFactory $wikiEditFormFactory
    ) {
        parent::__construct();
        $this->config = $config;
        $this->markdownConverter = $markdownConverter;
        $this->wikiEditFormFactory = $wikiEditFormFactory;
    }

    public function actionDefault(?string $page = NULL, bool $edit = FALSE): void
    {
        $file = $this->config->getPageFilePath($page);
        if (!is_file($file)) {
            $edit = TRUE;
        }

        if ($edit) {
            $this->documentContent = is_file($file) ? file_get_contents($file) : '';
            $this->setView('edit');
        } else {
            $this->document = $this->markdownConverter->convert(file_get_contents($file));
        }
    }

    public function renderDefault(): void
    {
        $this->template->document = $this->document;
    }

    public function renderEdit(): void
    {
    }

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

    protected function createComponentWikiEditForm(): Form
    {
        $this->wikiEditFormFactory->onSave[] = function (): void {
            $this->redirect('Wiki:', $this->getParameter('page'));
        };

        return $this->wikiEditFormFactory->create($this->getParameter('page'), $this->documentContent);
    }
}
