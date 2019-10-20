<?php

namespace Nerbiz\PrivateStats\Query;

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
    public function __construct(string $key, $value, string $operator)
    {
        $this->key = $key;
        $this->value = $value;
        $this->operator = strtolower($operator);
    }

    /**
     * See if a value passes the test
     * @param mixed $value
     * @return bool
     */
    public function valuePasses($value): bool
    {
        switch ($this->operator) {
            case '=':
            case '==':
                return ($value == $this->value);
            case '===':
                return ($value === $this->value);
            case '!=':
            case '<>':
                return ($value != $this->value);
            case '!==':
                return ($value !== $this->value);
            case '>':
                return ($value > $this->value);
            case '>=':
                return ($value >= $this->value);
            case '<':
                return ($value < $this->value);
            case '<=':
                return ($value <= $this->value);
            case 'like':
                $regex = preg_quote($this->value);
                // Translate percentage signs to regex syntax
                $regex = str_replace('%', '.*', $regex);
                return (preg_match('/' . $regex . '/', $value) === 1);
        }
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
