<?php

namespace App\Command;

use App\Service\GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'GetUsers',
    description: 'Get the list of users from the server',
)]
class GetUsersCommand extends Command
{
    private GuzzleHttpClient $guzzleHttpClient;

    public function __construct(GuzzleHttpClient $guzzleHttpClient)
    {
        parent::__construct();
        $this->guzzleHttpClient = $guzzleHttpClient;
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = $this->guzzleHttpClient->getClient();
        $response = $client->request('GET', 'api/users');

        $data = json_decode($response->getBody(), true);

        foreach ($data as $user) {
            $output->writeln(
                "User ID: " . $user['id'] .
                ", Name: " . $user['name'] .
                ", Email: " . $user['email']
            );
        }

        return Command::SUCCESS;
    }
}
