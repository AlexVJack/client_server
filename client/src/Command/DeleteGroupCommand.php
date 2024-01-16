<?php

namespace App\Command;

use App\Service\GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'DeleteGroup',
    description: 'Delete a group on the server',
)]
class DeleteGroupCommand extends Command
{
    private GuzzleHttpClient $guzzleHttpClient;

    public function __construct(GuzzleHttpClient $guzzleHttpClient)
    {
        parent::__construct();
        $this->guzzleHttpClient = $guzzleHttpClient;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'The ID of the group to delete');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        $client = $this->guzzleHttpClient->getClient();

        try {
            $response = $client->request('DELETE', "api/groups/{$id}");

            if ($response->getStatusCode() !== 204) {
                $output->writeln("Error deleting group: " . $response->getBody());
                return Command::FAILURE;
            }

            $output->writeln("Group deleted successfully.");

            return Command::SUCCESS;

        } catch (GuzzleException $e) {
            $output->writeln("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
