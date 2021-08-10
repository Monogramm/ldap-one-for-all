<?php

namespace App\Command;

use App\Entity\Parameter;
use App\Repository\ParameterRepository;
use App\Service\Encryptor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ParameterSetCommand extends Command
{
    protected static $defaultName = 'app:parameters:set';

    /**
     * @var EntityManagerInterface
     */
    private $emi;

    /**
     * @var ParameterRepository
     */
    private $parameterRepository;

    /**
     * @var Encryptor
     */
    private $encryptor;

    public function __construct(
        EntityManagerInterface $emi,
        ParameterRepository $parameterRepository,
        Encryptor $encryptor
    ) {
        $this->emi = $emi;
        $this->parameterRepository = $parameterRepository;
        $this->encryptor = $encryptor;

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
            ->setDescription('Set a parameter')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Parameter name to be set. If parameter with given name does not exist, it willbe created'
            )
            ->addArgument(
                'value',
                InputArgument::REQUIRED,
                'Parameter value to be set'
            )
            ->addOption(
                'desc',
                'd',
                InputOption::VALUE_REQUIRED,
                'Parameter description. If not defined and parameter already exists, existing description will be kept.'
            )
            ->addOption(
                'type',
                't',
                InputOption::VALUE_REQUIRED,
                'Parameter type. Must be one of the following: ['
                    . Parameter::STRING_TYPE . ','
                    . Parameter::SECRET_TYPE . ','
                    . Parameter::NUMBER_TYPE
                    . ']',
                Parameter::STRING_TYPE
            )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ioStyle = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $value = $input->getArgument('value');

        $desc = $input->getOption('desc');
        $type = $input->getOption('type');

        // Checking input format
        if ($this->isInvalid($ioStyle, $name, $value, $type)) {
            return 1;
        }

        // Creating/updating parameter

        $parameter = $this->findByName($name);
        if (empty($parameter)) {
            $parameter = new Parameter($name);
        }

        $parameter
            ->setValue($value)
            ->setType($type);

        if ($parameter->isSecret()) {
            $parameter->setValue(
                $this->encryptor->encryptText(
                    $parameter->getValue()
                )
            );
        }

        if (!empty($desc)) {
            $parameter->setDescription($desc);
        }

        $this->emi->persist($parameter);
        $this->emi->flush();

        $ioStyle->success("Parameter '$name' set");

        return 0;
    }

    protected function isInvalid(SymfonyStyle $ioStyle, String $name, String $value, String $type): bool
    {
        $invalid = false;

        if (empty($name)) {
            $ioStyle->error('Name cannot be empty');
            $invalid = true;
        }
        if (empty($type)) {
            $ioStyle->error('Type cannot be empty');
            $invalid = true;
        }
        switch ($type) {
            case Parameter::STRING_TYPE:
            case Parameter::SECRET_TYPE:
                break;

            case Parameter::NUMBER_TYPE:
                // Check that value is a valid number
                if (!empty($value) && !is_numeric($value)) {
                    $ioStyle->error('The given value is not a valid number:' . $value);
                    $invalid = true;
                }
                break;

            default:
                $ioStyle->error('Unknown type:' . $type);
                $invalid = true;
                break;
        }

        return $invalid;
    }

    protected function findByName(String $name): ?Parameter
    {
        return $this->parameterRepository->findOneBy(['name' => $name]);
    }
}
