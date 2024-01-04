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
		colors: {
			//Colors are set in the wordpress theme.json
			'base': 'var(--wp--preset--color--base)',
			'base-2': 'var(--wp--preset--color--base-2)',
			'base-3': 'var(--wp--preset--color--base-3)',
			'contrast': 'var(--wp--preset--color--contrast)',
			'contrast-2': 'var(--wp--preset--color--contrast-2)',
			'contrast-3': 'var(--wp--preset--color--contrast-3)',
			'contrast-4': 'var(--wp--preset--color--contrast-4)',
			'accent': 'var(--wp--preset--color--accent)',
			'accent-2': 'var(--wp--preset--color--accent-2)',
			'accent-3': 'var(--wp--preset--color--accent-3)',
			'accent-4': 'var(--wp--preset--color--accent-4)',
		},
		borderRadius: {
			DEFAULT: '30px',
		},
	},
	plugins: [],
};
