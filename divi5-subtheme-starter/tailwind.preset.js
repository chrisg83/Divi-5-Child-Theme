/** @type {import('tailwindcss').Config} */
module.exports = {
  theme: {
    extend: {
      colors: {
        brand: {
          primary: 'var(--d5ss-brand-primary)',
          text: 'var(--d5ss-text-primary)'
        }
      },
      borderRadius: {
        token: 'var(--d5ss-radius-md)'
      },
      spacing: {
        section: 'var(--d5ss-section-space)'
      },
      boxShadow: {
        card: '0 8px 24px rgba(23, 43, 77, 0.08)'
      }
    }
  }
};
