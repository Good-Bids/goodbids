import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['text', 'watchers'];

	static values = {
		id: String,
		state: String,
	};

	connect() {}

	toggle() {
		if (this.stateValue == 0) {
			this.stateValue = 1;
			this.textTarget.textContent = 'Unwatch';
		} else {
			this.stateValue = 0;
			this.textTarget.textContent = 'Watch';
		}
		this.element.classList.toggle('btn-fill-secondary');
		this.element.classList.toggle('btn-fill');
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
