/* eslint-env node */

module.exports = {
	syntax: 'postcss-scss',
	plugins: [
		require('postcss-import-ext-glob'),
		require('postcss-import'),
		require('@csstools/postcss-sass'),
		require('tailwindcss/nesting'),
		require('tailwindcss'),
	],
};
