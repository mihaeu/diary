<?php

namespace Mihaeu\Diary;

abstract class AbstractConfig
{
    public $slug;

    public function __construct($slug)
    {   
        $this->slug = $slug;

        // execute before hook
        if (isset($this->config[$slug]['before'])) {
            call_user_func(__NAMESPACE__.'\Config::'.$this->config[$slug]['before'], $this);
        }
    }

    public function __destruct()
    {
        // execute after hook
        if (isset($this->config[$this->slug]['after'])) {
            call_user_func(__NAMESPACE__.'\Config::'.$this->config[$this->slug]['after'], $this);
        }
    }

    public function __get($name)
    {
        return $this->config[$this->slug][$name];
    }
}