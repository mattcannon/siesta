<?php

/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 02.10.15
 * Time: 20:47
 */
class SiestaTester extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \siestaphp\driver\Driver
     */
    protected $driver;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @var float
     */
    protected $startTime;

    /**
     * @param string $database
     */
    protected function connectAndInstall($database)
    {
        $this->driver = \siestaphp\runtime\ServiceLocator::getDriver();
        $this->driver->query("DROP DATABASE IF EXISTS " . SIESTA_DATABASE);
        $this->driver->query("CREATE DATABASE " . SIESTA_DATABASE);
        $this->driver->useDatabase(SIESTA_DATABASE);
        $this->driver->install();
        $this->databaseName = SIESTA_DATABASE;
    }

    protected function dropDatabase()
    {
        if ($this->driver) {
            $this->driver->query("DROP DATABASE IF EXISTS " . $this->databaseName);
        }
    }

    /**
     * @param string $assetPath
     * @param string $srcXML
     * @param array $importList
     */
    protected function generateEntityFile($assetPath, $srcXML, array $importList)
    {
        $generator = new \siestaphp\generator\Generator();
        $generator->generateFile(__DIR__ . $assetPath, __DIR__ . $assetPath . $srcXML);

        foreach ($importList as $import) {
            require_once __DIR__ . $assetPath . $import;
        }

    }


    protected function startTimer() {
        $this->startTime = - microtime(true);
    }

    /**
     * @param $output
     * @param int $executionCount
     */
    protected function stopTimer($output, $executionCount = 0) {
        $delta =  ($this->startTime + microtime(true)) * 1000;
        if ($executionCount) {
            $delta /= $executionCount;
        }
        \Codeception\Util\Debug::debug(sprintf($output,$delta) );
    }
}