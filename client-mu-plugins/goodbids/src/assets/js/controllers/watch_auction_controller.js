import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['text', 'watchers'];

	static values = {
		id: String,
		state: String,
	};

	connect() {}

	toggle() {
		this.watch();
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
		const params = new URLSearchParams();
		params.append('action', 'goodbids_toggle_watching');
		params.append('auction', this.idValue);

		fetch(window.watchAuctionVars.ajaxUrl, {
			method: 'POST',
			credentials: 'include',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: params,
		})
			.then(function (response) {
				if (!response.success) {
					console.log(response);
					return;
				}

				document.querySelector(
					'data-watch-auction-target="watchers"',
				).textContent = response.data.totalWatchers;

				console.log(response.data.totalWatchers);
			})
			.catch((error) => console.error(error));
	}
}
