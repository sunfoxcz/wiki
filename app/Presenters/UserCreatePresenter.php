<?php declare(strict_types=1);

namespace App\Presenters;

use App\Forms\UserCreateFormFactory;
use App\Libs\Config;
use FilesystemIterator;
use Nette\Application\UI\Form;

final class UserCreatePresenter extends BasePresenter
{
    /**
     * @var Config
     * @inject
     */
    public $config;

    /**
     * @var UserCreateFormFactory
     * @inject
     */
    public $userCreateFormFactory;

    /**
     * @return Form
     */
    protected function createComponentUserCreateForm()
    {
        $this->userCreateFormFactory->onCreate[] = function ($identity): void {
            $this->getUser()->login($identity);
            $this->redirect('Wiki:');
        };

        return $this->userCreateFormFactory->create();
    }

    /**
     * @param \Nette\Application\UI\ComponentReflection $element
     */
    public function checkRequirements($element): void
    {
        $restricted = TRUE;

        if (!is_dir($this->config->userDir)) {
            $restricted = FALSE;
        } else {
            $it = new FilesystemIterator($this->config->userDir, FilesystemIterator::SKIP_DOTS);
            if (!iterator_count($it)) {
                $restricted = FALSE;
            }
        }

        if ($restricted) {
            parent::checkRequirements($element);
        }
    }
}
