const SECONDS_IN_MINUTE = 60;
const SECONDS_IN_HOUR = 60 * SECONDS_IN_MINUTE;
const SECONDS_IN_DAY = 24 * SECONDS_IN_HOUR;

export function formatTimeRemaining(timeRemaining: number) {
	const seconds = Math.floor(timeRemaining / 1000);

	if (seconds > SECONDS_IN_DAY) {
		if (seconds > 2 * SECONDS_IN_DAY) {
			return `${Math.floor(seconds / SECONDS_IN_DAY)} days`;
		}

		return `${Math.floor(seconds / SECONDS_IN_DAY)} day`;
	}

	if (seconds > SECONDS_IN_HOUR) {
		const minutes = Math.floor(
			(seconds % SECONDS_IN_HOUR) / SECONDS_IN_MINUTE,
		);

		if (minutes > 0) {
			return `${Math.floor(
				seconds / SECONDS_IN_HOUR,
			)} hours, ${minutes} minutes`;
		}

		return `${Math.floor(seconds / SECONDS_IN_HOUR)} hours`;
	}

	const remainingSeconds = (seconds % SECONDS_IN_MINUTE)
		.toString()
		.padStart(2, '0');

	return `${Math.floor(seconds / SECONDS_IN_MINUTE)}:${remainingSeconds}`;
}
