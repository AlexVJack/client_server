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
    name: 'CreateUser',
    description: 'Create a new user on the server',
)]
class CreateUserCommand extends Command
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
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user');
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');

        $client = $this->guzzleHttpClient->getClient();

        try {
            $response = $client->request('POST', 'api/users/new', [
                'json' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);

            if ($response->getStatusCode() !== 201) {
                $output->writeln("Error creating user: " . $response->getBody());
                return Command::FAILURE;
            }

            $output->writeln("User created: " . $response->getBody());

            return Command::SUCCESS;

        } catch (GuzzleException $e) {
            $output->writeln("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
