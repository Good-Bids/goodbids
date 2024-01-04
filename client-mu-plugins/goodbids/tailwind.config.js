/** @type {import('tailwindcss').Config} */
export default {
	corePlugins: {
		preflight: false,
	},
	content: [
		'./src/**/*.{css,js,jsx,tsx}',
		'./blocks/**/*.{php,css,js}',
		'./views/**/**/*.{php,html}',
	],
	theme: {
		borderRadius: {
			DEFAULT: '30px',
		},
	},
	plugins: [],
};
