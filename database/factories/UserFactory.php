<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = ['male', 'female', 'other'];
        $gender = $this->faker->randomElement($genders);
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => fake()->phoneNumber(),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'gender' => $gender,
            'shipping_address' => fake()->address(),
            'billing_address' => fake()->address(),
            'preferred_payment_method' => fake()->randomElement(['card', 'paypal', 'bank_transfer']),
            'newsletter_subscription' => fake()->boolean(70), // 70% de chance d'être abonné
            'total_spent' => fake()->randomFloat(2, 0, 5000),
            'orders_count' => fake()->numberBetween(0, 20),
            'last_order_at' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is male.
     */
    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'male',
        ]);
    }

    /**
     * Indicate that the user is female.
     */
    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'female',
        ]);
    }

    /**
     * Indicate that the user is subscribed to newsletter.
     */
    public function newsletterSubscribed(): static
    {
        return $this->state(fn (array $attributes) => [
            'newsletter_subscription' => true,
        ]);
    }

    /**
     * Indicate that the user is not subscribed to newsletter.
     */
    public function newsletterUnsubscribed(): static
    {
        return $this->state(fn (array $attributes) => [
            'newsletter_subscription' => false,
        ]);
    }
}
