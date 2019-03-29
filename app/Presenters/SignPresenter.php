<?php declare(strict_types=1);

namespace App\Presenters;

use App\Forms\SignInFormFactory;
use App\Libs\Config;
use FilesystemIterator;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;

final class SignPresenter extends BasePresenter
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var SignInFormFactory
     */
    private $signInFormFactory;

    public function __construct(Config $config, SignInFormFactory $signInFormFactory)
    {
        parent::__construct();
        $this->config = $config;
        $this->signInFormFactory = $signInFormFactory;
    }

    public function createComponentSignInForm(): Form
    {
        $this->signInFormFactory->onLoggedIn[] = function (): void {
            $this->redirect('Wiki:');
        };
        return $this->signInFormFactory->create();
    }

    /**
     * @throws AbortException
     */
    public function actionIn(): void
    {
        if (!is_dir($this->config->userDir)) {
            $this->redirect('UserCreate:');
        }

        $it = new FilesystemIterator($this->config->userDir, FilesystemIterator::SKIP_DOTS);
        if (!iterator_count($it)) {
            $this->redirect('UserCreate:');
        }
    }

    /**
     * @throws AbortException
     */
    public function actionOut(): void
    {
        $this->getUser()->logout(TRUE);
        $this->redirect('Sign:in');
    }
}
