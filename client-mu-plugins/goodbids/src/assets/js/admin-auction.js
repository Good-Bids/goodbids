/* global jQuery, ajaxurl */

const GBAdminAuction = (($) => {
	'use strict';

	const init = () => {
		forceAuctionCloseDate();
		generateAuctionInvoice();
		createStripeInvoice();
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

	const generateAuctionInvoice = () => {
		$('a[href="#gb-generate-invoice"]').on('click', function (e) {
			e.preventDefault();

			const $btn = $(this);

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'goodbids_generate_invoice',
					auction_id: $(this).data('auction-id'),
					gb_nonce: $(this).data('nonce'),
				},
				success: function (response) {
					if (response.success) {
						alert('Auction Invoice has been generated.');
						$btn.fadeOut('fast');
					} else {
						console.log(response);
						alert(
							'There was an error generating the Auction Invoice.',
						);
					}
				},
				error: function (response) {
					console.log(response);
					alert('There was an error generating the Auction Invoice.');
				},
			});

			return false;
		});
	};

	const createStripeInvoice = () => {
		$('a[href="#gb-create-stripe-invoice"]').on('click', function (e) {
			e.preventDefault();

			const $btn = $(this);

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'goodbids_create_stripe_invoice',
					invoice_id: $(this).data('invoice-id'),
					gb_nonce: $(this).data('nonce'),
				},
				success: function (response) {
					if (response.success) {
						alert('Stripe Invoice has been regenerated.');
						$btn.fadeOut('fast');
					} else {
						console.log(response);
						alert(
							'There was an error generating the Stripe Invoice.',
						);
					}
				},
				error: function (response) {
					console.log(response);
					alert('There was an error generating the Stripe Invoice.');
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
