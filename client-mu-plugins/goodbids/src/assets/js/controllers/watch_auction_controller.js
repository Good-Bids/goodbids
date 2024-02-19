import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['text'];

	static values = {
		id: Number,
		state: Number,
		watchText: String,
		unwatchText: String,
		watchClass: String,
		unwatchClass: String,
		ajaxUrl: String,
	};

	toggle() {
		this.watch();
		this.toggleState();
	}

	toggleState() {
		if (!this.stateValue) {
			this.stateValue = 1;
			this.textTarget.textContent = this.unwatchTextValue;
		} else {
			this.stateValue = 0;
			this.textTarget.textContent = this.watchTextValue;
		}

		this.element.classList.toggle(this.watchClassValue);
		this.element.classList.toggle(this.unwatchClassValue);
	}

	updateWatchers(string) {
		const watchers = document.querySelector(
			'[data-auction-watchers-count]',
		);
		watchers.textContent = string;
	}

	async sendRequest(data) {
		const response = await fetch(this.ajaxUrlValue, {
			method: 'POST',
			headers: {
				'Content-Type':
					'application/x-www-form-urlencoded; charset=UTF-8',
			},
			body: data,
		});

		// Checks if the response is within the 200 range
		if (!response.ok) {
			throw new Error('Something happened');
		}

		// The response is returned instead of a callback being used.
		return await response.json();
	}

	async watch() {
		const data = new URLSearchParams();
		data.append('action', 'goodbids_toggle_watching');
		data.append('auction', this.idValue);

		const response = await this.sendRequest(data);

		if (!response.success) {
			console.log(response);
			return;
		}

		this.updateWatchers(response.data.totalWatchers);
	}
}
