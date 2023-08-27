<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\MessageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:message')]
class MessageCommand extends Command
{
    public function __construct(private readonly MessageService $messageService)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Sends rpc call to other service.')
            ->addArgument('value', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $value = (int)$input->getArgument('value');

        $this->messageService->send($value);

        return self::SUCCESS;
    }
}
