<?php

class debInjectionTest
{
    public $public = 'public';
    private $private = 'private';

    /**
     * @return string
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @param string $private
     */
    public function setPrivate($private)
    {
        $this->private = $private;
    }
}