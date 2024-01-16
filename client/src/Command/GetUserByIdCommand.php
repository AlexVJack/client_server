<?php

namespace App\Command;

use App\Service\GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'GetUserById',
    description: 'Get a user by ID from the server',
)]
class GetUserByIdCommand extends Command
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
            ->addArgument('id', InputArgument::REQUIRED, 'The ID of the user');
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        $client = $this->guzzleHttpClient->getClient();

        try {
            $response = $client->request('GET', "api/users/{$id}");

            if ($response->getStatusCode() !== 200) {
                $output->writeln("Error fetching user: " . $response->getBody());
                return Command::FAILURE;
            }

            $user = json_decode($response->getBody(), true);
            $output->writeln("User ID: " . $user['id'] . ", Name: " . $user['name'] . ", Email: " . $user['email']);

            return Command::SUCCESS;

        } catch (GuzzleException $e) {
            $output->writeln("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
