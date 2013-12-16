# Diary

This is the setup I use for writing and publishing my diaries as books.

The files are in a separate place (e.g. Dropbox) in markdown format and will be converted to monthly entries fitting the Easybook generator.

Multiple diaries can be configured.

_This was not intended for public use, but I guess it can't hurt to share the way I did it._

## Usage

OK so this was written for myself, but if you still feel like giving it a try:

```
git clone https://github.com/mihaeu/diary
cd diary

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

If the result doesn't satisfy you, that's your problem :) have a look at the Bootstrapper class
and make your changes there.