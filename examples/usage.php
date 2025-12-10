<?php

/**
 * This file demonstrates various ways to use the Litepie Layout package
 */

require_once __DIR__.'/../vendor/autoload.php';

use Litepie\Layout\Facades\Layout;
use Litepie\Layout\Field;
use Litepie\Layout\Section;
use Litepie\Layout\Subsection;

// ==========================================
// Example 1: Simple Registration Layout
// ==========================================

Layout::register('user', 'registration', function ($builder) {
    $builder
        ->section('account')
        ->label('Account Information')
        ->subsection('credentials')
        ->field('username')
        ->type('text')
        ->label('Username')
        ->required()
        ->minLength(3)
        ->maxLength(20)
        ->end()
        ->field('email')
        ->type('email')
        ->label('Email')
        ->required()
        ->end()
        ->field('password')
        ->type('password')
        ->label('Password')
        ->required()
        ->minLength(8)
        ->end()
        ->endSubsection()
        ->endSection();
});

// Get the layout
$registrationLayout = Layout::get('user', 'registration');

// ==========================================
// Example 2: Using Fluent API
// ==========================================

$contactLayout = Layout::for('contact', 'form')
    ->section('personal')
    ->label('Personal Details')
    ->subsection('info')
    ->field('name')->type('text')->label('Full Name')->required()->end()
    ->field('email')->type('email')->label('Email')->required()->end()
    ->field('phone')->type('text')->label('Phone')->end()
    ->endSubsection()
    ->endSection()
    ->section('message')
    ->label('Your Message')
    ->subsection('content')
    ->field('subject')->type('text')->label('Subject')->required()->end()
    ->field('message')->type('textarea')->label('Message')->required()->end()
    ->endSubsection()
    ->endSection()
    ->build();

// ==========================================
// Example 3: Dynamic Layout with Options
// ==========================================

Layout::register('survey', 'feedback', function ($builder) {
    $builder
        ->section('rating')
        ->label('Rate Our Service')
        ->subsection('questions')
        ->field('satisfaction')
        ->type('radio')
        ->label('How satisfied are you?')
        ->options([
            1 => 'Very Dissatisfied',
            2 => 'Dissatisfied',
            3 => 'Neutral',
            4 => 'Satisfied',
            5 => 'Very Satisfied',
        ])
        ->required()
        ->end()
        ->field('recommend')
        ->type('select')
        ->label('Would you recommend us?')
        ->options([
            'yes' => 'Yes, definitely',
            'maybe' => 'Maybe',
            'no' => 'No',
        ])
        ->end()
        ->field('comments')
        ->type('textarea')
        ->label('Additional Comments')
        ->placeholder('Tell us more...')
        ->end()
        ->endSubsection()
        ->endSection();
});

// ==========================================
// Example 4: User-Specific Cached Layout
// ==========================================

$userId = 123;

// Get layout with user-specific caching
$userLayout = Layout::get('user', 'profile', $userId);

// Build and cache a new layout for a user
$customLayout = Layout::build('settings', 'preferences', function ($builder) {
    $builder
        ->section('display')
        ->subsection('appearance')
        ->field('theme')->type('select')->options([
                    'light' => 'Light',
                    'dark' => 'Dark',
                ])->end()
        ->endSubsection()
        ->endSection();
}, $userId);

// Clear cache for specific user
Layout::clearCache('user', 'profile', $userId);

// ==========================================
// Example 5: Accessing Layout Data
// ==========================================

$layout = Layout::get('user', 'profile');

if ($layout) {
    // Get all sections
    $sections = $layout->getSections();

    // Get specific section
    $section = $layout->getSection('personal_info');

    // Get specific subsection
    $subsection = $layout->getSubsection('personal_info', 'basic');

    // Get specific field
    $field = $layout->getField('personal_info', 'basic', 'first_name');

    // Get all fields
    $allFields = $layout->getAllFields();

    // Get field by name
    $emailField = $layout->getFieldByName('email');

    // Convert to array
    $layoutArray = $layout->toArray();

    // Render layout
    $rendered = $layout->render();
}

// ==========================================
// Example 6: Field Validation Rules
// ==========================================

$field = Field::make('email')
    ->type('email')
    ->label('Email Address')
    ->required()
    ->rules(['email', 'unique:users,email']);

// Get all validation rules
$rules = $field->getRules(); // ['required', 'email', 'unique:users,email']

// ==========================================
// Example 7: Advanced Field Configuration
// ==========================================

Layout::register('blog', 'post', function ($builder) {
    $builder
        ->section('content')
        ->subsection('post_details')
        ->field('title')
        ->type('text')
        ->label('Post Title')
        ->required()
        ->maxLength(100)
        ->attribute('data-autofocus', 'true')
        ->meta(['help_text' => 'Choose a catchy title'])
        ->end()
        ->field('content')
        ->type('textarea')
        ->label('Post Content')
        ->required()
        ->attribute('class', 'wysiwyg-editor')
        ->end()
        ->field('status')
        ->type('select')
        ->label('Status')
        ->options([
            'draft' => 'Draft',
            'published' => 'Published',
            'scheduled' => 'Scheduled',
        ])
        ->default('draft')
        ->end()
        ->field('publish_date')
        ->type('date')
        ->label('Publish Date')
        ->end()
        ->endSubsection()
        ->endSection();
});

// ==========================================
// Example 8: Cache Management
// ==========================================

// Set custom cache TTL (in seconds)
Layout::setCacheTtl(7200); // 2 hours

// Set custom cache prefix
Layout::setCachePrefix('my_app_layouts');

// Clear all user caches
Layout::clearUserCache($userId);

// Clear all layout caches
Layout::clearAllCache();

// Get fresh layout (bypass cache)
$freshLayout = Layout::fresh('user', 'profile');

// ==========================================
// Example 9: Check if Layout Exists
// ==========================================

if (Layout::has('user', 'profile')) {
    $layout = Layout::get('user', 'profile');
}

// Get all registered layouts
$registered = Layout::getRegistered();

echo "Examples completed successfully!\n";
