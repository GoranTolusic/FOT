<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Services\HttpService;

class AddAuthorCommand extends Command
{
    protected static $defaultName = 'app:add-author';

    private HttpService $httpService;

    public function __construct(HttpService $httpService)
    {
        parent::__construct();
        $this->httpService = $httpService;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:add-author')
            ->setDescription('Add a new author via API using login credentials')
            ->addArgument('email', InputArgument::REQUIRED, 'Email for login')
            ->addArgument('password', InputArgument::REQUIRED, 'Password for login')
            ->addArgument('first_name', InputArgument::REQUIRED, 'First name of the author')
            ->addArgument('last_name', InputArgument::REQUIRED, 'Last name of the author')
            ->addArgument('gender', InputArgument::REQUIRED, 'Gender of the author')
            ->addArgument('birthday', InputArgument::REQUIRED, 'Birthday (YYYY-MM-DD)')
            ->addArgument('place_of_birth', InputArgument::REQUIRED, 'Place of birth')
            ->addArgument('biography', InputArgument::REQUIRED, 'Biography');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $firstName = $input->getArgument('first_name');
        $lastName = $input->getArgument('last_name');
        $gender = $input->getArgument('gender');
        $birthday = $input->getArgument('birthday');
        $placeOfBirth = $input->getArgument('place_of_birth');
        $biography = $input->getArgument('biography');

        // --- 1. Login to API ---
        try {
            $loginResp = $this->httpService->postJson('/api/v2/token', [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ]
            ]);
        } catch (\Exception $e) {
            $output->writeln('<error>Login failed: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        if ($loginResp['status'] !== 200 || empty($loginResp['body']['token_key'])) {
            $output->writeln('<error>Invalid login credentials or API error</error>');
            return Command::FAILURE;
        }

        $token = $loginResp['body']['token_key'];

        // --- 2. Prepare author payload ---
        $payload = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $gender,
            'birthday' => $birthday,
            'place_of_birth' => $placeOfBirth,
            'biography' => $biography
        ];

        // --- 3. Create author via API ---
        try {
            $response = $this->httpService->postJson('/api/v2/authors', [
                'headers' => [
                    'Authorization' => "Bearer $token",
                    'Accept' => 'application/json'
                ],
                'json' => $payload
            ]);
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to create author: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        if ($response['status'] !== 200) {
            $output->writeln('<error>Unable to create author. Status: ' . $response['status'] . '</error>');
            return Command::FAILURE;
        }

        $output->writeln('<info>Author successfully created: ' . $firstName . ' ' . $lastName . '</info>');

        return Command::SUCCESS;
    }
}
