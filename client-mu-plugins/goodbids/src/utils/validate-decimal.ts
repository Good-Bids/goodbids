import { __ } from '@wordpress/i18n';

export function validateDecimal(value: string): string | undefined {
	const decimalRegex = /^\d+(\.\d{1,2})?$/;
	if (decimalRegex.test(value)) {
		return;
	}

	return __('Invalid value. Must match format 0.00', 'goodbids');
}
