/** @type {import('tailwindcss').Config} */
module.exports = {
	corePlugins: {
		preflight: false,
	},
	content: [
		'./src/**/*.{css,js,jsx,tsx,php}',
		'./blocks/**/*.{php,css,js}',
		'./views/**/*.{php,html}',
	],
	theme: {
		extend: {
			animation: {
				'spin-left': 'spin-left 1.5s linear infinite',
			},
			backgroundImage: {
				'select-arrow':
					'url(\'data:image/svg+xml,<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path id="Shape" d="M4.21967 8.46967C4.51256 8.17678 4.98744 8.17678 5.28033 8.46967L12 15.1893L18.7197 8.46967C19.0126 8.17678 19.4874 8.17678 19.7803 8.46967C20.0732 8.76256 20.0732 9.23744 19.7803 9.53033L12.5303 16.7803C12.2374 17.0732 11.7626 17.0732 11.4697 16.7803L4.21967 9.53033C3.92678 9.23744 3.92678 8.76256 4.21967 8.46967Z" fill="%230A3624"/></svg>\')',
			},
			borderRadius: {
				xs: '0.5rem',
				sm: '1rem',
				DEFAULT: '2rem',
				'admin-sm': '0.375rem',
			},
			borderWidth: {
				DEFAULT: '1px',
				0: '0',
				2: '2px',
				6: '6px',
			},
			colors: {
				//Colors are set in the WordPress theme.json
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
				'black-100': 'rgba(0, 0, 0, 0.1)',
				warning: {
					text: '#000',
					bg: '#fcda82',
				},
				error: {
					text: '#000',
					bg: '#f87171',
				},
				red: {
					500: '#c70808',
				},
				admin: {
					main: '#0a3624',
					accent: '#70ff8f',
					secondary: '#125E3E',
					gray: '#f0f0f1',
				},
				'admin-blue': {
					300: '#2271b1',
					600: '#135e96',
				},
			},
			fontSize: {
				xs: 'var(--wp--preset--font-size--x-small)',
				sm: 'var(--wp--preset--font-size--small)',
				md: 'var(--wp--preset--font-size--medium)',
				lg: 'var(--wp--preset--font-size--large)',
				xl: 'var(--wp--preset--font-size--x-large)',
				xxl: 'var(--wp--preset--font-size--xx-large)',
				'admin-label': '0.875rem',
				'admin-content': '1rem',
				'admin-medium': '1.125rem',
				'admin-large': '1.25rem',
				'admin-extra-large': '1.5rem',
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
			maxWidth: {
				100: '25rem',
				120: '30rem',
			},
			minWidth: {
				88: '22rem',
			},
		},
	},
	plugins: [
		({ addComponents, addVariant }) => {
			addVariant('tooltip-visible', '.tooltip-visible &');

			//set up btn classes
			const buttonBase = {
				'@apply rounded py-2 px-6 border border-solid border-transparent leading-normal no-underline focus:outline-dotted focus:outline-1 focus:outline-offset-2 hover:cursor-pointer':
					{},
			};

			addComponents({
				//set up btn classes
				'.btn-fill': {
					...buttonBase,
					'@apply bg-contrast text-base-2 hover:bg-contrast-3 hover:text-contrast hover:border-transparent hover:!no-underline focus:bg-contrast-3 focus:text-contrast':
						{},
				},
				'.btn-fill-gray': {
					...buttonBase,
					'@apply bg-contrast-5 text-contrast hover:bg-contrast-3 hover:text-contrast hover:border-transparent hover:!no-underline focus:bg-contrast-3 focus:text-contrast':
						{},
				},
				'.btn-fill-secondary': {
					...buttonBase,
					'@apply bg-contrast-3 text-contrast hover:bg-contrast hover:text-base-2 focus:bg-contrast focus:text-base-2':
						{},
				},
				'.btn-outline': {
					...buttonBase,
					'@apply bg-transparent text-contrast border-contrast hover:bg-contrast hover:border-contrast hover:text-contrast-3 focus:bg-contrast focus:border-contrast focus:text-contrast-3':
						{},
				},
				'.btn-fill-sm': {
					...buttonBase,
					'@apply text-xs py-1 px-2 bg-contrast text-base-2 hover:bg-contrast-3 hover:text-contrast hover:border-transparent hover:!no-underline focus:bg-contrast-3 focus:text-contrast':
						{},
				},
			});
		},
	],
};
