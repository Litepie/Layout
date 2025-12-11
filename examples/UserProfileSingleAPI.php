<?php

/**
 * Example 1: User Profile with Single Shared API
 *
 * Scenario: Simple user profile view where all data is lightweight and related.
 * One API call loads everything: stats, profile info, recent activity.
 *
 * Benefits:
 * - Single API call reduces latency
 * - All data loaded together (good for related data)
 * - Simpler backend API structure
 * - Faster initial page load
 */

use Litepie\Form\Fields\Email;
use Litepie\Form\Fields\Select;
use Litepie\Layout\LayoutBuilder;

class UserProfileController
{
    /**
     * Get the layout structure (called once, can be cached)
     */
    public function getLayout()
    {
        $layout = LayoutBuilder::create('user', 'profile')
            // Single shared data endpoint for ALL components
            ->sharedDataUrl('/api/users/{id}/profile-data')
            ->sharedDataParams([
                'include' => 'stats,profile,recent_activity,preferences',
                'with_trends' => true,
            ])

            // Stats Section - uses nested 'data.stats' from shared response
            ->statsSection('user_stats')
            ->title('Account Overview')
            ->subtitle('Your performance metrics')
            ->icon('chart-line')
            ->useSharedData(true, 'data.stats') // Nested: data.stats
            ->columns(4)
            ->addMetric('total_posts', 'Total Posts', [
                'icon' => 'file-text',
                'format' => 'number',
                'show_trend' => true,
                'color' => 'blue',
            ])
            ->addMetric('followers', 'Followers', [
                'icon' => 'users',
                'format' => 'number',
                'show_trend' => true,
                'color' => 'green',
            ])
            ->addMetric('engagement_rate', 'Engagement', [
                'icon' => 'heart',
                'format' => 'percentage',
                'show_trend' => true,
                'color' => 'pink',
            ])
            ->addMetric('avg_response_time', 'Avg Response', [
                'icon' => 'clock',
                'format' => 'number',
                'suffix' => 'hrs',
                'show_trend' => false,
                'color' => 'purple',
            ])
            ->addAction('View Analytics', '/analytics', ['icon' => 'bar-chart'])

            // Profile Card - uses nested 'data.profile.info' from shared response
            ->cardSection('profile_card')
            ->title('Profile Information')
            ->subtitle('Your account details')
            ->icon('user-circle')
            ->useSharedData(true, 'data.profile.info') // Nested: data.profile.info
            ->variant('elevated')
            ->description('Manage your personal information and account settings')
            ->addAction('Edit Profile', '/profile/edit', ['style' => 'primary', 'icon' => 'edit'])
            ->addAction('Change Avatar', '/profile/avatar', ['style' => 'secondary', 'icon' => 'camera'])

            // Recent Activity Table - uses nested 'data.activity.recent' from shared response
            ->tableSection('recent_activity')
            ->title('Recent Activity')
            ->subtitle('Your last 10 actions')
            ->icon('activity')
            ->useSharedData(true, 'data.activity.recent') // Nested: data.activity.recent
            ->columns([
                ['key' => 'action', 'label' => 'Action', 'sortable' => false],
                ['key' => 'description', 'label' => 'Description', 'sortable' => false],
                ['key' => 'created_at', 'label' => 'Date', 'sortable' => false],
                ['key' => 'status', 'label' => 'Status', 'sortable' => false],
            ])
            ->searchable(false)
            ->sortable(false)
            ->paginated(false) // Small dataset, no pagination needed

            // Settings Form - uses nested 'data.settings.preferences' from shared response
            ->formSection('preferences')
            ->label('Preferences')
            ->description('Customize your account settings')
            ->icon('settings')
            ->useSharedData(true, 'data.settings.preferences') // Nested: data.settings.preferences
            ->columns(2)
            ->addFormFields([
                Select::make('theme')
                    ->label('Theme')
                    ->options(['light' => 'Light', 'dark' => 'Dark', 'auto' => 'Auto']),

                Select::make('language')
                    ->label('Language')
                    ->options(['en' => 'English', 'es' => 'Spanish', 'fr' => 'French']),

                Select::make('timezone')
                    ->label('Timezone')
                    ->options([
                        'UTC' => 'UTC',
                        'America/New_York' => 'Eastern Time',
                        'America/Los_Angeles' => 'Pacific Time',
                        'Europe/London' => 'London',
                    ]),

                Select::make('notification_frequency')
                    ->label('Email Notifications')
                    ->options([
                        'realtime' => 'Real-time',
                        'daily' => 'Daily Digest',
                        'weekly' => 'Weekly Summary',
                        'never' => 'Never',
                    ]),
            ])
            ->addAction('Save Changes', '/profile/preferences', ['style' => 'primary'])
            ->addAction('Reset', '/profile/preferences/reset', ['style' => 'secondary'])

            ->build();

        return response()->json($layout);
    }

