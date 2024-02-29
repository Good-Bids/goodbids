declare module '*.png';
declare module '*.svg';
declare module '*.jpeg';
declare module '*.jpg';

type PHPVariables = Record<string, string>;

declare const gbAuctionWizard: PHPVariables;
declare const gbNonprofitSetup: {
	appID: string;
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

// TODO: Type this properly
// eslint-disable-next-line @typescript-eslint/no-explicit-any
declare const wp: any;
