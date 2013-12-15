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
        ]
    ];
}
