<?php

namespace Nerbiz\PrivateStats\Handlers;

use DOMDocument;
use Nerbiz\PrivateStats\Collections\VisitInfoCollection;
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
        // Get existing XML, or create a new document
        if (! file_exists($this->filePath)) {
            $simpleXmlElement = new SimpleXMLElement(''
                . '<?xml version="1.0" encoding="UTF-8"?>'
                . '<statistics></statistics>'
            );
        } else {
            $simpleXmlElement = simplexml_load_file($this->filePath);
        }

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
    public function read(): VisitInfoCollection
    {
        return new VisitInfoCollection([]);
    }
}
