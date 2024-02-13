const SECONDS_IN_MINUTE = 60;
const SECONDS_IN_HOUR = 60 * SECONDS_IN_MINUTE;
const SECONDS_IN_DAY = 24 * SECONDS_IN_HOUR;

export function formatTimeRemaining(timeRemainingMs: number | undefined) {
	if (timeRemainingMs === undefined) {
		return '';
	}

	const seconds = Math.floor(timeRemainingMs / 1000);

	// remainder is more than 1 day
	if (seconds > SECONDS_IN_DAY) {
		// remainder is more than 2 days, pluralize
		if (seconds > 2 * SECONDS_IN_DAY) {
			return `${Math.floor(seconds / SECONDS_IN_DAY)} days`;
		}

		return `${Math.floor(seconds / SECONDS_IN_DAY)} day`;
	}

	// remainder is less than a day, but more than 1 hour
	if (seconds > SECONDS_IN_HOUR) {
		const minutes = Math.floor(
			(seconds % SECONDS_IN_HOUR) / SECONDS_IN_MINUTE,
		);

		// if not exactly on the hour, show minutes
		if (minutes > 0) {
			return `${Math.floor(
				seconds / SECONDS_IN_HOUR,
			)} hours, ${minutes} minutes`;
		}

		return `${Math.floor(seconds / SECONDS_IN_HOUR)} hours`;
	}

	// remainder is less than an hour
	const remainingSeconds = (seconds % SECONDS_IN_MINUTE)
		.toString()
		.padStart(2, '0');

	return `${Math.floor(seconds / SECONDS_IN_MINUTE)}:${remainingSeconds}`;
}
