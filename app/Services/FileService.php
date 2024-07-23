<?php

namespace App\Services;

use App\Core\Interfaces\IService;
use App\Core\Service\BaseService;

class FileService implements IService
{
    const FILE_ERROR = "file does not exist";
    const LINK_ERROR = "no link exists";

    public function list(): array
    {
        $dir = BaseService::getPath();
        $files = [];

        foreach (glob($dir . '/*') as $file) {
            if (is_file($file)) {
                $files[] = basename($file);
            }
        }

        return $files;
    }

    public function get(string $id): array
    {
        $filename = BaseService::getPath() . "/" . $id;

        if (file_exists($filename) && is_file($filename)) {
            return stat($filename);
        }

        throw new \Error(self::FILE_ERROR);
    }

    public function add(array $data): void
    {
        $file = $data["file"];

        move_uploaded_file($file["tmp_name"], BaseService::getPath() . "/" . $file["name"]);
    }

    public function rename(array $data): void
    {
        $filename = $data["filename"];
        $newFilename = $data["newFilename"];

        rename(BaseService::getPath() . "/" . $filename, BaseService::getPath() . "/" . $newFilename);
    }

    public function remove(string $id): void
    {
        unlink(BaseService::getPath() . "/" . $id);
    }

    /*
      returns a list of all users who have a link to the original file
    */
    public function shareGet(string $id): array
    {
        $pathDbDirectory = $_ENV["DIR"] . "/" . $_ENV["DB_DIRECTORY"];
        $allDbDirectories = scandir($pathDbDirectory);
        $filename = BaseService::getPath() . "/" . $id; // path to original file

        if (file_exists($filename) && is_file($filename)) {
            $authorsHasLinks = [];
            $myFolder = explode("/", BaseService::getPath());

            foreach ($allDbDirectories as $dbDirectory) {
                if (
                    in_array($dbDirectory, ['.', '..', $myFolder[count($myFolder) - 1]])
                    || !$this->authorHasSymbolLink($dbDirectory, $filename)
                ) {
                    continue;
                }

                $authorsHasLinks[] = $dbDirectory;
            }

            return $authorsHasLinks;
        }

        throw new \Error(self::FILE_ERROR);
    }

    public function shareFile(string $id, string $userId): void
    {
        $originalFile = BaseService::getPath() . "/" . $id;
        $pathOtherUserDirectory = $_ENV["DIR"] . "/" . $_ENV["DB_DIRECTORY"] . "/" . $userId;

        symlink($originalFile, $pathOtherUserDirectory . "/" . $id);
    }

    public function shareDelete(string $id, string $userId): void
    {
        $targetFile = $_ENV["DIR"] . "/" . $_ENV["DB_DIRECTORY"] . "/" . $userId . "/" . $id;

        if (is_link($targetFile)) {
            unlink($targetFile);
            return;
        }

        throw new \Error(self::LINK_ERROR);
    }

    private function authorHasSymbolLink($dir, $originalFile): bool
    {
        $includes = new \FilesystemIterator($_ENV["DIR"] . "/" . $_ENV["DB_DIRECTORY"] . "/" . $dir);

        foreach ($includes as $include) {
            if (is_dir($include) && !is_link($include) && ($include !== "." || $include !== "..")) {
                $this->authorHasSymbolLink($include, $originalFile);
            } else {
                if (is_link($include) && readlink($include) === $originalFile) {
                    return true;
                }
            }
        }

        return false;
    }
}
