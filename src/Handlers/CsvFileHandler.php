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
        $fileIsNew = (! file_exists($this->filePath));

        // Open the file, implicitly try to create it, if it doesn't exist
        $fileHandle = fopen($this->filePath, 'a');
        if ($fileHandle === false) {
            return false;
        }

        // Add a header row, if the file is newly created
        if ($fileIsNew) {
            fputcsv($fileHandle, [
                'IP hash',
                'URL',
                'Referring URL',
                'Timestamp',
                'Date'
            ]);
        }

        // Add a row to the file
        fputcsv($fileHandle, [
            $visitInfo->getIpHash(),
            $visitInfo->getUrl(),
            $visitInfo->getReferringUrl(),
            $visitInfo->getTimestamp(),
            Date::createFromTimestamp($visitInfo->getTimestamp())->format('Y-m-d H:i:s'),
        ]);

        fclose($fileHandle);

        return true;
    }
}
