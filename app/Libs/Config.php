<?php declare(strict_types=1);

namespace App\Libs;

use Nette\Utils\ArrayHash;

/**
 * @property-read string $dataDir
 * @property-read string $pageDir
 * @property-read string $userDir
 * @property-read string $defaultPage
 */
final class Config extends ArrayHash
{
    public function __construct(array $arr)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $this->$key = ArrayHash::from($value, TRUE);
            } else {
                $this->$key = $value;
            }
        }
    }

    /**
     * Get Wiki page path on disk based on url path.
     */
    public function getPageFilePath(?string $wikiPath): string
    {
        if (!$wikiPath) {
            $wikiPath = $this->defaultPage;
        }

        return vsprintf('%s/%s.md', [$this->pageDir, $wikiPath]);
    }

    public function getUserFilePath(string $username): string
    {
        return vsprintf('%s/%s.neon', [$this->userDir, $username]);
    }
}
