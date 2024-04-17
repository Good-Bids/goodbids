/* global goodbidsBidNow */

function toggleBidBlocks() {
	refreshBidNowBlock();
	document.querySelector('.bid-now').classList.toggle('standby');
	document.querySelector('.bid-now').classList.toggle('active');

	// just in case
	document.getElementById('goodbids-socket-error').style.display = 'none';
}

// Create an observer for the socket error.
const observer = new MutationObserver(function (mutations, observerInstance) {
	// Check for the socket error.
	if (
		document.getElementById('goodbids-socket-error') ||
		document.getElementById('goodbids-fetch-error')
	) {
		// Fallback to PHP block.
		toggleBidBlocks();
		observerInstance.disconnect();
	}
});

// init observer
observer.observe(document, {
	attributes: true,
	childList: true,
	subtree: true,
});

function refreshBidNowBlock() {
	// TODO
	console.log(goodbidsBidNow);
}
