<?php

namespace Nerbiz\PrivateStats\Collections;

class WhereClause
{
    /**
     * The key to filter with
     * @var string
     */
    protected $key;

    /**
     * The value to filter with
     * @var mixed
     */
    protected $value;

    /**
     * The operator to use for the filtering
     * @var string
     */
    protected $operator;

    /**
     * @param string $key
     * @param mixed  $value
     * @param string $operator
     */
    public function __construct(string $key, $value, string $operator = '=')
    {
        $this->key = $key;
        $this->value = $value;
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }
}
