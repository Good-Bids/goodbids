/** @type {import('tailwindcss').Config} */
export default {
	corePlugins: {
		preflight: false,
	},
	content: [
		"./assets/**/*.{css,js,jsx,tsx}",
		"./block/**/*.{php,css,js}",
		"./views/**/**/*.{php,html}",
	],
	theme: {
		extend: {},
	},
	plugins: [],
}
