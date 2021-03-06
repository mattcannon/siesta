<?php

namespace siestaphp\generator;

use Psr\Log\LoggerInterface;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\ConnectionFactory;
use siestaphp\util\File;
use siestaphp\xmlbuilder\XMLEntityBuilder;

/**
 * Class ReverseGenerator
 * @package siestaphp\generator
 */
class ReverseGenerator
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ReverseGeneratorConfig $config
     * @return void
     */
    public function generateXML(ReverseGeneratorConfig $config)
    {
        $connection = ConnectionFactory::getConnection($config->getConnectionName());

        $entitySourceList = $connection->getEntitySourceList($config->getTargetNamespace(), $config->getTargetPath());
        foreach ($entitySourceList as $entitySource) {
            $this->generateXMLFiles($entitySource, $config->getTargetPath());
        }
    }

    /**
     * @param EntitySource $entitySource
     * @param $targetPath
     *
     * @return void
     */
    private function generateXMLFiles(EntitySource $entitySource, $targetPath)
    {
        $targetDir = new File($targetPath);
        $targetDir->createDir();

        $entityBuilder = new XMLEntityBuilder($entitySource);

        $this->logger->info("Found " . $entitySource->getTable());

        $domDocument = $entityBuilder->getDOMDocument();

        $domDocument->formatOutput = true;

        $domDocument->save($targetPath . "/" . $entitySource->getClassName() . ".entity.xml");
    }

}