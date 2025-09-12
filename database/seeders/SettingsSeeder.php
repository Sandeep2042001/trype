<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Information
            'company_name' => 'Tryp',
            'site_logo' => '/storage/logos/7Hx0NJN8GwxI1srCWrhfQQpt1hJx7CS4fu33xzhC.png',
            'hero_heading' => 'Discover Your Perfect Getaway',
            'hero_subheading' => 'Explore exclusive vacation packages and create memories that last a lifetime',
            'hero_bg_image' => '/storage/hero/7uY5jJLbY1RSPtxRZ8xcxt9h16RsjEUdUHu0tTTV.jpg',
            
            // Header Colors
            'header_bg_color_from' => '#2563eb',
            'header_bg_color_to' => '#3b82f6',
            
            // Button Colors
            'primary_button_bg' => '#2563eb',
            'primary_button_hover_bg' => '#1d4ed8',
            'secondary_text_color' => '#ffffff',
            
            // CSS Version for cache busting
            'css_version' => time(),
            
            // Contact Information
            'contact_email' => 'info@tryp.com',
            'contact_phone' => '+1 (555) 123-4567',
            'contact_address' => '123 Travel Street, Adventure City, AC 12345',
            
            // Social Media
            'facebook_url' => 'https://facebook.com/tryp',
            'twitter_url' => 'https://twitter.com/tryp',
            'instagram_url' => 'https://instagram.com/tryp',
            'linkedin_url' => 'https://linkedin.com/company/tryp',
            
            // SEO
            'meta_title' => 'Tryp - Discover Your Perfect Getaway',
            'meta_description' => 'Explore exclusive vacation packages and create memories that last a lifetime with Tryp.',
            'meta_keywords' => 'travel, vacation, packages, destinations, booking',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }
    }
}
