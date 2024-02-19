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

	sendRequest(data, callback) {
		// Create a new XMLHttpRequest object
		const request = new XMLHttpRequest();
		console.log(this.ajaxUrlValue);

		// Open a POST request to the admin-ajax.php file
		request.open('POST', this.ajaxUrlValue, true); // TODO: Update Reference.

		// Set the request header for POST requests
		request.setRequestHeader(
			'Content-Type',
			'application/x-www-form-urlencoded; charset=UTF-8',
		);

		request.onreadystatechange = function () {
			if (request.readyState === 4 && request.status === 200) {
				const jsonResponse = JSON.parse(request.responseText);
				callback(jsonResponse);
			}
		};

		// Send the data
		request.send(data);
	}

	watch() {
		const data = new URLSearchParams();
		data.append('action', 'goodbids_toggle_watching');
		data.append('auction', this.idValue);

		const updateWatchers = this.updateWatchers;

		this.sendRequest(data, (response) => {
			if (!response.success) {
				console.log(response);
				return;
			}

			updateWatchers(response.data.totalWatchers);
		});
	}
}
