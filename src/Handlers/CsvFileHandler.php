<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\Collections\CsvQuery;
use Nerbiz\PrivateStats\Collections\VisitInfoCollection;
use Nerbiz\PrivateStats\VisitInfo;

class CsvFileHandler extends AbstractFileHandler
{
    /**
     * {@inheritdoc}
     */
    public function write(VisitInfo $visitInfo): bool
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
                'timestamp',
                'date',
                'ip_hash',
                'url',
                'referrer',
            ]);
        }

        // Add a row to the file
        fputcsv($fileHandle, [
            $visitInfo->getIpHash(),
            $visitInfo->getUrl(),
            $visitInfo->getReferrer(),
            $visitInfo->getTimestamp(),
            $visitInfo->getDate(),
        ]);

        fclose($fileHandle);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read(): VisitInfoCollection
    {
        $fileHandle = fopen($this->filePath, 'r');
        if ($fileHandle === false) {
            return false;
        }

        // The array of all rows from the CSV file
        $allRows = [];

        $headerRow = null;
        while (($csvRow = fgetcsv($fileHandle)) !== false) {
            // Get the keys from the header row
            if ($headerRow === null) {
                $headerRow = $csvRow;
                continue;
            }

            // Create a visit information object from the row data
            $row = array_combine($headerRow, $csvRow);
            $visitInfo = (new VisitInfo())
                ->setTimestamp((int)$row['timestamp'] ?? '')
                ->setDateFromTimestamp($row['timestamp'] ?? '')
                ->setIpHash($row['ip_hash'] ?? '')
                ->setUrl($row['url'] ?? '')
                ->setReferrer($row['referrer'] ?? '');

            $allRows[] = $visitInfo;
        }

        fclose($fileHandle);

        return new VisitInfoCollection($allRows);
    }
}
