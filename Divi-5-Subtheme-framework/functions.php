<?php
/**
 * Divi 5 Subtheme Framework functions and definitions.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Request higher PHP memory for this theme context.
 *
 * Note: host/server limits can still override these values.
 */
function divi5_subtheme_framework_set_memory_limit(): void
{
    if (!defined('WP_MEMORY_LIMIT')) {
        define('WP_MEMORY_LIMIT', '512M');
    }

    if (!defined('WP_MAX_MEMORY_LIMIT')) {
        define('WP_MAX_MEMORY_LIMIT', '512M');
    }

    if (function_exists('ini_set')) {
        @ini_set('memory_limit', '512M');
    }
}
divi5_subtheme_framework_set_memory_limit();

/**
 * Theme support details shown in the WordPress dashboard panel.
 */
function divi5_subtheme_framework_support_details(): array
{
    return [
        'title' => __('Theme Support', 'divi-5-subtheme-framework'),
        'message' => __('Need help with this theme? Use the resources below.', 'divi-5-subtheme-framework'),
        'logo_url' => 'https://www.bluehelixdesign.com/wp-content/uploads/2016/12/Blue-helix-mega.png',
        'email' => 'Support@bluehelixdesign.com',
        'email_subject' => 'Divi 5 Starter Theme Support',
        'docs_url' => 'https://github.com/chrisg83/Divi-5-Child-Theme',
        'support_url' => 'https://support.bluehelixdesign.com',
    ];
}

/**
 * Render dashboard support panel content.
 */
function divi5_subtheme_framework_render_support_widget(): void
{
    $theme = wp_get_theme();
    $details = divi5_subtheme_framework_support_details();
    $mailto_link = sprintf(
        'mailto:%s?subject=%s',
        rawurlencode($details['email']),
        rawurlencode($details['email_subject'])
    );
    ?>
    <?php if (!empty($details['logo_url'])) : ?>
        <p>
            <img
                class="d5sf-support-logo"
                src="<?php echo esc_url($details['logo_url']); ?>"
                alt="<?php esc_attr_e('Support Logo', 'divi-5-subtheme-framework'); ?>"
            />
        </p>
    <?php else : ?>
        <p><em><?php esc_html_e('Add your support logo URL in divi5_subtheme_framework_support_details().', 'divi-5-subtheme-framework'); ?></em></p>
    <?php endif; ?>

    <p><?php echo esc_html($details['message']); ?></p>
    <ul>
        <li>
            <strong><?php esc_html_e('Theme', 'divi-5-subtheme-framework'); ?>:</strong>
            <?php echo esc_html($theme->get('Name')); ?>
            (<?php echo esc_html($theme->get('Version')); ?>)
        </li>
        <li>
            <strong><?php esc_html_e('Support Email', 'divi-5-subtheme-framework'); ?>:</strong>
            <a href="mailto:<?php echo esc_attr($details['email']); ?>">
                <?php echo esc_html($details['email']); ?>
            </a>
        </li>
        <li>
            <strong><?php esc_html_e('Documentation', 'divi-5-subtheme-framework'); ?>:</strong>
            <a href="<?php echo esc_url($details['docs_url']); ?>" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e('View Docs', 'divi-5-subtheme-framework'); ?>
            </a>
        </li>
        <li>
            <strong><?php esc_html_e('Support Portal', 'divi-5-subtheme-framework'); ?>:</strong>
            <a href="<?php echo esc_url($details['support_url']); ?>" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e('Open Support', 'divi-5-subtheme-framework'); ?>
            </a>
        </li>
    </ul>
    <p>
        <a class="button button-primary" href="<?php echo esc_url($mailto_link); ?>" target="_blank" rel="noopener noreferrer">
            <?php esc_html_e('Contact Support', 'divi-5-subtheme-framework'); ?>
        </a>
    </p>
    <?php
}

/**
 * Register dashboard support panel.
 */
function divi5_subtheme_framework_register_support_widget(): void
{
    $details = divi5_subtheme_framework_support_details();

    wp_add_dashboard_widget(
        'divi5_subtheme_framework_support_widget',
        esc_html($details['title']),
        'divi5_subtheme_framework_render_support_widget'
    );
}
add_action('wp_dashboard_setup', 'divi5_subtheme_framework_register_support_widget');

/**
 * Dashboard support widget admin styles.
 */
function divi5_subtheme_framework_admin_styles(): void
{
    echo '<style>.d5sf-support-logo{max-width:400px;height:auto;}</style>';
}
add_action('admin_head', 'divi5_subtheme_framework_admin_styles');

/**
 * Default design token values for the theme.
 */
