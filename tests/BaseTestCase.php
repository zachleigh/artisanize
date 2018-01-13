<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Files created during tests that should be unlinked during tear down.
     *
     * @var array
     */
    protected $createdFiles = [];

    /**
     * Register a created file that should be unlinked during treardown.
     *
     * @param string $path
     *
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
            if (is_dir($path)) {
                $dir = new \DirectoryIterator($path);

                foreach ($dir as $file) {
                    if (!$file->isDot()) {
                        unlink($file->getPathname());
                    }
                }

                rmdir($path);
            } elseif (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
