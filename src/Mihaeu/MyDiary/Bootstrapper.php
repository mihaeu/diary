<?php

namespace Mihaeu\MyDiary;

class Bootstrapper
{
    private $twig;

    public function __construct()
    {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__));
    }

    public function createBookConfig()
    {
        $bookConfig = $this->twig->render('config.yaml.twig');
        file_put_contents('', $bookConfig);
    }
}