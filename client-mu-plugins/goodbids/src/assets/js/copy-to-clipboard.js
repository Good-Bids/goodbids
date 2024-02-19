function copyToClipboard(text, element) {
	navigator.clipboard
		.writeText(text)
		.then(() => {
			if (!element) {
				return;
			}

			const original = element.textContent;
			element.textContent = 'Copied!';
			element.blur();

			// Set a timeout to revert the text after 3 seconds
			setTimeout(function () {
				element.textContent = original;
			}, 3000);
		})
		.catch((err) => {
			console.log(err);
		});
}

function maybeShowTooltip() {
	const tooltipElement = document.querySelector('.tooltiptext');
	const tooltipSRText = document.querySelector('.tooltip-sr-text');

	if (tooltipElement) {
		tooltipSRText.innerText = 'Link copied to clipboard!';
		tooltipElement.classList.add('tooltip-visible');
	}

	setTimeout(function () {
		tooltipElement.classList.remove('tooltip-visible');
	}, 3000);
}

document.addEventListener('DOMContentLoaded', function () {
	const clipboardElements = document.querySelectorAll('[data-clipboard]');

	clipboardElements.forEach(function (element) {
		element.addEventListener('click', function (e) {
			// Prevent the default link behavior
			e.preventDefault();

			const text =
				e.target.dataset['clipboard'] ||
				e.currentTarget.dataset['clipboard'];
			const silent =
				e.target.dataset['silent'] || e.currentTarget.dataset['silent'];
			const el = silent ? null : e.target;

			copyToClipboard(text, el);

			maybeShowTooltip();

			return false;
		});
	});
});
