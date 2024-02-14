import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['text', 'watchers'];

	static values = {
		id: String,
		state: String,
	};

	connect() {
		this.textTarget.textContent =
			this.stateValue == 0 ? 'Watch' : 'Unwatch';
	}

	toggle() {
		this.textTarget.textContent =
			this.stateValue == 0 ? 'Unwatch' : 'Watch';
		this.stateValue = this.stateValue == 0 ? 1 : 0;
	}

	watch() {
		fetch(window.watchAuctionVars.ajaxUrl, {
			type: 'POST',
			credentials: 'same-origin',
			data: {
				action: 'goodbids_toggle_watching',
				auction: this.idValue,
			},
		})
			.then(function (response) {
				if (!response.success) {
					console.log(response);
					return;
				}

				this.watchersTarget.textContent = response.data.totalWatchers;

				console.log(response);
			})
			.catch((error) => console.error(error));
	}
}
