declare module '*.png';
declare module '*.svg';
declare module '*.jpeg';
declare module '*.jpg';

// Defined in classes/Network/Nonprofit.php
type SiteStatusType = 'pending' | 'live' | 'inactive';

type PHPVariables = Record<string, string>;

// These are only defined for the auction wizard page
declare const gbAuctionWizard: PHPVariables;

// These are only defined for the nonprofit setup page
declare const gbNonprofitSetup: {
	appID: string;
	siteStatus: SiteStatusType;
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
