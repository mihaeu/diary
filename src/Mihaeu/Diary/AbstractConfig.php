<?php

namespace Mihaeu\Diary;

abstract class AbstractConfig
{
    public $slug;

    public function __construct($slug)
    {
        $this->slug = $slug;
    }

    public function __get($name)
    {
        return $this->config[$this->slug][$name];
    }
}