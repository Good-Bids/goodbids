/* global jQuery, goodbidsFreeBids */

import Swal from 'sweetalert2';

const $ = jQuery;

$(() => {
	const grantFreeBidButton = $('#goodbids-grant-free-bid-button');
	grantFreeBidButton.attr('disabled', true); // disable by default
	const freeBidReason = $('#' + goodbidsFreeBids.reasonFieldId);
	freeBidReason.on('keyup', function () {
		if (!freeBidReason.val()) {
			grantFreeBidButton.attr('disabled', true);
		} else {
			grantFreeBidButton.attr('disabled', false);
		}
	});

	grantFreeBidButton.on('click', function (e) {
		e.preventDefault();

		const userId = $(this).data('user-id');
		const reason = freeBidReason.val();

		if (!reason) {
			Swal.fire(
				goodbidsFreeBids.validationAlert.title,
				goodbidsFreeBids.validationAlert.text,
				'error',
			);
			return false;
		}

		grantFreeBidButton.attr('disabled', true);
		freeBidReason.attr('disabled', true);

		$.ajax({
			url: goodbidsFreeBids.ajaxUrl,
			type: 'POST',
			data: {
				action: goodbidsFreeBids.grantAction,
				nonce: goodbidsFreeBids.nonceGrant,
				userid: userId,
				reason,
			},
			success: function (response) {
				freeBidReason.attr('disabled', false);

				if ('undefined' !== typeof response.data.error) {
					grantFreeBidButton.attr('disabled', false);

					Swal.fire(
						goodbidsFreeBids.errorAlert.title,
						response.data.error,
						'success',
					);
					return;
				}

				grantFreeBidButton.attr('disabled', true);
				freeBidReason.val('');

				Swal.fire(
					goodbidsFreeBids.confirmedAlert.title,
					goodbidsFreeBids.confirmedAlert.text,
					'success',
				);
			},
		});
	});
});
