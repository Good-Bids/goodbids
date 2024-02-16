import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['text'];

	static values = {
		id: Number,
		state: Number,
		watch: String,
		unwatch: String,
		watchClass: String,
		unwatchClass: String,
	};

	connect() {}

	toggle() {
		this.watch();
		this.toggleState();

		this.element.classList.toggle(this.watchClassValue);
		this.element.classList.toggle(this.unwatchClassValue);
	}

	toggleState() {
		if (!this.stateValue) {
			this.stateValue = 1;
			this.updateText(this.unwatchValue);
		} else {
			this.stateValue = 0;
			this.updateText(this.watchValue);
		}
	}

	updateText(string) {
		this.textTarget.textContent = string;
	}

	updateWatchers(string) {
		const watchers = document.querySelector('.auction-watchers-count');
		watchers.textContent = string;
	}

	sendRequest(data, callback) {
		// Create a new XMLHttpRequest object
		const xhr = new XMLHttpRequest();

		// Open a POST request to the admin-ajax.php file
		xhr.open('POST', window.watchAuctionVars.ajaxUrl, true); // TODO: Update Reference.

		// Set the request header for POST requests
		xhr.setRequestHeader(
			'Content-Type',
			'application/x-www-form-urlencoded; charset=UTF-8',
		);

		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4 && xhr.status === 200) {
				const jsonResponse = JSON.parse(xhr.responseText);
				callback(jsonResponse);
			}
		};

		// Send the data
		xhr.send(data);
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
