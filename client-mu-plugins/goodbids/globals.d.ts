declare module '*.png';
declare module '*.svg';
declare module '*.jpeg';
declare module '*.jpg';

type Internationalization = Record<string, Internationalization>;

declare const gbAuctionWizard: Internationalization;

// TODO: Type this properly
// eslint-disable-next-line @typescript-eslint/no-explicit-any
declare const wp: any;
