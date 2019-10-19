<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\Query\ReadQuery;
use Nerbiz\PrivateStats\VisitInfo;

class JsonFileHandler extends AbstractFileHandler
{
    /**
     * {@inheritdoc}
     */
    public function write(VisitInfo $visitInfo): bool
    {
        // Add an entry to the statistics
        $jsonContents = $this->getCurrentJson();
        $jsonContents[] = $visitInfo->toArray();

        // Store the file
        file_put_contents($this->filePath, json_encode($jsonContents));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read(?ReadQuery $readQuery = null): array
    {
        $jsonContents = $this->getCurrentJson();

        // Create VisitInfo instances from the JSON items
        return array_map(function ($item) {
            return VisitInfo::fromStdClass($item);
        }, $jsonContents);
    }

    /**
     * Get existing JSON (as array), or create a new array
     * @return array
     */
    protected function getCurrentJson(): array
    {
        if (file_exists($this->filePath)) {
            $fileContents = file_get_contents($this->filePath);
            return json_decode($fileContents);
        } else {
            return [];
        }
    }
}
