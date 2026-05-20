/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./resources/**/*.blade.php',
		'./resources/**/*.js',
		'./app-modules/**/resources/**/*.blade.php',
		'./vendor/glhd/aire/**/*.php',
		'./vendor/glhd/gretel/resources/**/*.blade.php',
	],
	theme: {
		extend: {
			fontFamily: {
				sans: ['system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
			},
		},
	},
	plugins: [require('@tailwindcss/forms')],
};
