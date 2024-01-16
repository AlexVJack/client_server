<?php

namespace App\Command;

use App\Service\GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'GetGroups',
    description: 'List all groups from the server',
)]
class GetGroupsCommand extends Command
{
    private GuzzleHttpClient $guzzleHttpClient;

    public function __construct(GuzzleHttpClient $guzzleHttpClient)
    {
        parent::__construct();
        $this->guzzleHttpClient = $guzzleHttpClient;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = $this->guzzleHttpClient->getClient();

        try {
            $response = $client->request('GET', 'api/groups/');

            if ($response->getStatusCode() !== 200) {
                $output->writeln("Error fetching groups: " . $response->getBody());
                return Command::FAILURE;
            }

            $groups = json_decode($response->getBody(), true);
            foreach ($groups as $group) {
                $output->writeln("Group ID: " . $group['id'] . ", Name: " . $group['name']);
            }

            return Command::SUCCESS;

        } catch (GuzzleException $e) {
            $output->writeln("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
