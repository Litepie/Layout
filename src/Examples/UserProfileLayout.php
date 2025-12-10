<?php

namespace Litepie\Layout\Examples;

use Litepie\Layout\Facades\Layout;

class UserProfileLayout
{
    /**
     * Register the user profile layout
     */
    public static function register(): void
    {
        Layout::register('user', 'profile', function ($builder) {
            $builder
                ->section('personal_info')
                ->label('Personal Information')
                ->description('Manage your personal details')
                ->icon('user')
                ->subsection('basic')
                ->label('Basic Details')
                ->field('first_name')
                ->type('text')
                ->label('First Name')
                ->placeholder('Enter your first name')
                ->required()
                ->maxLength(50)
                ->end()
                ->field('last_name')
                ->type('text')
                ->label('Last Name')
                ->placeholder('Enter your last name')
                ->required()
                ->maxLength(50)
                ->end()
                ->field('email')
                ->type('email')
                ->label('Email Address')
                ->placeholder('your@email.com')
                ->required()
                ->end()
                ->field('phone')
                ->type('text')
                ->label('Phone Number')
                ->placeholder('+1 (555) 000-0000')
                ->end()
                ->endSubsection()
                ->subsection('address')
                ->label('Address Information')
                ->field('street')
                ->type('text')
                ->label('Street Address')
                ->maxLength(100)
                ->end()
                ->field('city')
                ->type('text')
                ->label('City')
                ->maxLength(50)
                ->end()
                ->field('state')
                ->type('select')
                ->label('State')
                ->options([
                    'CA' => 'California',
                    'NY' => 'New York',
                    'TX' => 'Texas',
                    // Add more states
                ])
                ->end()
                ->field('zip')
                ->type('text')
                ->label('ZIP Code')
                ->maxLength(10)
                ->end()
                ->endSubsection()
                ->endSection()
                ->section('account_settings')
                ->label('Account Settings')
                ->description('Configure your account preferences')
                ->icon('settings')
                ->subsection('preferences')
                ->label('Preferences')
                ->field('theme')
                ->type('select')
                ->label('Theme')
                ->options([
                    'light' => 'Light',
                    'dark' => 'Dark',
                    'auto' => 'Auto (System)',
                ])
                ->default('light')
                ->end()
                ->field('language')
                ->type('select')
                ->label('Language')
                ->options([
                    'en' => 'English',
                    'es' => 'Spanish',
                    'fr' => 'French',
                ])
                ->default('en')
                ->end()
                ->field('timezone')
                ->type('select')
                ->label('Timezone')
                ->options([
                    'America/New_York' => 'Eastern Time',
                    'America/Chicago' => 'Central Time',
                    'America/Denver' => 'Mountain Time',
                    'America/Los_Angeles' => 'Pacific Time',
                ])
                ->end()
                ->endSubsection()
                ->subsection('notifications')
                ->label('Notification Settings')
                ->field('email_notifications')
                ->type('checkbox')
                ->label('Enable Email Notifications')
                ->default(true)
                ->end()
                ->field('push_notifications')
                ->type('checkbox')
                ->label('Enable Push Notifications')
                ->default(false)
                ->end()
                ->field('notification_frequency')
                ->type('radio')
                ->label('Notification Frequency')
                ->options([
                    'realtime' => 'Real-time',
                    'daily' => 'Daily Digest',
                    'weekly' => 'Weekly Digest',
                ])
                ->default('realtime')
                ->end()
                ->endSubsection()
                ->endSection();
        });
    }

    /**
     * Get the layout for a specific user
     */
    public static function get(int $userId): ?\Litepie\Layout\Layout
    {
        return Layout::get('user', 'profile', $userId);
    }

    /**
     * Clear cache for a user
     */
    public static function clearCache(int $userId): void
    {
        Layout::clearCache('user', 'profile', $userId);
    }
}
