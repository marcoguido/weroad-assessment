<?php

namespace App\Console\Commands;

use App\Actions\User\FindUserByEmail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateNewAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:admin-user
                            {email? : User's email}
                            {name? : User's full name}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user with full administrative privileges';

    public function handle(): int
    {
        // Fetch user data from CLI arguments or ask them
        // again if invalid
        $userData = [
            'name' => $this->getNameArgument(),
            'email' => $this->getEmailArgument(),
            'password' => $this->getPasswordArgument(),
        ];

        // Store the user and assign him the default roles set
        $adminUser = (new User())->fill($userData);
        $adminUser->save();
        $adminUser
            ->roles()
            ->attach([
                Role::admin()->id,
                Role::editor()->id,
            ]);

        $this->info("User successfully created!");

        $this->getOutput()
            ->horizontalTable(
                headers: [
                    'Name',
                    'Email',
                    'Password',
                ],
                rows: [$userData],
            );

        return 0;
    }

    private function getNameArgument(): string
    {
        $providedName = trim($this->argument('name'));
        if (
            strlen($providedName) >= 3
            && strlen($providedName) < 255
        ) {
            return $providedName;
        }

        if (empty($providedName)) {
            $this->warn("No name was provided.");
        } else {
            $this->error("Provided name is not valid, try again");
        }

        return text(
            label: "What is user's full name?",
            required: true,
            validate: fn(string $value) => match (true) {
                strlen($value) < 3 => 'The name must be at least 3 characters.',
                strlen($value) > 255 => 'The name must not exceed 255 characters.',
                default => null
            },
        );
    }

    private function getEmailArgument(): string
    {
        $providedEmail = trim($this->argument('email'));
        $existingUserWithGivenEmail = (new FindUserByEmail())->execute($providedEmail);

        if (
            null === $existingUserWithGivenEmail
            && false !== filter_var($providedEmail, FILTER_VALIDATE_EMAIL)
            && strlen($providedEmail) < 255
        ) {
            return $providedEmail;
        }

        if (empty($providedEmail)) {
            $this->warn("No email was provided.");
        } else {
            $this->error("Provided email is not valid, try again");
        }

        return text(
            label: "What is user's email address?",
            required: true,
            validate: function (string $value) {
                $existingUser = (new FindUserByEmail())->execute($value);

                if (null !== $existingUser) {
                    return 'This email address is already taken, try again';
                }

                return match (true) {
                    (false === filter_var($value, FILTER_VALIDATE_EMAIL)) => "This email address looks invalid, check it out",
                    strlen($value) > 255 => "The email must not exceed 255 characters.",
                    default => null,
                };
            },
        );
    }

    private function getPasswordArgument(): string
    {
        return password(
            label: "What will user's password be?",
            required: true,
            validate: fn(string $value) => match (true) {
                strlen($value) < 8 => "The password must be at least 8 characters long.",
                default => null
            },
            hint: 'Password should be at least 8 characters long'
        );
    }
}
