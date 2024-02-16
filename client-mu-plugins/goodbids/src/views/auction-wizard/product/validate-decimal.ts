export function validateDecimal(value: string): boolean {
	const decimalRegex = /^\d+(\.\d{1,2})?$/;
	return decimalRegex.test(value);
}
