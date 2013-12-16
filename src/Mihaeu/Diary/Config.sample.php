<?php

namespace Mihaeu\Diary;

class Config extends AbstractConfig
{
    public $config = [
        'some-diary' => [
            'title'         => 'Some title',
            'author'        => 'Your Nema',

            'pathToDoc'     => '../../../doc',
            'pathToEntries' => '/behind/the/whirewood/tree'
        ],

        'another-diary' => [
            'title'         => '...',
            'author'        => '...',

            'pathToDoc'     => '../../../doc',
            'pathToEntries' => 'some/secret/path'

            // executed when Config is initialized, before any other action
            // receives config as parameter
            'before'        => 'specialBeforeHook'

            // executed when Config is destructed
            // receives config as parameter
            'after'         => 'specialAfterHook'
        ]
    ];

    public static function specialBeforeHook()
    {
        // do nothing for now
    }

    public static function specialAfterHook()
    {
        // do nothing for now
    }
}
