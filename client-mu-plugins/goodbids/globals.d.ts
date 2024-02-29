declare module '*.png';
declare module '*.svg';
declare module '*.jpeg';
declare module '*.jpg';

type PHPVariables = Record<string, string>;

declare const gbAuctionWizard: PHPVariables;
declare const gbNonprofitSetup: PHPVariables;

// TODO: Type this properly
// eslint-disable-next-line @typescript-eslint/no-explicit-any
declare const wp: any;
