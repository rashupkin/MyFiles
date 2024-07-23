<?php

namespace App\Core;

use App\Core\DB\IRepository;
use App\Core\Interfaces\InstanceInterface;
use App\Core\Interfaces\IService;
use InvalidArgumentException;

class App
{
    private static array $services = [];

    private static array $instantiated = [];

    public static function add(string $class, array $params): void
    {
        self::$services[$class] = $params;
    }

    public static function getService(string $class): IService
    {
        $object = self::getInstanceInterface($class);

        if (!$object instanceof IService) {
            throw new InvalidArgumentException('Could not register service: is no instance of IService');
        }

        return $object;
    }

    public static function getRepository(string $class): IRepository
    {
        $object = self::getInstanceInterface($class);

        if (!$object instanceof IRepository) {
            throw new InvalidArgumentException('Could not register repository: is no instance of IRepository');
        }

        return $object;
    }

    public static function addServices(): void
    {
        self::addDirecotoryClasses($_ENV["SERVICES_DIRECTORIES"]);
    }

    public static function addRepositories(): void
    {
        self::addDirecotoryClasses($_ENV["REPOSITORIES_DIRECTORIES"]);
    }

    private static function addInstance(string $class, InstanceInterface $service): void
    {
        self::$instantiated[$class] = $service;
    }

    private static function getInstanceInterface(string $class): InstanceInterface
    {
        if (isset(self::$instantiated[$class])) {
            return self::$instantiated[$class];
        }

        $object = new $class(...self::$services[$class]);

        self::addInstance($class, $object);

        return $object;
    }

    private static function addDirecotoryClasses(array $searchableDirectory): void
    {
        $pathToMainDirectory = $_ENV["DIR"] . "/" . $_ENV["MAIN_DIRECTORY"];

        foreach ($searchableDirectory as $directory) {
            $classes = array_slice(scandir($pathToMainDirectory . "/" . $directory), 2);

            foreach ($classes as $class) {
                require_once $pathToMainDirectory . "/" . $directory . "/" . $class;

                App::add("App\\" . str_replace("/", "\\", $directory) . "\\" . substr($class, 0, -4), []);
            }
        }
    }
}
