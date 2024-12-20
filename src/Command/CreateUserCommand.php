<?php

namespace App\Command;

use App\Entity\User; // Cambia esto al nombre de tu entidad de usuario
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Crea un nuevo usuario')
            ->setHelp('Este comando permite crear un usuario en el sistema')
            ->addArgument('email', InputArgument::REQUIRED, 'El correo electrónico del usuario')
            ->addArgument('password', InputArgument::OPTIONAL, 'La contraseña del usuario');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!$password) {
            // Usar el helper de preguntas para solicitar la contraseña
            $helper = $this->getHelper('question');
            $question = new Question('Por favor, introduce la contraseña del usuario: ');
            $question->setHidden(true); // Oculta la entrada en la consola
            $question->setHiddenFallback(false);
            $password = $helper->ask($input, $output, $question);
        }

        $output->writeln('Creando usuario...');

        // Validación básica del correo
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $output->writeln('<error>El correo electrónico no es válido.</error>');
            return Command::FAILURE;
        }

        // Comprobar si el usuario ya existe
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            $output->writeln('<error>El correo ya está registrado.</error>');
            return Command::FAILURE;
        }

        // Crear el nuevo usuario
        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>Usuario creado exitosamente.</info>');

        return Command::SUCCESS;
    }
}
