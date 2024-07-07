const { merge } = require('lodash');

// Set the Preflight flag based on the build target.
const includePreflight = 'editor' === process.env._TW_TARGET ? false : true;

module.exports = {
	presets: [
		// Manage Tailwind Typography's configuration in a separate file.
		require('./tailwind-typography.config.js'),
	],
	content: [
		// Ensure changes to PHP files and `theme.json` trigger a rebuild.
		'./theme/**/*.php',
	],
	theme: {
		// Extend the default Tailwind theme.
		extend: {
			spacing: {
				content: 'var(--layout--content-padding)',
			},
			fontFamily: {
				base: 'var(--afm-font-family)',
			},
			fontSize: {
				'display-2xl': ['4.5rem', '5.625rem'],
				'display-xl': ['3.75rem', '4.5rem'],
				'display-lg': ['3rem', '3.75rem'],
				'display-md': ['2.25rem', '2.75rem'],
				'display-sm': ['1.875rem', '2.375rem'],
				'display-xs': ['1.5rem', '2rem'],
				'body-xl': ['1.25rem', '1.875rem'],
				'body-lg': ['1.125rem', '1.75rem'],
				'body-md': ['1rem', '1.5rem'],
				'body-sm': ['0.875rem', '1.25rem'],
				'body-xs': ['0.75rem', '1.125rem'],
				'caption-md': ['0.625rem', '1rem'],
			},
			colors: {
				surface: {
					DEFAULT: 'var(--afm-color-surface)',
					high: 'var(--afm-color-surface-high)',
					highest: 'var(--afm-color-surface-highest)',
					max: 'var(--afm-color-surface-max)',
				},
				'surface-outline': {
					lowest: 'var(--afm-color-surface-outline-lowest)',
					low: 'var(--afm-color-surface-outline-low)',
					DEFAULT: 'var(--afm-color-surface-outline)',
					high: 'var(--afm-color-surface-high)',
					highest: 'var(--afm-color-surface-outline-highest)',
				},
				'on-surface': {
					min: 'var(--afm-color-on-surface-min)',
					lowest: 'var(--afm-color-on-surface-lowest)',
					low: 'var(--afm-color-on-surface-low)',
					DEFAULT: 'var(--afm-color-on-surface)',
					high: 'var(--afm-color-on-surface-high)',
					highest: 'var(--afm-color-on-surface-highest)',
				},
				...merge(
					...['gray', 'primary', 'error', 'warning', 'success'].map(
						(c) => ({
							[`${c}`]: {
								lowest: `var(--afm-color-${c}-lowest)`,
								low: `var(--afm-color-${c}-low)`,
								tint: `var(--afm-color-${c}-tint)`,
								DEFAULT: `var(--afm-color-${c})`,
								high: `var(--afm-color-${c}-high)`,
								highest: `var(--afm-color-${c}-highest)`,
							},
							[`${c}-container`]: {
								DEFAULT: `var(--afm-color-${c}-container)`,
								low: `var(--afm-color-${c}-container-low)`,
								lowest: `var(--afm-color-${c}-container-lowest)`,
							},
							[`on-${c}-container`]: {
								DEFAULT: `var(--afm-color-on-${c}-container)`,
							},
							[`on-${c}`]: {
								DEFAULT: `var(--afm-color-on-${c})`,
								low: `var(--afm-color-on-${c}-low)`,
								lowest: `var(--afm-color-on-${c}-lowest)`,
								min: `var(--afm-color-on-${c}-min)`,
							},
							[`${c}-outline`]: {
								DEFAULT: `var(--afm-color-${c}-outline)`,
								lowest: `var(--afm-color-${c}-outline-lowest)`,
							},
						})
					)
				),
			},
		},
	},
	corePlugins: {
		// Disable Preflight base styles in builds targeting the editor.
		preflight: includePreflight,
	},
	plugins: [
		// Add Tailwind Typography (via _tw fork).
		require('@_tw/typography'),

		// Extract colors and widths from `theme.json`.
		require('@_tw/themejson'),

		// Uncomment below to add additional first-party Tailwind plugins.
		// require('@tailwindcss/forms'),
		// require('@tailwindcss/aspect-ratio'),
		// require('@tailwindcss/container-queries'),
	],
};
