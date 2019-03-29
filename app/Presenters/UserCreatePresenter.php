<?php declare(strict_types=1);

namespace App\Presenters;

use App\Forms\UserCreateFormFactory;
use App\Libs\Config;
use FilesystemIterator;
use Nette\Application\AbortException;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\ComponentReflection;
use Nette\Application\UI\Form;

final class UserCreatePresenter extends BasePresenter
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var UserCreateFormFactory
     */
    private $userCreateFormFactory;

    public function __construct(Config $config, UserCreateFormFactory $userCreateFormFactory)
    {
        parent::__construct();
        $this->config = $config;
        $this->userCreateFormFactory = $userCreateFormFactory;
    }

    protected function createComponentUserCreateForm(): Form
    {
        $this->userCreateFormFactory->onCreate[] = function ($identity): void {
            $this->getUser()->login($identity);
            $this->redirect('Wiki:');
        };

        return $this->userCreateFormFactory->create();
    }

    /**
     * @param ComponentReflection $element
     *
     * @throws ForbiddenRequestException
     * @throws AbortException
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
