<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'body' => fake()->text(200),
            'completed' => fake()->boolean(),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Get predefined todos for seeding.
     */
    public function getPredefinedTodos(): array
    {
        return [
            [
                'title' => 'Buy groceries',
                'body' => 'Pick up milk, bread, eggs, and fresh vegetables from the supermarket.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'title' => 'Finish Laravel API documentation',
                'body' => 'Complete the API documentation for the Todo App using Swagger annotations.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(19),
                'updated_at' => now()->subDays(19),
            ],
            [
                'title' => 'Call the electrician',
                'body' => 'Schedule a visit for fixing the living room lights and checking the switchboard.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
            [
                'title' => 'Submit monthly report',
                'body' => 'Prepare and submit the project progress report to the manager before Friday.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(17),
                'updated_at' => now()->subDays(17),
            ],
            [
                'title' => 'Read a book',
                'body' => 'Finish reading "Atomic Habits" and write a summary of key takeaways.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(16),
                'updated_at' => now()->subDays(16),
            ],
            [
                'title' => 'Water the plants',
                'body' => 'Don’t forget to water the indoor plants and check for dead leaves.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'title' => 'Clean the garage',
                'body' => 'Organize tools, sweep the floor, and remove any unnecessary boxes.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(14),
                'updated_at' => now()->subDays(14),
            ],
            [
                'title' => 'Update portfolio website',
                'body' => 'Add recent freelance projects and improve the layout on mobile screens.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(13),
                'updated_at' => now()->subDays(13),
            ],
            [
                'title' => 'Book dentist appointment',
                'body' => 'Check availability next week and set a reminder for the scheduled time.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'title' => 'Renew domain and hosting',
                'body' => 'Login to Namecheap and renew your domain and hosting for another year.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(11),
                'updated_at' => now()->subDays(11),
            ],
            [
                'title' => 'Fix navbar bug',
                'body' => 'Resolve the mobile view dropdown bug in the main navbar of the app.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'title' => 'Plan weekend trip',
                'body' => 'Choose a destination, check hotel options, and share details with friends.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(9),
            ],
            [
                'title' => 'Write a blog post',
                'body' => 'Draft a post on Vue 3 and the Composition API for the tech blog.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'title' => 'Pay electricity bill',
                'body' => 'Use the banking app to pay the bill before the due date to avoid late fees.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'title' => 'Practice coding problems',
                'body' => 'Solve 3 problems on LeetCode focusing on dynamic programming.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'title' => 'Update resume',
                'body' => 'Add the latest job role and relevant skills to your resume PDF and LinkedIn.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Organize desk',
                'body' => 'Declutter the desk, manage cables, and clean your keyboard and monitor.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'title' => 'Push project to GitHub',
                'body' => 'Finalize the README and push the latest commits to the repository.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'title' => 'Install security updates',
                'body' => 'Update all packages and apply the latest security patches to the server.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'Watch tutorial on GraphQL',
                'body' => 'Go through the YouTube GraphQL basics playlist and take notes.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'title' => 'Prepare slides for Monday presentation',
                'body' => 'Design and finalize presentation slides for the upcoming client meeting about Q2 goals.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Update team on feature rollout',
                'body' => 'Send a Slack message summarizing the timeline and details of the new feature launch.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDay(),
                'updated_at' => now()->addDay(),
            ],
            [
                'title' => 'Reorganize email inbox',
                'body' => 'Clean up old emails, unsubscribe from newsletters, and create labels for better workflow.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDays(2),
                'updated_at' => now()->addDays(2),
            ],
            [
                'title' => 'Review pull requests',
                'body' => 'Check all open pull requests in the project repo and leave comments if needed.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDays(3),
                'updated_at' => now()->addDays(3),
            ],
            [
                'title' => 'Set up Google Analytics',
                'body' => 'Configure GA for the new marketing website to track user traffic and engagement.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDays(4),
                'updated_at' => now()->addDays(4),
            ],
            [
                'title' => 'Refactor authentication logic',
                'body' => 'Improve middleware and guard structure for better role-based access control.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDays(5),
                'updated_at' => now()->addDays(5),
            ],
            [
                'title' => 'Clean photo gallery folder',
                'body' => 'Sort and delete duplicate photos in the downloads and screenshots folder.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDays(6),
                'updated_at' => now()->addDays(6),
            ],
            [
                'title' => 'Test responsive layout',
                'body' => 'Manually test the UI across multiple screen sizes and devices for bugs.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDays(7),
                'updated_at' => now()->addDays(7),
            ],
            [
                'title' => 'Backup project files',
                'body' => 'Create a compressed backup of the project folder and upload it to Google Drive.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDays(8),
                'updated_at' => now()->addDays(8),
            ],
            [
                'title' => 'Record screen demo',
                'body' => 'Use OBS to record a walkthrough of the new app features for internal documentation.',
                'completed' => rand(0, 1),
                'user_id' => rand(1, 2),
                'created_at' => now()->addDays(9),
                'updated_at' => now()->addDays(9),
            ],
        ];
    }
}
