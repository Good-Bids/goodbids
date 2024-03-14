import { __ } from '@wordpress/i18n';

export function validateDecimal(value: string): string | undefined {
	if (!value.length) {
		return;
	}

	const decimalRegex = /^\d+(\.\d{1,2})?$/;
	if (decimalRegex.test(value)) {
		return;
	}

	return __(
		'Invalid value. Please remove any commas or currency symbols. Must match format 0.00.',
		'goodbids',
	);
}

export function validateInteger(value: string): string | undefined {
	if (!value.length) {
		return;
	}

	const integerRegex = /^\d+$/;
	if (integerRegex.test(value)) {
		return;
	}

	return 'Must be a whole number';
}

export function formatStringToCurrency(value: string): string {
	// If the value is an invalid decimal, return the value as is
	if (validateDecimal(value)) {
		return value;
	}

	return `$${Number(value).toLocaleString()}`;
}
