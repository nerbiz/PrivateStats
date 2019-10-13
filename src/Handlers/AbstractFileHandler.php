<?php

namespace Nerbiz\PrivateStats\Handlers;

abstract class AbstractFileHandler implements HandlerInterface
{
    /**
     * The path to the file to create/update
     * @var string
     */
    protected $filePath;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
}
