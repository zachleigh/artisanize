<?php

namespace Tests;

use Tasks\Core\Application;
use Tasks\Core\Output\Logger;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Files created during tests that should be unlinked during tear down.
     *
     * @var array
     */
    protected $createdFiles = [];

    /**
     * Setup the test case.
     *
     * @return void
     */
    protected function setUp()
    {
        // $this->app = require __DIR__ . '/../src/Core/bootstrap.php';

        // app()->register('output', new Logger());
    }

    /**
     * Tear down after tests have run.
     *
     * @return void
     */
    protected function tearDown()
    {
        // if ($this->app) {
        //     $this->app->flush();

        //     $this->app = null;
        // }

        // $this->removeRegisteredFiles();
    }

    /**
     * Register a created file that should be unlinked during treardown.
     *
     * @param string $path
     * @return void
     */
    protected function registerCreatedFile($path)
    {
        $this->createdFiles[] = $path;
    }

    /**
     * Unlink all registered files.
     *
     * @return void
     */
    protected function removeRegisteredFiles()
    {
        foreach ($this->createdFiles as $path) {
            if (file_exists($path)) {
                if (is_dir($path)) {
                    $dir = new \DirectoryIterator($path);

                    foreach ($dir as $file) {
                        if (!$file->isDot()) {
                            unlink($file->getPathname());
                        }
                    }

                    rmdir($path);
                } else {
                    unlink($path);
                }
            }
        }
    }
}