function divi5_subtheme_framework_default_tokens(): array
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
function divi5_subtheme_framework_sanitize_int_range($value, int $min, int $max): int
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
function divi5_subtheme_framework_get_tokens(): array
{
    $defaults = divi5_subtheme_framework_default_tokens();

    $brand_primary = sanitize_hex_color((string) get_theme_mod('d5ss_brand_primary', $defaults['brand_primary']));
    $text_primary = sanitize_hex_color((string) get_theme_mod('d5ss_text_primary', $defaults['text_primary']));

    return [
        'brand_primary' => $brand_primary ?: $defaults['brand_primary'],
        'text_primary' => $text_primary ?: $defaults['text_primary'],
        'radius_md' => divi5_subtheme_framework_sanitize_int_range(get_theme_mod('d5ss_radius_md', $defaults['radius_md']), 0, 40),
        'section_space' => divi5_subtheme_framework_sanitize_int_range(get_theme_mod('d5ss_section_space', $defaults['section_space']), 24, 160),
    ];
}

/**
 * Build CSS variable declarations from theme tokens.
 */
function divi5_subtheme_framework_tokens_css(): string
{
    $tokens = divi5_subtheme_framework_get_tokens();

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
function divi5_subtheme_framework_register_customizer($wp_customize): void
{
    $defaults = divi5_subtheme_framework_default_tokens();

    $wp_customize->add_section(
        'd5ss_design_tokens',
        [
            'title' => __('Divi 5 Subtheme Tokens', 'divi-5-subtheme-framework'),
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
                'label' => __('Brand Primary', 'divi-5-subtheme-framework'),
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
                'label' => __('Text Primary', 'divi-5-subtheme-framework'),
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
                return divi5_subtheme_framework_sanitize_int_range($value, 0, 40);
            },
            'transport' => 'refresh',
        ]
    );

    $wp_customize->add_control(
        'd5ss_radius_md_control',
        [
            'label' => __('Base Radius (px)', 'divi-5-subtheme-framework'),
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
                return divi5_subtheme_framework_sanitize_int_range($value, 24, 160);
            },
            'transport' => 'refresh',
        ]
    );

    $wp_customize->add_control(
        'd5ss_section_space_control',
        [
            'label' => __('Section Spacing (px)', 'divi-5-subtheme-framework'),
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
add_action('customize_register', 'divi5_subtheme_framework_register_customizer');

/**
 * Determine an admin menu position above Divi when available.
 */
function divi5_subtheme_framework_theme_settings_menu_position(): float
{
    global $menu;

    if (!is_array($menu)) {
        return 59.0;
    }

    $divi_menu_slugs = [
        'et_divi_options',
        'et_theme_options',
    ];

    foreach ($menu as $position => $item) {
        $menu_slug = $item[2] ?? '';

        if (in_array($menu_slug, $divi_menu_slugs, true)) {
            return max(1.0, ((float) $position) - 0.1);
        }
    }

    return 59.0;
}

/**
 * Register subpages for the Theme Settings admin section.
 */
function divi5_subtheme_framework_theme_settings_subpages(): array
{
    return apply_filters(
        'divi5_subtheme_framework_theme_settings_subpages',
        [
            [
                'page_title' => __('Theme Settings', 'divi-5-subtheme-framework'),
                'menu_title' => __('Overview', 'divi-5-subtheme-framework'),
                'menu_slug' => 'd5sf-theme-settings-overview',
                'callback' => 'divi5_subtheme_framework_render_theme_settings_overview',
            ],
        ]
    );
}

/**
 * Render the default Theme Settings overview page.
 */
function divi5_subtheme_framework_render_theme_settings_overview(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Theme Settings', 'divi-5-subtheme-framework'); ?></h1>
        <p><?php esc_html_e('This area is the control center for custom theme modifications and advanced settings.', 'divi-5-subtheme-framework'); ?></p>

        <div class="card" style="max-width: 780px;">
            <h2><?php esc_html_e('Ready For Additional Modules', 'divi-5-subtheme-framework'); ?></h2>
            <p><?php esc_html_e('New theme feature modules can be added here as submenu pages over time without changing the main menu structure.', 'divi-5-subtheme-framework'); ?></p>
            <p><?php esc_html_e('Current design token controls still live in the WordPress Customizer.', 'divi-5-subtheme-framework'); ?></p>
            <p>
                <a class="button button-primary" href="<?php echo esc_url(admin_url('customize.php')); ?>">
                    <?php esc_html_e('Open Customizer', 'divi-5-subtheme-framework'); ?>
                </a>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Register Theme Settings top-level admin menu and subpages.
 */
function divi5_subtheme_framework_register_theme_settings_menu(): void
{
    $capability = 'manage_options';
    $parent_slug = 'd5sf-theme-settings';

    add_menu_page(
        __('Theme Settings', 'divi-5-subtheme-framework'),
        __('Theme Settings', 'divi-5-subtheme-framework'),
        $capability,
        $parent_slug,
        'divi5_subtheme_framework_render_theme_settings_overview',
        'dashicons-admin-generic',
        divi5_subtheme_framework_theme_settings_menu_position()
    );

    foreach (divi5_subtheme_framework_theme_settings_subpages() as $subpage) {
        add_submenu_page(
            $parent_slug,
            $subpage['page_title'],
            $subpage['menu_title'],
            $capability,
            $subpage['menu_slug'],
            $subpage['callback']
        );
    }
}
add_action('admin_menu', 'divi5_subtheme_framework_register_theme_settings_menu', 99);

/**
 * Default values for performance settings.
 */
function divi5_subtheme_framework_performance_defaults(): array
{
    return [
        'disable_emojis' => 0,
        'disable_embeds' => 0,
        'remove_asset_version_query' => 0,
        'disable_dashicons_visitors' => 0,
    ];
}

/**
 * Get merged performance settings.
 */
function divi5_subtheme_framework_get_performance_settings(): array
{
    $defaults = divi5_subtheme_framework_performance_defaults();
    $saved = get_option('d5sf_performance_settings', []);

    if (!is_array($saved)) {
        return $defaults;
    }

    return wp_parse_args($saved, $defaults);
}

/**
 * Read a single boolean performance setting.
 */
function divi5_subtheme_framework_performance_enabled(string $key): bool
{
    $settings = divi5_subtheme_framework_get_performance_settings();

    return !empty($settings[$key]);
}

/**
 * Sanitize performance option payload.
 */
function divi5_subtheme_framework_sanitize_performance_settings($input): array
{
    $defaults = divi5_subtheme_framework_performance_defaults();
    $sanitized = [];
    $input = is_array($input) ? $input : [];

    foreach (array_keys($defaults) as $key) {
        $sanitized[$key] = (isset($input[$key]) && '1' === (string) $input[$key]) ? 1 : 0;
    }

    return $sanitized;
}

/**
 * Add Performance submenu to Theme Settings.
 */
function divi5_subtheme_framework_add_performance_subpage(array $subpages): array
{
    $subpages[] = [
        'page_title' => __('Performance Settings', 'divi-5-subtheme-framework'),
        'menu_title' => __('Performance', 'divi-5-subtheme-framework'),
        'menu_slug' => 'd5sf-theme-settings-performance',
        'callback' => 'divi5_subtheme_framework_render_theme_settings_performance',
    ];

    return $subpages;
}
add_filter('divi5_subtheme_framework_theme_settings_subpages', 'divi5_subtheme_framework_add_performance_subpage');

/**
 * Register settings and fields for the performance page.
 */
function divi5_subtheme_framework_register_performance_settings(): void
{
    register_setting(
        'd5sf_performance_settings_group',
        'd5sf_performance_settings',
        [
            'type' => 'array',
            'sanitize_callback' => 'divi5_subtheme_framework_sanitize_performance_settings',
            'default' => divi5_subtheme_framework_performance_defaults(),
        ]
    );

    add_settings_section(
        'd5sf_performance_main_section',
        __('Performance Options', 'divi-5-subtheme-framework'),
        static function (): void {
            echo '<p>' . esc_html__('These Performance Options are still in BETA Testing and under development, please use with care. These are designed to assist you when troubleshooting theme conflicts or errors. Feel free to suggest or request additional tools and/or features on the GitHub repo.', 'divi-5-subtheme-framework') . '</p>';
            echo '<p><a class="button button-secondary" href="' . esc_url('https://github.com/chrisg83/Divi-5-Child-Theme') . '" target="_blank" rel="noopener noreferrer">' . esc_html__('View GitHub Repo', 'divi-5-subtheme-framework') . '</a></p>';
            echo '<p>' . esc_html__('Enable only the optimizations you need. All toggles are off by default to avoid conflicts.', 'divi-5-subtheme-framework') . '</p>';
        },
        'd5sf-theme-settings-performance'
    );

    $fields = [
        'disable_emojis' => [
            'label' => __('Disable Emoji Scripts and Styles', 'divi-5-subtheme-framework'),
            'description' => __('Removes WordPress emoji assets on front-end and admin.', 'divi-5-subtheme-framework'),
        ],
        'disable_embeds' => [
            'label' => __('Disable WP Embeds', 'divi-5-subtheme-framework'),
            'description' => __('Disables oEmbed discovery links, routes, and the wp-embed script.', 'divi-5-subtheme-framework'),
        ],
        'remove_asset_version_query' => [
            'label' => __('Remove Asset Version Query Strings', 'divi-5-subtheme-framework'),
            'description' => __('Removes the ver query arg from CSS and JS URLs.', 'divi-5-subtheme-framework'),
        ],
        'disable_dashicons_visitors' => [
            'label' => __('Disable Dashicons for Visitors', 'divi-5-subtheme-framework'),
            'description' => __('Prevents loading dashicons on the front-end for logged-out users.', 'divi-5-subtheme-framework'),
        ],
    ];

    foreach ($fields as $key => $field) {
        add_settings_field(
            'd5sf_performance_' . $key,
            $field['label'],
            'divi5_subtheme_framework_render_performance_checkbox_field',
            'd5sf-theme-settings-performance',
            'd5sf_performance_main_section',
            [
                'key' => $key,
                'description' => $field['description'],
            ]
        );
    }
}
add_action('admin_init', 'divi5_subtheme_framework_register_performance_settings');

/**
 * Render a single checkbox field for performance settings.
 */
function divi5_subtheme_framework_render_performance_checkbox_field(array $args): void
{
    $key = $args['key'];
    $description = $args['description'];
    $settings = divi5_subtheme_framework_get_performance_settings();
    ?>
    <label for="<?php echo esc_attr('d5sf_performance_' . $key); ?>">
        <input
            id="<?php echo esc_attr('d5sf_performance_' . $key); ?>"
            name="d5sf_performance_settings[<?php echo esc_attr($key); ?>]"
            type="checkbox"
            value="1"
            <?php checked(!empty($settings[$key])); ?>
        />
        <?php echo esc_html($description); ?>
    </label>
    <?php
}

/**
 * Render the Theme Settings > Performance page.
 */
function divi5_subtheme_framework_render_theme_settings_performance(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Performance Settings', 'divi-5-subtheme-framework'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('d5sf_performance_settings_group');
            do_settings_sections('d5sf-theme-settings-performance');
            submit_button(__('Save Performance Settings', 'divi-5-subtheme-framework'));
            ?>
        </form>
    </div>
    <?php
}

/**
 * Disable WordPress emoji assets if enabled.
 */
function divi5_subtheme_framework_apply_disable_emojis(): void
{
    if (!divi5_subtheme_framework_performance_enabled('disable_emojis')) {
        return;
    }

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('emoji_svg_url', '__return_false');
}
add_action('init', 'divi5_subtheme_framework_apply_disable_emojis', 20);

/**
 * Disable WordPress oEmbed helpers and script if enabled.
 */
function divi5_subtheme_framework_apply_disable_embeds(): void
{
    if (!divi5_subtheme_framework_performance_enabled('disable_embeds')) {
        return;
    }

    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    add_filter('embed_oembed_discover', '__return_false');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
}
add_action('init', 'divi5_subtheme_framework_apply_disable_embeds', 20);

/**
 * Conditionally remove wp-embed script.
 */
function divi5_subtheme_framework_maybe_disable_embed_script(): void
{
    if (!divi5_subtheme_framework_performance_enabled('disable_embeds')) {
        return;
    }

    wp_dequeue_script('wp-embed');
    wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'divi5_subtheme_framework_maybe_disable_embed_script');

/**
 * Conditionally remove ver query arg from CSS/JS assets.
 */
function divi5_subtheme_framework_maybe_remove_asset_ver(string $src): string
{
    if (!divi5_subtheme_framework_performance_enabled('remove_asset_version_query')) {
        return $src;
    }

    return (string) remove_query_arg('ver', $src);
}
add_filter('script_loader_src', 'divi5_subtheme_framework_maybe_remove_asset_ver', 9999);
add_filter('style_loader_src', 'divi5_subtheme_framework_maybe_remove_asset_ver', 9999);

/**
 * Conditionally disable dashicons for visitors.
 */
function divi5_subtheme_framework_maybe_disable_dashicons_for_visitors(): void
{
    if (!divi5_subtheme_framework_performance_enabled('disable_dashicons_visitors')) {
        return;
    }

    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
    }
}
add_action('wp_enqueue_scripts', 'divi5_subtheme_framework_maybe_disable_dashicons_for_visitors', 100);

/**
 * Enqueue parent and child theme styles.
 */
function divi5_subtheme_framework_enqueue_assets(): void
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
        'divi-5-subtheme-framework-main',
        get_stylesheet_directory_uri() . $child_css_relative,
        ['divi-parent-style'],
        $child_css_version
    );
    wp_add_inline_style('divi-5-subtheme-framework-main', divi5_subtheme_framework_tokens_css());

    wp_enqueue_script(
        'divi-5-subtheme-framework-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        $theme->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'divi5_subtheme_framework_enqueue_assets', 20);
