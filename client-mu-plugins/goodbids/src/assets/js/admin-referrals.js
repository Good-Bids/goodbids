/* global jQuery, goodbidsReferral */

import AjaxButton from './components/AjaxButton';
import DeleteHandler from './components/DeleteHandler';
import AddHandler from './components/AddHandler';
import Swal from 'sweetalert2';

const $ = jQuery;

$(() => {
	$('.goodbids-referrals-delete').on('click', (e) => {
		e.preventDefault();
		const ajaxButton = new AjaxButton(
			$(this),
			new DeleteHandler(
				$(this).data('user-id'),
				$(this).data('referrer-id'),
			),
		);
		ajaxButton.disable().loading(null);
		ajaxButton.handle(
			() => {
				ajaxButton.disable();
				ajaxButton.button.parent().remove();
			},
			(response) => {
				if (response.event === 'canceled') {
					ajaxButton.enable().changeTextTo('Remove');
					return;
				}
				ajaxButton.changeTextTo('Failed!');
			},
		);
		// send an ajax request to delete relation
	});

	const addReferralButton = $('#goodbids-referrals-add-button');
	addReferralButton.attr('disabled', true); // disable by default
	const searchUserSelect = $('#goodbids-referrals-search-user-select');
	// enable button on user select
	searchUserSelect.on('select2:select', function (e) {
		const userID = e.params.data.id; // this is the user_id

		if (userID == -1) {
			addReferralButton.attr('disabled', true);
		} else {
			addReferralButton.attr('disabled', false);
		}
	});

	addReferralButton.on('click', function (e) {
		e.preventDefault();

		const referrerID = $(this).data('referrer-id');
		let toAddUserId = searchUserSelect.val();

		const addReferralAjaxButton = new AjaxButton(
			$(this),
			new AddHandler(toAddUserId, referrerID),
		);

		addReferralAjaxButton.disable();

		addReferralAjaxButton.handle(
			() => {
				window.location.reload();
			},
			(response) => {
				addReferralAjaxButton.enable();
				Swal.fire(
					goodbidsReferral.alert.error,
					response.data.error,
					'error',
				);
			},
		);
	});
});
