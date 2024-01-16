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
    name: 'EditGroup',
    description: 'Edit a group on the server',
)]
class EditGroupCommand extends Command
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
            ->addArgument('id', InputArgument::REQUIRED, 'The ID of the group')
            ->addArgument('name', InputArgument::REQUIRED, 'The new name of the group');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        $name = $input->getArgument('name');

        $client = $this->guzzleHttpClient->getClient();

        try {
            $response = $client->request('PUT', "api/groups/{$id}/edit", [
                'json' => [
                    'name' => $name,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                $output->writeln("Error editing group: " . $response->getBody());
                return Command::FAILURE;
            }

            $output->writeln("Group edited successfully: " . $response->getBody());

            return Command::SUCCESS;

        } catch (GuzzleException $e) {
            $output->writeln("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