    /**
     * Get all profile data in ONE API call (used by frontend)
     *
     * This endpoint returns ALL data needed for the entire page
     * Data is organized in nested structure
     */
    public function getProfileData($userId)
    {
        $user = User::findOrFail($userId);

        // All data returned in a single response with nested structure
        return response()->json([
            'data' => [
                'stats' => [
                    'total_posts' => $user->posts()->count(),
                    'total_posts_trend' => 12.5, // +12.5% vs last period
                    'followers' => $user->followers()->count(),
                    'followers_trend' => -3.2, // -3.2% vs last period
                    'engagement_rate' => $user->calculateEngagementRate(),
                    'engagement_rate_trend' => 8.7,
                    'avg_response_time' => $user->averageResponseTime(),
                    'avg_response_time_trend' => null, // No trend
                ],

                'profile' => [
                    'info' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar_url,
                        'bio' => $user->bio,
                        'location' => $user->location,
                        'website' => $user->website,
                        'joined_date' => $user->created_at->format('M d, Y'),
                        'verified' => $user->email_verified_at !== null,
                        'status' => $user->status,
                    ],
                ],

                'activity' => [
                    'recent' => $user->activities()
                        ->latest()
                        ->limit(10)
                        ->get()
                        ->map(fn ($activity) => [
                            'action' => $activity->action,
                            'description' => $activity->description,
                            'created_at' => $activity->created_at->diffForHumans(),
                            'status' => $activity->status,
                        ]),
                ],

                'settings' => [
                    'preferences' => [
                        'theme' => $user->preferences['theme'] ?? 'light',
                        'language' => $user->preferences['language'] ?? 'en',
                        'timezone' => $user->preferences['timezone'] ?? 'UTC',
                        'notification_frequency' => $user->preferences['notification_frequency'] ?? 'daily',
                    ],
                ],
            ],
        ]);
    }
}

/**
 * Frontend Implementation (React/Vue example)
 */
/*
// Helper function to extract nested data using dot notation
function getNestedValue(obj, path) {
  return path.split('.').reduce((current, key) => current?.[key], obj);
}

// 1. Fetch layout (once, cached)
const layout = await fetch('/api/layouts/user/profile').then(r => r.json());

// 2. Extract shared data URL and params
const { shared_data_url, shared_data_params } = layout;

// 3. Make SINGLE API call with user ID
const userId = 123;
const dataUrl = shared_data_url.replace('{id}', userId);
const params = new URLSearchParams(shared_data_params);
const response = await fetch(`${dataUrl}?${params}`).then(r => r.json());

// Result: response = {
//   data: {
//     stats: { total_posts: 42, followers: 128, ... },
//     profile: {
//       info: { name: "John", email: "...", ... }
//     },
//     activity: {
//       recent: [...]
//     },
//     settings: {
//       preferences: { theme: "dark", ... }
//     }
//   }
// }

// 4. Render each component with its nested data
layout.components.forEach(component => {
  if (component.use_shared_data && component.data_key) {
    // Extract nested data using dot notation
    const data = getNestedValue(response, component.data_key);
    renderComponent(component, data);
  }
});

// Examples of nested extraction:
// component.data_key = 'data.stats'
// getNestedValue(response, 'data.stats') → { total_posts: 42, followers: 128, ... }

// component.data_key = 'data.profile.info'
// getNestedValue(response, 'data.profile.info') → { name: "John", email: "...", ... }

// component.data_key = 'data.activity.recent'
// getNestedValue(response, 'data.activity.recent') → [...]

// component.data_key = 'data.settings.preferences'
// getNestedValue(response, 'data.settings.preferences') → { theme: "dark", ... }

// Components automatically get their nested data:
// - StatsSection gets response.data.stats
// - CardSection gets response.data.profile.info
// - TableSection gets response.data.activity.recent
// - FormSection gets response.data.settings.preferences
*/
