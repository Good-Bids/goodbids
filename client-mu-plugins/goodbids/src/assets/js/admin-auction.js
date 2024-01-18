/* global jQuery, ajaxurl */

const GBAdminAuction = (($) => {
	'use strict';

	const init = () => {
		forceAuctionCloseDate();
	};

	const forceAuctionCloseDate = () => {
		$('a[href="#gb-force-update-close-date"]').on('click', function (e) {
			e.preventDefault();

			const $btn = $(this);

			if (
				!confirm(
					'Are you sure you want to force the End Date as the Auction Close Date?',
				)
			) {
				return false;
			}

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'goodbids_force_auction_close_date',
					auction_id: $(this).data('auction-id'),
					gb_nonce: $(this).data('nonce'),
				},
				success: function (response) {
					if (response.success) {
						alert('Auction Close Date has been updated.');
						$('#gb-close-date').text(response.data.closeDate);
						$btn.fadeOut('fast');
					} else {
						console.log(response);
						alert(
							'There was an error updating the Auction Close Date.',
						);
					}
				},
				error: function (response) {
					console.log(response);
					alert(
						'There was an error updating the Auction Close Date.',
					);
				},
			});

			return false;
		});
	};

	return {
		init,
	};
})(jQuery);

jQuery(window).on('load', GBAdminAuction.init);
