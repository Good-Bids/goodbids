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

	connectStripeURL: string;
	woocommerceSettingsURL: string;
	updateWoocommerceStoreURL: string;
	configureShippingURL: string;
	orderMetricsURL: string;
	revenueMetricsURL: string;

	styleURL: string;
	updateLogoURL: string;
	uploadLogoURL: string;
	customizeHomepageURL: string;

	pagesURL: string;
	patternsURL: string;
	usersUrl: string;
	addUsersURL: string;

	auctionWizardURL: string;
	auctionsURL: string;
	invoicesURL: string;

	siteId: number;
	siteStatus: string;
	siteStatusOptions: ['pending', 'live', 'inactive'];

	isAdmin: boolean;
	isBDPAdmin: boolean;
	isJrAdmin: boolean;
};

declare const gbNonprofitOnboarding: {
	appID: string;
	baseUrl: string;
	stepParam: string;
	skipStepParam: string;
	stepOptions: [
		'init-onboarding',
		'activate-accessibility-checker',
		'create-store',
		'set-up-payments',
		'onboarding-complete',
	];
	initOnboardingUrl: string;
	createStoreUrl: string;
	setUpPaymentsUrl: string;
	skipSetUpPaymentsUrl: string;
	accessibilityCheckerUrl: string;
	onboardingCompleteUrl: string;
	setupGuideUrl: string;
	adminUrl: string;
};

// TODO: Type this properly
// eslint-disable-next-line @typescript-eslint/no-explicit-any
declare const wp: any;
