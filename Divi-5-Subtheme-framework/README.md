# Divi 5 Subtheme Framework

This is a clean child theme scaffold for Divi 5 with Tailwind CSS.

## 1. Rename theme metadata

Edit `style.css` and set:
- `Theme Name`
- `Author`
- `Theme URI`
- `Author URI`
- `Text Domain`

## 2. Install

Copy `divi-5-subtheme-framework` to:

`wp-content/themes/divi-5-subtheme-framework`

## 3. Activate

In WordPress admin:

`Appearance -> Themes -> Divi 5 Subtheme Framework -> Activate`

## 4. Customize

- Add Tailwind styles in `assets/css/tailwind.css`
- Add scripts in `assets/js/main.js`
- Add PHP hooks and custom logic in `functions.php`
- Update shared token defaults in `tailwind.preset.js`

## 5. Tailwind workflow

Install dependencies:

`npm install`

Build once:

`npm run build:css`

Watch while developing:

`npm run watch:css`

## Notes

- `Template: Divi` in `style.css` is required so WordPress treats this as a Divi child theme.
- Tailwind output is generated into `assets/css/main.css` and loaded by `functions.php`.
- Tailwind utility classes are prefixed with `tw-` to avoid conflicts with Divi classes.
- Tailwind preflight is disabled to avoid resetting Divi base styles.
- Add classes used only in Divi Builder content to `safelist.txt`.
- Theme token controls are available in `Appearance -> Customize -> Divi 5 Subtheme Tokens`.
- CSS layers are organized in `assets/css/tailwind.css` as `base`, `components`, and `utilities`.

## Included utility components

- `.tw-btn` and `.tw-btn-primary`
- `.tw-card`
- `.tw-section-pad`
- `.tw-prose-block`
