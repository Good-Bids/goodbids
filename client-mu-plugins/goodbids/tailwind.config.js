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
			DEFAULT: 'var(--wp--preset--spacing--20)',
		},
		borderWidth: {
			DEFAULT: '1px',
			0: '0',
		},
		colors: {
			//Colors are set in the wordpress theme.json
			base: 'var(--wp--preset--color--base)',
			'base-2': 'var(--wp--preset--color--base-2)',
			'base-3': 'var(--wp--preset--color--base-3)',
			contrast: 'var(--wp--preset--color--contrast)',
			'contrast-2': 'var(--wp--preset--color--contrast-2)',
			'contrast-3': 'var(--wp--preset--color--contrast-3)',
			'contrast-4': 'var(--wp--preset--color--contrast-4)',
			'contrast-5': 'var(--wp--preset--color--contrast-5)',
			accent: 'var(--wp--preset--color--accent)',
			'accent-2': 'var(--wp--preset--color--accent-2)',
			'accent-3': 'var(--wp--preset--color--accent-3)',
			'accent-4': 'var(--wp--preset--color--accent-4)',
			transparent: 'transparent',
			// warning: '#fcd34d',
			warning: {
				text: '#000',
				bg: '#fcda82',
			},
			error: {
				text: '#000',
				bg: '#f87171',
			},
		},
		extend: {
			animation: {
				'spin-left': 'spin-left 1.5s linear infinite',
			},
			keyframes: {
				'spin-left': {
					'0%': {
						transform: 'rotate(0deg)',
					},
					'100%': {
						transform: 'rotate(-360deg)',
					},
				},
			},
		},
	},
	plugins: [
		({ addComponents }) => {
			//set up btn classes
			const buttonBase = {
				'@apply rounded py-2 px-6 border border-solid border-transparent no-underline focus:outline-dotted focus:outline-1 focus:outline-offset-2':
					{},
			};

			addComponents({
				//set up btn classes
				'.btn-fill': {
					...buttonBase,
					'@apply bg-contrast text-base-2 hover:bg-contrast-3 hover:text-contrast hover:border-transparent hover:!no-underline focus:bg-contrast-3 focus:text-contrast':
						{},
				},
				'.btn-fill-secondary': {
					...buttonBase,
					'@apply bg-contrast-3 text-contrast hover:bg-base focus:bg-base focus:text-base-2':
						{},
				},
				'.btn-outline': {
					...buttonBase,
					'@apply bg-transparent text-contrast border-contrast hover:bg-contrast hover:border-contrast hover:text-contrast-3 focus:bg-contrast focus:border-contrast focus:text-contrast-3':
						{},
				},
			});
		},
	],
};
