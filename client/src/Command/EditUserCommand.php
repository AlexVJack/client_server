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
    name: 'EditUser',
    description: 'Edit a user on the server',
)]
class EditUserCommand extends Command
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
            ->addArgument('id', InputArgument::REQUIRED, 'The ID of the user')
            ->addArgument('name', InputArgument::REQUIRED, 'The new name of the user')
            ->addArgument('email', InputArgument::REQUIRED, 'The new email of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');

        $client = $this->guzzleHttpClient->getClient();

        try {
            $response = $client->request('PUT', "api/users/{$id}/edit", [
                'json' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                $output->writeln("Error editing user: " . $response->getBody());
                return Command::FAILURE;
            }

            $output->writeln("User edited successfully: " . $response->getBody());

            return Command::SUCCESS;

        } catch (GuzzleException $e) {
            $output->writeln("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
