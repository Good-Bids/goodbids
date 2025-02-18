const path = require('path');
const defaults = require('@wordpress/scripts/config/webpack.config.js');
const TsconfigPathsPlugin = require('tsconfig-paths-webpack-plugin');

module.exports = {
	...defaults,
	devServer: {
		devMiddleware: {
			writeToDisk: true,
		},
		allowedHosts: 'all',
		host: 'localhost',
		port: 8887,
		proxy: {
			'/build': {
				pathRewrite: {
					'^/build': '',
				},
			},
		},
		liveReload: true,
		watchFiles: ['src/**/*'],
	},
	entry: {
		biddingView: {
			import: path.resolve(
				process.cwd(),
				'src/blocks/bidding',
				'view.tsx',
			),
			filename: 'blocks/bidding/view.js',
		},
		biddingIndex: {
			import: path.resolve(
				process.cwd(),
				'src/blocks/bidding',
				'index.tsx',
			),
			filename: 'blocks/bidding/index.js',
		},
		admin: {
			import: path.resolve(process.cwd(), 'src', 'admin.tsx'),
			filename: 'admin.js',
		},
		editor: {
			import: path.resolve(process.cwd(), 'src', 'editor.tsx'),
			filename: 'editor.js',
		},
		main: {
			import: path.resolve(process.cwd(), 'src', 'main.tsx'),
			filename: 'main.js',
		},
		auctionWizard: {
			import: path.resolve(
				process.cwd(),
				'src/views/auction-wizard',
				'index.tsx',
			),
			filename: 'views/auction-wizard.js',
		},
		nonprofitOnboarding: {
			import: path.resolve(
				process.cwd(),
				'src/views/nonprofit-onboarding',
				'index.tsx',
			),
			filename: 'views/nonprofit-onboarding.js',
		},
		nonprofitSetupGuide: {
			import: path.resolve(
				process.cwd(),
				'src/views/nonprofit-setup-guide',
				'index.tsx',
			),
			filename: 'views/nonprofit-setup-guide.js',
		},
	},
	output: {
		filename: '[name].js',
		path: path.resolve(process.cwd(), 'build'),
	},
	resolve: {
		...defaults.resolve,
		plugins: [new TsconfigPathsPlugin()],
	},
};
