<?php

namespace Src;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use GuzzleHttp\ClientInterface;
use ZipArchive;

class NewCommand extends Command
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        parent::__construct();
    }
    public function configure()
    {
        $this->setName('new')
             ->setDescription('Create a new Laravel application')
             ->addArgument('name', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input , OutputInterface $output)
    {
        //assert the folder
        $directory = getcwd() . '/' . $input->getArgument('name');
        $this->assertApplicationDoesNotExist($directory,$output);
        //download nightly version of laravel
        $this->download($zipFile = $this->makeFileName())
              ->extract($zipFile,$directory);
        //extractzip file


        //allert the user 

        $output->writeln('<comment>Application Ready!!</comment>');
    }

    private function assertApplicationDoesNotExist($directory,OutputInterface $output)
    {
        if(is_dir($directory)){
            $output->writeln('Application already exists!');
            exit(1);
        }
    }


    private function makeFileName()
    {
        return getcwd() . '/laravel_' . md5(time().uniqid()) . '.zip';
    }

    private function download($zipFile)
    {
        //http://cabinet.laravel.com./latest.zip

      $response = $this->client->get('http://cabinet.laravel.com./latest.zip')->getBody();
      
      file_put_contents($zipFile, $response);

      return $this;
    }

    private function extract($zipFile,$directory)
    {
        $archive = new ZipArchive;

        $archive->open($zipFile);

        $archive->extractTo($directory);

        $archive->close();

        return $this;
    }
}
