<?php


namespace App\Command;

use App\Service\GuzzleHttpClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'GetUserGroupReport',
    description: 'Get a report of users grouped by their groups',
)]
class GetUserGroupReportCommand extends Command
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
        $response = $client->request('GET', 'api/groups/report');

        $report = json_decode($response->getBody(), true);

        foreach ($report as $groupName => $users) {
            $output->writeln("Group: $groupName");
            foreach ($users as $user) {
                $output->writeln(" - User ID: " . $user['id'] . ", Name: " . $user['name']);
            }
        }

        return Command::SUCCESS;
    }
}
