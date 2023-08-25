<?php

namespace App\Command;

use App\Entity\Imported;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

// название команды - это то, что пользователь вводит после "bin/console"
#[AsCommand(name: 'import')]
class ParseFileCommand extends Command
{

    protected static $defaultName = 'import';
    private string $connection = 'pgsql';
    private int $rowsCount = 50;
    private int $lastRow = 0;
    private EntityManagerInterface | null $entityManager = null;

    public function __construct(private ManagerRegistry $doctrine)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('import')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to file')
            ->addOption('to', 't', InputOption::VALUE_OPTIONAL,'DB connection', 'postgres')
            ->addOption('rows', 'r', InputOption::VALUE_OPTIONAL, 'Rows count to flush', 50)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->connection = $input->getOption('to');
        $this->rowsCount = $input->getOption('rows');
        $path = $input->getArgument('path');

        $this->entityManager = $this->doctrine->getManager($this->connection);


        $handle = fopen(__DIR__ . '/../../../public/uploads/'.$path, "r");

        $lineNumber = 0;



        while (($raw_string = fgets($handle)) !== false) {

            $lineNumber++;

            $row = str_getcsv($raw_string);

            $output->writeln(implode(', ', $row));
//            var_dump($row);

            $import = new Imported();
            $import->setUuid($row[0]);
            $import->setCtime(new DateTime($row[1]));
            $import->setEventName($row[2]);

            $this->entityManager->persist($import);

            if($lineNumber % $this->rowsCount === 0){
                $this->entityManager->flush();
                $this->lastRow = $lineNumber;
            }

        }

        if($this->lastRow != $lineNumber){
            $this->entityManager->flush();
        }

        fclose($handle);


        $output->writeln('Done!');

        return Command::SUCCESS;
    }


}