declare module '*.png';
declare module '*.svg';
declare module '*.jpeg';
declare module '*.jpg';

type PHPVariables = Record<string, string>;

// These are only defined for the auction wizard page
declare const gbAuctionWizard: PHPVariables;

// These are only defined for the nonprofit setup page
declare const gbNonprofitSetupGuide: {
	appID: string;
	ajaxUrl: string;
	homeURL: string;

	optionsGeneralURL: string;
	commentsURL: string;
	jetpackURL: string;
	akismetURL: string;
	accessibilityCheckerURL: string;

	woocommerceSettingsURL: string;
	configureShippingURL: string;
	orderMetricsURL: string;
	revenueMetricsURL: string;

	styleURL: string;
	updateLogoURL: string;
	customizeHomepageURL: string;

	pagesURL: string;
	patternsURL: string;
	addUsersURL: string;

	auctionWizardURL: string;
	auctionsURL: string;
	invoicesURL: string;

	siteId: number;
	siteStatus: string;
	siteStatusOptions: ['pending', 'live', 'inactive'];
};

declare const gbNonprofitOnboarding: {
	appID: string;
	stepParam: string;
	stepOptions: [
		'create-store',
		'set-up-payments',
		'activate-accessibility-checker',
		'onboarding-complete',
	];
	createStoreUrl: string;
	setUpPaymentsUrl: string;
	accessibilityCheckerUrl: string;
	onboardingCompleteUrl: string;
	setupGuideUrl: string;
	adminUrl: string;
};

// TODO: Type this properly
// eslint-disable-next-line @typescript-eslint/no-explicit-any
declare const wp: any;
