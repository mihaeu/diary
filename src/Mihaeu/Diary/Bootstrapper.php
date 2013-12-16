<?php

namespace Mihaeu\Diary;

/**
 * Bootstrapper
 * 
 * Bootstraps book publishing by transforming and copying all the entries
 * from a (secret) destination to Easybook and creates the book configuration.
 * 
 * @author  Michael Haeuslmann <haeuslmann@gmail.com>
 * @package Mihaeu\Diary\Bootstrapper
 */
class Bootstrapper
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $docPath;

    /**
     * @var array
     */
    private $entries; 

    /**
     * Sets up config, pathing and templating.
     * 
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->docPath = realpath(__DIR__.DIRECTORY_SEPARATOR.$config->pathToDoc.DIRECTORY_SEPARATOR.$config->slug);
        if (!file_exists($this->docPath)
            || !is_dir($this->docPath)
            || !is_writable($this->docPath)) {
            throw new \Exception('Path to document does not exist: '.$this->docPath);
        }

        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__));
    }

    /**
     * Creates the book configuration file for Easybook using information from the
     * entries in markdown file.
     * 
     * @return void
     */
    public function createBookConfig()
    {
        $bookConfig = $this->twig->render('config.yml.twig', [
            'title' => $this->config->title,
            'author' => $this->config->author,
            'entries' => $this->entries
        ]);
        file_put_contents($this->docPath.DIRECTORY_SEPARATOR.'config.yml', $bookConfig);
    }

    /**
     * Cleans the output directory.
     * 
     * @param  strin|null $path
     * @return void
     */
    public function cleanOldContent($path = null)
    {
        if ($path === null) {
            $path = $this->docPath.DIRECTORY_SEPARATOR.'Contents';
        }

        if (!is_dir($path)) {
            return;
        }

        foreach (array_diff(scandir($path), ['.', '..']) as $file) {
            $file = $path.DIRECTORY_SEPARATOR.$file;
            if (is_file($file)) {
                // delete file
                unlink($file);
            } else {
                // recursively delete files in folder than folder
                $this->cleanOldContent($file);
                rmdir($file);
            }
        }
    }

    /**
     * Transforms entries and copies them to Easybook.
     * 
     * @return void
     */
    public function createNewContent()
    {
        $files = array_diff(scandir($this->config->pathToEntries), ['.', '..']);
        foreach ($files as $file) {
            $this->parseDailyEntry($file);
        }

        foreach ($this->entries as $month => $monthlyEntries) {
            $this->writeParsedEntriesPerMonth($month, $monthlyEntries);
        }
    }

    /**
     * Parses daily entries and adds them to the entry container.
     * 
     * @param  string $file
     * @return void
     */
    public function parseDailyEntry($file)
    {
        $matches = [];
        if (preg_match('/(\d{4}-\d{2}-\d{2})-?([\w\d \-_]+)?\.md/', $file, $matches)) {
            // daily entry
            $datetime = \DateTime::createFromFormat('Y-m-d', $matches[1]);
            $author = isset($matches[2]) ? ' ('.$matches[2].')' : '';
            $this->entries[$datetime->format('F Y')][$datetime->format('jS F Y')] = [
                'date'   => $datetime->format('jS F Y'),
                'file'   => $file,
                'author' => $author
            ];
        } else {
            printf("Skipping malformed file (expected Y-m-d.md or Y-m-d-author.md) %s\n", $file);
        }
    }

    /**
     * Writes the parsed entries for each month to a single file.
     * 
     * @param  string $month
     * @param  array  $monthlyEntries
     * @return void
     */
    public function writeParsedEntriesPerMonth($month, $monthlyEntries) {
        $entryContents = [];
        foreach ($monthlyEntries as $dailyEntry) {
            $source = $this->config->pathToEntries.DIRECTORY_SEPARATOR.$dailyEntry['file'];
            $entryContents[] = sprintf(
                "## %s%s\n\n%s\n\n",
                $dailyEntry['date'],
                $dailyEntry['author'],
                file_get_contents($source)
            );
        }
        
        $destination = $this->docPath.DIRECTORY_SEPARATOR.'Contents'
            .DIRECTORY_SEPARATOR.$month.'.md';
        file_put_contents($destination, implode('', $entryContents));
    }
}
