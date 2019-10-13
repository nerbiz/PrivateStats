<?php

namespace Nerbiz\PrivateStats\Handlers;

use Jenssegers\Date\Date;
use Nerbiz\PrivateStats\VisitInfo;

class CsvFileHandler extends AbstractFileHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function store(VisitInfo $visitInfo): bool
    {
        // (Try to) create the file, if it doesn't exist yet
        if (! file_exists($this->filePath)) {
            $csvHeader = implode(',', ['IP hash', 'URL', 'Referring URL', 'Timestamp', 'Date']);
            $fileIsCreated = file_put_contents($this->filePath, $csvHeader . PHP_EOL, FILE_APPEND);

            if ($fileIsCreated === false) {
                return false;
            }
        }

        // Create the CSV values, escape possible commas, by using quotes
        $csvValues = [
            $visitInfo->getIpHash(),
            '"' . $visitInfo->getUrl() . '"',
            '"' . $visitInfo->getReferringUrl() . '"',
            $visitInfo->getTimestamp(),
            Date::createFromTimestamp($visitInfo->getTimestamp())->format('Y-m-d H:i:s'),
        ];

        // Add a row to the file
        $csvRow = implode(',', $csvValues);
        $rowIsAdded = file_put_contents($this->filePath, $csvRow . PHP_EOL, FILE_APPEND);

        return ($rowIsAdded !== false);
    }
}
