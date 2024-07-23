<?php

namespace App\Services;

use App\Core\Interfaces\IService;
use App\Core\Service\BaseService;

class DirectoryService implements IService
{
    const DIRECTORY_ERROR = "directory does not exist";
    const LOGGIN_ERROR = "you are not logged in";

    public function create(): void
    {
        if (!is_dir($_ENV["DIR"] . "/" . $_ENV["DB_DIRECTORY"])) {
            mkdir($_ENV["DIR"] . "/" . $_ENV["DB_DIRECTORY"]);
        }

        $path = BaseService::getPath();

        if (!is_dir($path)) {
            mkdir($path);
            return;
        }

        throw new \Error(self::DIRECTORY_ERROR);
    }

    public function add(array $data): void
    {
        $directoryName = $data["directoryName"];
        $path = BaseService::getPath() . "/" . $directoryName;

        if (!is_dir($path)) {
            mkdir($path);
            return;
        }

        throw new \Error(self::DIRECTORY_ERROR);
    }

    public function rename(array $data): void
    {
        $directoryName = $data["dirname"];
        $newDirectoryName = $data["newDirname"];
        $path = BaseService::getPath();

        rename($path . "/" . $directoryName, $path . "/" . $newDirectoryName);
    }

    public function get(string $id): array
    {
        $folder = BaseService::getPath() . "/" . $id;

        if (is_dir($folder)) {
            $files = array();

            foreach (glob($folder . '/*') as $file) {
                if (is_file($file)) {
                    $files[] = basename($file);
                }
            }

            return $files;
        }

        throw new \Error("folder does not exists");
    }

    public function delete(): void
    {
        $pathToDir = $_ENV["DIR"] . "/" . $_ENV["DB_DIRECTORY"];

        if (!is_dir($pathToDir)) {
            throw new \Error(self::LOGGIN_ERROR);
        }

        if (count(scandir($pathToDir)) === 2) {
            throw new \Error(self::LOGGIN_ERROR);
        }

        $path = BaseService::getPath();

        $this->recursiveRemoveDir($path);
    }

    public function remove(): void
    {
        $path = BaseService::getPath();
        $splitPath = explode("/", $path);

        if ($splitPath[count($splitPath) - 1] !== $_ENV["DB_DIRECTORY"]) {
            $this->recursiveRemoveDir($path);
        }
    }

    private function recursiveRemoveDir($dir): void
    {
        $includes = new \FilesystemIterator($dir);

        foreach ($includes as $include) {
            var_dump($include);
            if (is_dir($include) && !is_link($include)) {
                $this->recursiveRemoveDir($include);
            } else {
                unlink($include);
            }
        }

        rmdir($dir);
    }
}
