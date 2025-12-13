<?php

/**
 * Wizard Example
 *
 * Demonstrates creating a multi-step wizard for user onboarding.
 */

use Litepie\Layout\Facades\Layout;

// Create wizard layout
$layout = Layout::create('user-onboarding-wizard')
    ->title('User Onboarding')
    ->setSharedData([
        'user_id' => auth()->id(),
        'wizard_data' => session('wizard_data', []),
    ])

    // Header section
    ->section('header', function ($section) {
        $section->text('title')
            ->content('# Welcome! Let\'s get you set up')
            ->format('markdown')
            ->align('center');

        $section->text('subtitle')
            ->content('Complete the steps below to finish setting up your account')
            ->align('center')
            ->meta(['color' => 'muted']);
    })

    // Main wizard section
    ->section('main', function ($section) {
        $section->wizard('onboarding-wizard')

            // ====================
            // STEP 1: Account Setup
            // ====================
            ->addStep('account', 'Account Setup', function ($step) {
                $step->alert('step-info')
                    ->content('Set up your account credentials')
                    ->variant('info')
                    ->icon('user');

                $step->form('account-form')
                    ->action('/onboarding/account')
                    ->method('POST')

                    // Username
                    ->addField('username', 'text', 'Username', [
                        'placeholder' => 'Choose a unique username',
                        'required' => true,
                        'help' => 'This will be your public display name',
                    ])

                    // Email
                    ->addField('email', 'email', 'Email Address', [
                        'placeholder' => 'your@email.com',
                        'required' => true,
                        'help' => 'We\'ll send a verification email',
                    ])

                    // Password
                    ->addField('password', 'password', 'Password', [
                        'required' => true,
                        'help' => 'Must be at least 8 characters',
                    ])

                    // Confirm password
                    ->addField('password_confirmation', 'password', 'Confirm Password', [
                        'required' => true,
                    ])

                    // Buttons
                    ->addButton('next', 'Continue to Profile →', 'submit')

                    // Validation
                    ->validationRules([
                        'username' => 'required|min:3|max:50|unique:users|alpha_dash',
                        'email' => 'required|email|unique:users|max:255',
                        'password' => 'required|min:8|confirmed',
                    ]);
            })

            // ====================
            // STEP 2: Profile Information
            // ====================
            ->addStep('profile', 'Profile Details', function ($step) {
                $step->alert('step-info')
                    ->content('Tell us about yourself')
                    ->variant('info')
                    ->icon('edit');

                $step->form('profile-form')
                    ->action('/onboarding/profile')
                    ->method('POST')
                    ->enctype('multipart/form-data')

                    // Profile photo
                    ->addField('avatar', 'file', 'Profile Photo', [
                        'accept' => 'image/png,image/jpeg,image/jpg',
                        'help' => 'Upload a profile picture (PNG, JPG)',
                    ])

                    // First name
                    ->addField('first_name', 'text', 'First Name', [
                        'placeholder' => 'John',
                        'required' => true,
                    ])

                    // Last name
                    ->addField('last_name', 'text', 'Last Name', [
                        'placeholder' => 'Doe',
                        'required' => true,
                    ])

                    // Phone
                    ->addField('phone', 'tel', 'Phone Number', [
                        'placeholder' => '+1 (555) 123-4567',
                    ])

                    // Bio
                    ->addField('bio', 'textarea', 'Bio', [
                        'placeholder' => 'Tell us about yourself...',
                        'rows' => 4,
                        'help' => 'Share a brief description about yourself',
                    ])

                    // Country
                    ->addField('country', 'select', 'Country', [
                        'required' => true,
                        'options' => [
                            'US' => 'United States',
                            'CA' => 'Canada',
                            'UK' => 'United Kingdom',
                            'AU' => 'Australia',
                            'DE' => 'Germany',
                            'FR' => 'France',
                            'JP' => 'Japan',
                            'other' => 'Other',
                        ],
                    ])

                    // Buttons
                    ->addButton('back', '← Back', 'button')
                    ->addButton('next', 'Continue to Preferences →', 'submit')

                    // Validation
                    ->validationRules([
                        'avatar' => 'nullable|image|max:2048',
                        'first_name' => 'required|max:100',
                        'last_name' => 'required|max:100',
                        'phone' => 'nullable|regex:/^[0-9+\-\s()]+$/',
                        'bio' => 'nullable|max:500',
                        'country' => 'required',
                    ]);
            })

            // ====================
            // STEP 3: Preferences
            // ====================
            ->addStep('preferences', 'Preferences', function ($step) {
                $step->alert('step-info')
                    ->content('Customize your experience')
                    ->variant('info')
                    ->icon('settings');

                $step->form('preferences-form')
                    ->action('/onboarding/preferences')
                    ->method('POST')

                    // Language
                    ->addField('language', 'select', 'Language', [
                        'required' => true,
                        'default' => 'en',
                        'options' => [
                            'en' => 'English',
                            'es' => 'Spanish (Español)',
                            'fr' => 'French (Français)',
                            'de' => 'German (Deutsch)',
                            'ja' => 'Japanese (日本語)',
                            'zh' => 'Chinese (中文)',
                        ],
                    ])

                    // Timezone
                    ->addField('timezone', 'select', 'Timezone', [
                        'required' => true,
                        'default' => 'America/New_York',
                        'options' => [
                            'America/New_York' => 'Eastern Time (ET)',
                            'America/Chicago' => 'Central Time (CT)',
                            'America/Denver' => 'Mountain Time (MT)',
                            'America/Los_Angeles' => 'Pacific Time (PT)',
                            'Europe/London' => 'London (GMT)',
                            'Europe/Paris' => 'Paris (CET)',
                            'Asia/Tokyo' => 'Tokyo (JST)',
                            'Australia/Sydney' => 'Sydney (AEDT)',
                        ],
                    ])

                    // Theme
                    ->addField('theme', 'radio', 'Theme', [
                        'required' => true,
                        'default' => 'auto',
                        'options' => [
                            'light' => 'Light',
                            'dark' => 'Dark',
                            'auto' => 'Auto (System)',
                        ],
                    ])

                    // Email notifications
                    ->addField('notifications_email', 'checkbox', 'Email Notifications', [
                        'default' => true,
                        'help' => 'Receive important updates via email',
                    ])

                    // SMS notifications
                    ->addField('notifications_sms', 'checkbox', 'SMS Notifications', [
                        'default' => false,
                        'help' => 'Receive alerts via text message',
                    ])

                    // Newsletter
                    ->addField('newsletter', 'checkbox', 'Newsletter Subscription', [
                        'default' => false,
                        'help' => 'Stay updated with our latest news and features',
                    ])

                    // Marketing emails
                    ->addField('marketing', 'checkbox', 'Marketing Emails', [
                        'default' => false,
                        'help' => 'Receive promotional offers and tips',
                    ])

                    // Buttons
                    ->addButton('back', '← Back', 'button')
                    ->addButton('next', 'Continue to Interests →', 'submit')

                    // Validation
                    ->validationRules([
                        'language' => 'required',
                        'timezone' => 'required',
                        'theme' => 'required|in:light,dark,auto',
                    ]);
            })

            // ====================
            // STEP 4: Interests
            // ====================
            ->addStep('interests', 'Interests', function ($step) {
                $step->alert('step-info')
                    ->content('Select your areas of interest')
                    ->variant('info')
                    ->icon('heart');

                $step->form('interests-form')
                    ->action('/onboarding/interests')
                    ->method('POST')

                    // Primary interest
                    ->addField('primary_interest', 'select', 'Primary Interest', [
                        'required' => true,
                        'placeholder' => 'Select your main interest',
                        'options' => [
                            'technology' => 'Technology',
                            'business' => 'Business',
                            'design' => 'Design',
                            'marketing' => 'Marketing',
                            'development' => 'Development',
                            'data_science' => 'Data Science',
                            'other' => 'Other',
                        ],
                    ])

                    // Additional interests (multiple)
                    ->addField('interests', 'checkbox', 'Additional Interests', [
                        'help' => 'Select all that apply',
                        'options' => [
                            'ai' => 'Artificial Intelligence',
                            'cloud' => 'Cloud Computing',
                            'security' => 'Cybersecurity',
                            'mobile' => 'Mobile Development',
                            'web' => 'Web Development',
                            'devops' => 'DevOps',
                            'blockchain' => 'Blockchain',
                            'iot' => 'Internet of Things',
                        ],
                    ])

                    // Experience level
                    ->addField('experience_level', 'radio', 'Experience Level', [
                        'required' => true,
                        'options' => [
                            'beginner' => 'Beginner',
                            'intermediate' => 'Intermediate',
                            'advanced' => 'Advanced',
                            'expert' => 'Expert',
                        ],
                    ])

                    // Buttons
                    ->addButton('back', '← Back', 'button')
                    ->addButton('next', 'Review & Complete →', 'submit')

                    // Validation
                    ->validationRules([
                        'primary_interest' => 'required',
                        'experience_level' => 'required',
                    ]);
            })

            // ====================
            // STEP 5: Confirmation
            // ====================
            ->addStep('confirm', 'Review & Confirm', function ($step) {
                $step->alert('almost-done')
                    ->content('Almost done! Please review your information.')
                    ->variant('success')
                    ->icon('check-circle');

                // Summary card
                $step->card('summary')
                    ->title('Registration Summary')
                    ->icon('clipboard')
                    ->dataSource(function () {
                        return session('wizard_data', []);
                    })
                    ->addField('username', 'Username')
                    ->addField('email', 'Email')
                    ->addField('full_name', 'Full Name')
                    ->addField('country', 'Country')
                    ->addField('language', 'Language')
                    ->addField('timezone', 'Timezone')
                    ->addField('theme', 'Theme')
                    ->addField('primary_interest', 'Primary Interest')
                    ->addField('experience_level', 'Experience Level');

                // Terms acceptance form
                $step->form('confirm-form')
                    ->action('/onboarding/complete')
                    ->method('POST')

                    // Terms of service
                    ->addField('terms', 'checkbox', 'I agree to the Terms of Service', [
                        'required' => true,
                    ])

                    // Privacy policy
                    ->addField('privacy', 'checkbox', 'I agree to the Privacy Policy', [
                        'required' => true,
                    ])

                    // Age confirmation
                    ->addField('age_confirm', 'checkbox', 'I confirm that I am 18 years or older', [
                        'required' => true,
                    ])

                    // Buttons
                    ->addButton('back', '← Back', 'button')
                    ->addButton('complete', '✓ Complete Registration', 'submit')

                    // Validation
                    ->validationRules([
                        'terms' => 'required|accepted',
                        'privacy' => 'required|accepted',
                        'age_confirm' => 'required|accepted',
                    ]);
            })

            // Wizard configuration
            ->currentStep('account')
            ->linear(true); // Must complete steps in order
    });

// Render the layout
return $layout->render();
