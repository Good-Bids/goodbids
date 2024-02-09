function copyToClipboard(text, element) {
	navigator.clipboard.writeText(text)
		.then(() => {
			const original = element.textContent;
			element.textContent = 'Copied!';
			element.blur();

			// Set a timeout to revert the text after 3 seconds
			setTimeout(function () {
				element.textContent = original;
			}, 3000);
		})
		.catch((err) => {console.log(err)});
}

document.addEventListener('DOMContentLoaded', function () {
	document.addEventListener('click', function (e) {
		if (e.target.dataset.clipboard) {
			// Prevent the default link behavior
			e.preventDefault();

			// Call the copyToClipboard function
			copyToClipboard(e.target.dataset.clipboard, e.target);
		}
	});
});
