<?php

namespace App\Libs;

use Nette\Utils\ArrayHash;

/**
 * @property string $dataDir
 * @property string $pageDir
 * @property string $userDir
 * @property string $defaultPage
 */
final class Config extends ArrayHash
{
    /**
     * @param array $arr
     */
    public function __construct($arr)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $this->$key = ArrayHash::from($value, true);
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
