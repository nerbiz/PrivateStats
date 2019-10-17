<?php

namespace Nerbiz\PrivateStats\Handlers;

use DOMDocument;
use Nerbiz\PrivateStats\Collections\XmlQuery;
use Nerbiz\PrivateStats\VisitInfo;
use SimpleXMLElement;

class XmlFileHandler extends AbstractFileHandler
{
    /**
     * {@inheritdoc}
     */
    public function write(VisitInfo $visitInfo): bool
    {
        $simpleXmlElement = $this->getXmlFromFile();

        // Add an entry to the statistics
        $entry = $simpleXmlElement->addChild('entry');
        $entry->addChild('ip_hash', $visitInfo->getIpHash());
        $entry->addChild('url', $visitInfo->getUrl());
        $entry->addChild('referrer', $visitInfo->getReferrer());
        $entry->addChild('timestamp', $visitInfo->getTimestamp());
        $entry->addChild('date', $visitInfo->getDate());

        // Format with newlines and indentation
        $domDocument = new DOMDocument('1.0');
        $domDocument->preserveWhiteSpace = false;
        $domDocument->formatOutput = true;
        $domDocument->loadXML($simpleXmlElement->asXML());

        // Store the file
        $fileIsSaved = $domDocument->save($this->filePath);

        return ($fileIsSaved !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function read(): array
    {
        $allRows = [];
        $simpleXmlElement = $this->getXmlFromFile();

        foreach ($simpleXmlElement as $entry) {
            $visitInfo = (new VisitInfo())
                ->setTimestamp($entry->timestamp)
                ->setDateFromTimestamp($entry->timestamp)
                ->setIpHash($entry->ip_hash)
                ->setUrl($entry->url)
                ->setReferrer($entry->referrer);

            if ($this->keepItem($visitInfo)) {
                $allRows[] = $visitInfo;
            }
        }

        return $allRows;
    }

    /**
     * Get existing XML, or create a new document
     * @return SimpleXMLElement
     */
    protected function getXmlFromFile(): SimpleXMLElement
    {
        if (! file_exists($this->filePath)) {
            return new SimpleXMLElement(''
                . '<?xml version="1.0" encoding="UTF-8"?>'
                . '<statistics></statistics>'
            );
        } else {
            return simplexml_load_file($this->filePath);
        }
    }
}
