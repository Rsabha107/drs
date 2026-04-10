<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{

    // protected $model = Employee::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_number' => fake()->numerify('ABC-000##'),
            // 'employee_number' => fake()->numberBetween(1000,9000),
            'national_identifier_number' => fake()->ean13(),
            'salutation' => fake()->numberBetween(1, 3),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'full_name' => fake()->name(),
            'gender' => fake()->numberBetween(1, 3),
            'marital_status' => fake()->numberBetween(1, 3),
            'employee_type' => fake()->numberBetween(1, 4),
            'date_of_birth' => fake()->date(),
            'date_of_hire' => fake()->date(),
            'join_date' => fake()->date(),
            'town_of_birth' => fake()->city(),
            'country_of_birth' => fake()->numberBetween(1, 246),
            'personal_email_address' => fake()->safeEmail,
            'work_email_address' => fake()->safeEmail,
            'phone_number' => fake()->phoneNumber,
            'alt_phone_number' => fake()->phoneNumber,
            'nationality' => fake()->numberBetween(251, 499),
            'department_id' => fake()->numberBetween(11, 23),
            'designation_id' => fake()->numberBetween(6, 10),
            'department_id' => fake()->numberBetween(19, 23),
            //
        ];
    }
}
