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
    name: 'AddUserToGroup',
    description: 'Add a user to a group on the server',
)]
class AddUserToGroupCommand extends Command
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
            ->addArgument('groupId', InputArgument::REQUIRED, 'The ID of the group')
            ->addArgument('userId', InputArgument::REQUIRED, 'The ID of the user');
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $groupId = $input->getArgument('groupId');
        $userId = $input->getArgument('userId');
        $client = $this->guzzleHttpClient->getClient();

        try {
            $response = $client->request('POST', "api/groups/{$groupId}/users/{$userId}");

            if ($response->getStatusCode() !== 200) {
                $output->writeln("Error adding user to group: " . $response->getBody());
                return Command::FAILURE;
            }

            $output->writeln("User added to group successfully.");

            return Command::SUCCESS;

        } catch (GuzzleException $e) {
            $output->writeln("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
