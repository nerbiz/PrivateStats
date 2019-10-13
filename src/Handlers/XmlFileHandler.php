<?php

namespace Nerbiz\PrivateStats\Handlers;

use DOMDocument;
use Jenssegers\Date\Date;
use Nerbiz\PrivateStats\VisitInfo;
use SimpleXMLElement;

class XmlFileHandler extends AbstractFileHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function store(VisitInfo $visitInfo): bool
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
        $entry->addChild('referring_url', $visitInfo->getReferringUrl());
        $entry->addChild('timestamp', $visitInfo->getTimestamp());
        $entry->addChild('date', Date::createFromTimestamp($visitInfo->getTimestamp())->format('Y-m-d H:i:s'));

        // Format with newlines and indentation
        $domDocument = new DOMDocument('1.0');
        $domDocument->preserveWhiteSpace = false;
        $domDocument->formatOutput = true;
        $domDocument->loadXML($simpleXmlElement->asXML());

        // Store the file
        $fileIsSaved = $domDocument->save($this->filePath);

        return ($fileIsSaved !== false);
    }
}
