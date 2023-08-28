<?php

namespace App\Command;

use App\Parser\Fillers\Mysql;
use App\Parser\Fillers\Postgres;
use App\Parser\Fillers\Redis;
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
    private string $connection = 'pgsql';
    private int $rowsCount = 50;

    public function __construct(private Mysql $mysql, private Postgres $postgers, private Redis $redis)
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

        switch ($this->connection){
            case 'mysql':
                $parser = new \App\Parser\Parser($this->mysql);
                break;
            case 'postgres':
                $parser = new \App\Parser\Parser($this->postgers);
                break;
            case 'redis':
                $parser = new \App\Parser\Parser($this->redis);
                break;
            default:
                $parser = new \App\Parser\Parser($this->postgers);
                break;
        }

        $parser->setPerPage($this->rowsCount);

        $handle = fopen(__DIR__ . '/../../public/uploads/'.$path, "r");
//

        $generator = $this->csv_read($handle, ',');

        foreach ($generator as $item) {
            $parser->setData($item);
        }

       $parser->done();


        $output->writeln('Done!');

        return Command::SUCCESS;
    }

    function csv_read($handle, $delimeter=',')
    {
        $header = [];
        $row = 0;

        if ($handle === false) {
            return false;
        }

        while (($data = fgetcsv($handle, 0, $delimeter)) !== false) {

            yield $data;

            $row++;
        }
        fclose($handle);
    }


}