/* global ajaxurl, jQuery, goodbidsReferral */

const $ = jQuery;

export default class AddHandler {
	constructor(userID, referrerID) {
		this.userID = userID;
		this.referrerID = referrerID;
	}

	sendRequest(successCallback, failCallback) {
		const url = ajaxurl || goodbidsReferral.ajaxUrl;
		console.log(url);
		$.post(url, this.getData(), (response) => {
			if (response.success) {
				successCallback(response);
			} else {
				failCallback(response);
			}
		}).fail(failCallback);
	}

	getData() {
		return {
			action: 'goodbids_referrals_add_referral',
			user_id: this.userID,
			referrer_id: this.referrerID,
			nonce: goodbidsReferral.nonceAdd,
		};
	}
}
