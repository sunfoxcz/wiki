<?php

namespace App\Presenters;

use App\Forms\WikiEditFormFactory;
use App\Libs\Config;
use Carrooi\Menu\IMenuItem;
use Nette\Application\UI\Form;


final class WikiEditPresenter extends BasePresenter
{
    /**
     * @var Config
     * @inject
     */
    public $config;

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
            $currentMenu->addItem($level, $level, function(IMenuItem $item) use ($path) {
                $item->setMenuVisibility(FALSE);
                $item->setAction('WikiEdit:default', ['page' => implode('/', $path)]);
            });
            $currentMenu = $currentMenu->getItem($level);
        }
    }

    protected function createComponentWikiEditForm(): Form
    {
        $this->wikiEditFormFactory->onSave[] = function () {
            $this->redirect('Wiki:', $this->getParameter('page'));
        };

        return $this->wikiEditFormFactory->create($this->getParameter('page'), $this->document);
    }

    public function actionDefault($page = NULL)
    {
        $file = $this->config->getPageFilePath($page);
        $this->document = is_file($file) ? file_get_contents($file) : '';
    }
}
