<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'user_type',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
        query for dummy users record
        Passord for all the users ---> 12345678
        10 developers
        10 Qa's
        04 Managers
    */
    
    /*
        INSERT INTO `users` (`id`, `name`, `email`, `user_type`, `password`, `remember_token`, `created_at`, `updated_at`) 
        VALUES 
        (NULL, 'Ali Khan', 'dev1@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Sana Ahmed', 'dev2@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Imran Butt', 'dev3@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Ayesha Malik', 'dev4@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Farhan Khan', 'dev5@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Zara Abbas', 'dev6@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Kamran Ahmed', 'dev7@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Sadia Khan', 'dev8@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Ahmed Hassan', 'dev9@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Fatima Shah', 'dev10@gmail.com', 'Developer', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Khadija Akhtar', 'qa1@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Usman Mahmood', 'qa2@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Hina Shahzad', 'qa3@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Imran Ali', 'qa4@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Ayesha Siddiqui', 'qa5@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Fahad Khan', 'qa6@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Sara Ahmed', 'qa7@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Hamza Iqbal', 'qa8@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Sadia Nadeem', 'qa9@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Bilal Khan', 'qa10@gmail.com', 'QA', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'M Shahzaib Akhtar', 'm1@gmail.com', 'Manager', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Ali raza khan', 'm2@gmail.com', 'Manager', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Hina Shahzad', 'm3@gmail.com', 'Manager', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL),
        (NULL, 'Imran Ali', 'm4@gmail.com', 'Manager', '$2y$12$SexF8YWa7e9gJJ3vDBJUUecGgeC6JUNHI3H08DBmZ1VQ/x9D9fknG', NULL, NULL, NULL);
    */
}
