export function validateDateTime(value: string): string | undefined {
	const date = new Date(value);
	if (isNaN(date.getTime())) {
		return 'Invalid date';
	}

	return;
}

export function convertDateTimeForInput(date: Date): string {
	return date.toISOString().slice(0, 16);
}
