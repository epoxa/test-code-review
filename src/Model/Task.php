<?php

namespace App\Model;

class Task implements \JsonSerializable
{
    /**
     * @var array // It's ok. Unfortunately the project is for ancient PHP 7.2 and properties type declaration is not possible
     */
    private $_data; // No leading "_" need. It's not JavaScript nor Dart
    // This line had unvisible trailing spaces. PSR violation
    public function __construct($data) // Need array type declartion for the argument
    {
        $this->_data = $data; // No fields validation at all. Types, names and presence of fields should be checked
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->_data; // No filter for unwanted columns like **
    }
}
