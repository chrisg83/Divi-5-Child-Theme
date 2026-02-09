<?php
/**
 * Divi 5 Subtheme Starter functions and definitions.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default design token values for the theme.
 */
function divi5_subtheme_starter_default_tokens(): array
{
    return [
        'brand_primary' => '#146ef5',
        'text_primary' => '#172b4d',
        'radius_md' => 12,
        'section_space' => 80,
    ];
}

/**
 * Sanitize integer token fields with bounds.
 */
function divi5_subtheme_starter_sanitize_int_range($value, int $min, int $max): int
{
    $value = absint($value);

    if ($value < $min) {
        return $min;
    }

    if ($value > $max) {
        return $max;
    }

    return $value;
}

/**
 * Resolve tokens from Customizer with safe fallbacks.
 */
function divi5_subtheme_starter_get_tokens(): array
{
    $defaults = divi5_subtheme_starter_default_tokens();

    $brand_primary = sanitize_hex_color((string) get_theme_mod('d5ss_brand_primary', $defaults['brand_primary']));
    $text_primary = sanitize_hex_color((string) get_theme_mod('d5ss_text_primary', $defaults['text_primary']));

    return [
        'brand_primary' => $brand_primary ?: $defaults['brand_primary'],
        'text_primary' => $text_primary ?: $defaults['text_primary'],
        'radius_md' => divi5_subtheme_starter_sanitize_int_range(get_theme_mod('d5ss_radius_md', $defaults['radius_md']), 0, 40),
        'section_space' => divi5_subtheme_starter_sanitize_int_range(get_theme_mod('d5ss_section_space', $defaults['section_space']), 24, 160),
    ];
}

/**
 * Build CSS variable declarations from theme tokens.
 */
function divi5_subtheme_starter_tokens_css(): string
{
    $tokens = divi5_subtheme_starter_get_tokens();

    return sprintf(
        ':root{--d5ss-brand-primary:%1$s;--d5ss-text-primary:%2$s;--d5ss-radius-md:%3$dpx;--d5ss-section-space:%4$dpx;}',
        esc_html($tokens['brand_primary']),
        esc_html($tokens['text_primary']),
        $tokens['radius_md'],
        $tokens['section_space']
    );
}

/**
 * Register Customizer controls for design tokens.
 */
function divi5_subtheme_starter_register_customizer($wp_customize): void
{
    $defaults = divi5_subtheme_starter_default_tokens();

    $wp_customize->add_section(
        'd5ss_design_tokens',
        [
            'title' => __('Divi 5 Subtheme Tokens', 'divi5-subtheme-starter'),
            'priority' => 30,
        ]
    );

    $wp_customize->add_setting(
        'd5ss_brand_primary',
        [
            'default' => $defaults['brand_primary'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ]
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'd5ss_brand_primary_control',
            [
                'label' => __('Brand Primary', 'divi5-subtheme-starter'),
                'section' => 'd5ss_design_tokens',
                'settings' => 'd5ss_brand_primary',
            ]
        )
    );

    $wp_customize->add_setting(
        'd5ss_text_primary',
        [
            'default' => $defaults['text_primary'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ]
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'd5ss_text_primary_control',
            [
                'label' => __('Text Primary', 'divi5-subtheme-starter'),
                'section' => 'd5ss_design_tokens',
                'settings' => 'd5ss_text_primary',
            ]
        )
    );

    $wp_customize->add_setting(
        'd5ss_radius_md',
        [
            'default' => $defaults['radius_md'],
            'sanitize_callback' => static function ($value): int {
                return divi5_subtheme_starter_sanitize_int_range($value, 0, 40);
            },
            'transport' => 'refresh',
        ]
    );

    $wp_customize->add_control(
        'd5ss_radius_md_control',
        [
            'label' => __('Base Radius (px)', 'divi5-subtheme-starter'),
            'section' => 'd5ss_design_tokens',
            'settings' => 'd5ss_radius_md',
            'type' => 'number',
            'input_attrs' => [
                'min' => 0,
                'max' => 40,
                'step' => 1,
            ],
        ]
    );

    $wp_customize->add_setting(
        'd5ss_section_space',
        [
            'default' => $defaults['section_space'],
            'sanitize_callback' => static function ($value): int {
                return divi5_subtheme_starter_sanitize_int_range($value, 24, 160);
            },
            'transport' => 'refresh',
        ]
    );

    $wp_customize->add_control(
        'd5ss_section_space_control',
        [
            'label' => __('Section Spacing (px)', 'divi5-subtheme-starter'),
            'section' => 'd5ss_design_tokens',
            'settings' => 'd5ss_section_space',
            'type' => 'number',
            'input_attrs' => [
                'min' => 24,
                'max' => 160,
                'step' => 1,
            ],
        ]
    );
}
add_action('customize_register', 'divi5_subtheme_starter_register_customizer');

/**
 * Enqueue parent and child theme styles.
 */
function divi5_subtheme_starter_enqueue_assets(): void
{
    $theme = wp_get_theme();
    $child_css_relative = '/assets/css/main.css';
    $child_css_path = get_stylesheet_directory() . $child_css_relative;
    $child_css_version = file_exists($child_css_path) ? (string) filemtime($child_css_path) : $theme->get('Version');

    wp_enqueue_style(
        'divi-parent-style',
        get_template_directory_uri() . '/style.css',
        [],
        $theme->parent() ? $theme->parent()->get('Version') : null
    );

    wp_enqueue_style(
        'divi5-subtheme-starter-main',
        get_stylesheet_directory_uri() . $child_css_relative,
        ['divi-parent-style'],
        $child_css_version
    );
    wp_add_inline_style('divi5-subtheme-starter-main', divi5_subtheme_starter_tokens_css());

    wp_enqueue_script(
        'divi5-subtheme-starter-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        $theme->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'divi5_subtheme_starter_enqueue_assets', 20);
