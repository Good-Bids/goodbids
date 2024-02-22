export function validateDecimal(value: string): string | undefined {
	const decimalRegex = /^\d+(\.\d{1,2})?$/;
	if (decimalRegex.test(value)) {
		return;
	}

	return gbAuctionWizard.strings.invalidDecimal;
}
