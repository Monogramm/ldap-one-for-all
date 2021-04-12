<?php

namespace App\Command;

use App\Entity\Parameter;
use App\Repository\ParameterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ParameterListCommand extends Command
{
    protected static $defaultName = 'app:parameters:list';

    /**
     * @var EntityManagerInterface
     */
    private $_em;

    /**
     * @var ParameterRepository
     */
    private $_parameterRepository;

    public function __construct(
        EntityManagerInterface $em,
        ParameterRepository $parameterRepository
    ) {
        $this->_parameterRepository = $parameterRepository;
        $this->_em = $em;

        parent::__construct(self::$defaultName);
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('List parameters')
            ->addOption(
                'filters',
                'f',
                InputOption::VALUE_REQUIRED,
                'Filter criterias. Expected in JSON format: {"name": "Example"}'
            )
            ->addOption(
                'orders',
                'o',
                InputOption::VALUE_REQUIRED,
                'Order by. Expected in JSON format: {"name": "ASC"}'
            )
            ->addOption(
                'page',
                'p',
                InputOption::VALUE_REQUIRED,
                'Page number (starts at 1). Set to 0 for no pagination (page size will be ignored).',
                1
            )
            ->addOption(
                'size',
                's',
                InputOption::VALUE_REQUIRED,
                'Page size.',
                10
            )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment("List of parameters:");

        $criteria = [];
        $rawCriteria = $input->getOption('filters');
        if (!empty($rawCriteria)) {
            $criteria = json_decode($rawCriteria, true);
        }

        $orderBy = null;
        $rawOrderBy = $input->getOption('orders');
        if (!empty($rawOrderBy)) {
            $orderBy = json_decode($rawOrderBy, true);
        }

        $page = $input->getOption('page');
        $size = $input->getOption('size');
        if ($page > 0) {
            $offset = ($page - 1) * +$size;
        } else {
            $offset = null;
            $size = null;
        }

        $parameters = $this->_parameterRepository->findBy($criteria, $orderBy, $size, $offset);
        $rows = [];
        foreach ($parameters as $key => $parameter) {
            $rows[$key] = [$parameter->getId(),
                $parameter->getName(),
                $parameter->getValue()];
        }

        (new SymfonyStyle($input, $output))
            ->table(['Id', 'Name', 'Value'], $rows);

        return 0;
    }
}
