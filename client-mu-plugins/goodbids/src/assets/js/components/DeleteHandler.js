/* global ajaxurl, jQuery, goodbidsReferral */
import Swal from 'sweetalert2';

const $ = jQuery;
export default class DeleteHandler {
	constructor(userID, referrerID) {
		this.userID = userID;
		this.referrerID = referrerID;
	}

	sendRequest(successCallback, failCallback) {
		Swal.fire({
			title: goodbidsReferral.alert.title,
			text: goodbidsReferral.alert.text,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: goodbidsReferral.alert.confirmText,
			cancelButtonText: goodbidsReferral.alert.cancelText,
		}).then((result) => {
			if (result.isConfirmed) {
				const url = ajaxurl || goodbidsReferral.ajaxURL;
				$.post(url, this.getData(), (response) => {
					if (response.success) {
						successCallback(response);
						this.onSuccess();
					} else {
						failCallback(response);
					}
				}).fail(this.onRequestFailure);
			} else {
				failCallback({ event: 'canceled' });
			}
		});
	}

	getData() {
		return {
			action: 'goodbids_referral_delete_referral',
			user_id: this.userID,
			referrer_id: this.referrerID,
			nonce: goodbidsReferral.nonceDelete,
		};
	}

	onSuccess() {
		Swal.fire(
			goodbidsReferral.confirmedAlert.title,
			goodbidsReferral.confirmedAlert.text,
			'success',
		);
	}
}
