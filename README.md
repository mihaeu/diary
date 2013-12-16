# Diary

This is the setup I use for writing and publishing my diaries as books.

The files are in a separate place (e.g. Dropbox) in markdown format and will be converted to monthly entries fitting the Easybook generator.

Multiple diaries can be configured.

_This was not intended for public use, but I guess it can't hurt to share the way I did it._

## Usage

OK so this was written for myself, but if you still feel like giving it a try:

```bash
git clone https://github.com/mihaeu/diary
cd diary

# assuming Composer is installed globally, otherwise check http://getcomposer.org
composer install

# copy the Config sample and adjust
cp src/Mihaeu/Diary/Config.sample.php src/Mihaeu/Diary/Config.php
vim src/Mihaeu/Diary/Config.php

# make sure to set the path to your entry files
# expected format of the file is either
# 2014-01-01.md
# or
# 2014-01-01-author.md

# set up a new doc book
./easybook new book-slug --dir=./doc

# publish your diary as a book (output e.g. ./doc/your-book-slug/Output/print/book.bdf)
./publish book-slug
```

If the result doesn't satisfy you, that's your problem :) have a look at the `Bootstrapper` class
and make your changes there, but most of the time a `before` or `after` hook in the `Config` class will do.

## Hook Example

One of my diaries is written in three different languages for instance, one if which is Persian, which is a right-to-left language with an alphabet similar to Arabic. I use a `before` hook similar to this to achieve proper display of those entries (I use the original Markdown parser, HTML tags remain unchanged):

```php

// ... config item
[
    'before'                => 'assembleDiaryFiles'
]

// ... static method within the Config class

/**
 * Analyse and process files into a temporary directory.
 *
 * @param  Config $config
 * @return void
 */
public static function assembleDiaryFiles(Config $config)
{
    // fetch all the files from one directory
    // ...

    foreach ($files as $file) {
        $matches = [];
        // I chose to just add .fa to the filename to mark it as a Farsi entry
        // instead of using a fancy language detector
        if (preg_match('/(\d{4}-\d{2}-\d{2})(\.fa)?\.md/', $file, $matches)) {
            // pathToMyEntries is a custom Config item I set for this hook
            $source = $config->pathToMyEntries.DIRECTORY_SEPARATOR.$file;
            $destination = $config->pathToMyEntries.DIRECTORY_SEPARATOR.$matches[1].'-Farsi Author.md';;
            
            if (empty($matches[2])) {
                // document is NOT written in farsi, just copy
                copy($source, $destination);
            } else {
                // document was written in Farsi, add RTL CSS property, or class
                // to change font etc.
                $content = '<div style="direction:rtl;">'.file_get_contents($source).'</div>';
                file_put_contents($destination, $content);
            }
        }
    }

    // ...
}
```