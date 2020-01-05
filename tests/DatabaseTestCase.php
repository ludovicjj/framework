<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class DatabaseTestCase extends TestCase
{
    /** @var PDO */
    protected $pdo;

    /** @var Manager */
    private $manager;

    public function setUp(): void
    {
        // connect to database "memory" in sqlite by pdo for test
        $this->pdo = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // require phinx.php
        $configArray = require(dirname(__DIR__).'/phinx.php');
        // Create new environments "test" and it using my instance of PDO
        $configArray['environments']['test'] = [
            'adapter' => 'sqlite',
            'connection' => $this->pdo
        ];

        // Create config with updated phinx.php
        $config = new Config($configArray);

        // Create Manager
        $this->manager = new Manager($config, new StringInput(' '), new NullOutput());
        $this->migrateDatabase();
    }

    protected function seedDatabase(): void
    {
        // Phinx accept only array, not \stdClass
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
        $this->manager->seed('test');
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    protected function migrateDatabase(): void
    {
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
        $this->manager->migrate('test');
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
}
