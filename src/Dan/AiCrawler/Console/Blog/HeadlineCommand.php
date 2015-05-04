<?php namespace Dan\AiCrawler\Console\Blog;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dan\AiCrawler\Scrapers\BlogScraper;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

/**
 * Test various utilities in the Symfony DomCrawler & AiCrawler Extension
 *
 * @package AiCrawler\Commands
 */
class HeadlineCommand extends Command {

    protected $sample;

    /**
     * Setup our Command
     */
    protected function configure()
    {
        $this->setName('blog:headline')
            ->setDescription('Search the DOM for an article\'s headline.')
            ->setHelp("e.g. http://www.example.com/")
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Enter a URL to inspect.',
                'http://www.example.com/'
            )
            ->addOption(
                'dump',
                'd',
                InputOption::VALUE_NONE,
                'Also, dump all the considerations.'
            );
    }

    /**
     * Run our command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $url = $input->getArgument('url');
        $dump = $input->getOption('dump');

        /**
         * Download, Scrape the Crawler, Output
         */
        try {
            $blog = new BlogScraper($url);

            $payload = $blog->setExtra("link", $url)->scrape()->choose();

            if ($payload["headline"]->count())
                $this->output($output, $dump, $payload["headline"]);
            else
                $output->writeln("Sorry, we couldn't find a headline.");
        } catch (SourceNotFoundException $e) {
            $output->writeln("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $output->writeln("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }

    /**
     * Output our data
     *
     * @param OutputInterface $output
     * @param $dump
     * @param $s
     */
    private function output(OutputInterface $output, $dump, $headlines)
    {
        if ($dump) {
            foreach ($headlines as $h)
                $output->writeln($h->nodeName() . " Score (" . number_format($h->getScoreTotal("headline"), 1) . "): \t" . regex_remove_extraneous_whitespace($h->text()));
        } else {
            $first = $headlines->first();
            $output->writeln($first->nodeName() . " Scoring " . number_format($first->getScoreTotal("headline"), 1) . " amongst " . $headlines->count() . " considerations.");
            $output->writeln(regex_remove_extraneous_whitespace($first->text()));
        }
    }

}