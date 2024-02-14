/* global jQuery, watchAuctionVars */

const GBWatchAuction = (($) => {
	'use strict';

	const init = () => {
		bind_toggle();
	};

	const bind_toggle = () => {
		$('[data-controller="watch-auction"]').on('click', function (e) {
			e.preventDefault();

			const $btn = $(this);

			$.ajax({
				url: watchAuctionVars.ajaxUrl,
				type: 'POST',
				data: {
					action: 'goodbids_toggle_watching',
					auction: $btn.attr('data-auction'),
				},
				success: function (response) {
					if (!response.success) {
						console.log(response);
						return;
					}

					$('[data-watch-auction-target="watchers"]').text(
						response.data.totalWatchers,
					);
				},
				error: function (response) {
					console.log(response);
				},
			});

			return false;
		});
	};

	return {
		init,
	};
})(jQuery);

jQuery(window).on('load', GBWatchAuction.init);
