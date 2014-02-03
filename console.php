<?php
require_once('vendors/autoload.php');
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application();

$console
    ->register('eloquent')
    ->setDefinition(array(
      new InputArgument('command', InputArgument::OPTIONAL, 'What Command?'),
    ))
    ->setDescription('')
    ->setHelp('')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
      $person = $input->getArgument('person');
      $output->writeln('Hello '.$person.'');
    });

$console->run();