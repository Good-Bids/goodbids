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
	siteStatus: string;
	siteStatusOptions: ['pending', 'live', 'inactive'];
	ajaxUrl: string;
	optionsGeneralURL: string;
	createWooCommerceURL: string;
	setUpPaymentURL: string;
	configureShippingURL: string;
	jetpackURL: string;
	akismetURL: string;
	woocommerceSettingsURL: string;
	styleURL: string;
	updateLogoURL: string;
	customizeHomepageURL: string;
	pagesURL: string;
	patternsURL: string;
	auctionWizardURL: string;
	addUsersURL: string;
	accessibilityCheckerURL: string;
	homeURL: string;
	auctionsURL: string;
	orderMetricsURL: string;
	revenueMetricsURL: string;
	invoicesURL: string;
	commentsURL: string;
};

declare const gbNonprofitOnboarding: {
	appID: string;
	stepParam: string;
	stepOptions: ['create-store', 'set-up-payments', 'onboarding-complete'];
	createStoreUrl: string;
	setUpPaymentsUrl: string;
	onboardingCompleteUrl: string;
	setupGuideUrl: string;
	adminUrl: string;
};

// TODO: Type this properly
// eslint-disable-next-line @typescript-eslint/no-explicit-any
declare const wp: any;
