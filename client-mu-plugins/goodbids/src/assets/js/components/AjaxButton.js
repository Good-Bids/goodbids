export default class AjaxButton {
	constructor(button, handler) {
		this.button = button;
		this.handler = handler;
	}

	handle(successCallback, failCallback) {
		this.handler.sendRequest(successCallback, failCallback);
	}

	disable() {
		this.button.attr('disabled', true);
		return this;
	}

	enable() {
		this.button.attr('disabled', false);
		return this;
	}

	changeTextTo(text) {
		this.button.text(text);
		return this;
	}

	loading(text) {
		this.button.html(text);
		this.button.prepend('<span class="spinner is-active"></span>');
		return this;
	}

	/**
	 * @param {string|number} text
	 */
	success(text) {
		this.button.text(text);
		this.button
			.prepend(' <i class="fas fa-check-circle"></i> ')
			.toggleClass('btn-dark-blue')
			.addClass('btn-success');
		return this;
	}

	/**
	 * @param {string|number} text
	 */
	fail(text) {
		this.button.text(text);
		this.button
			.text(text)
			.prepend(' <i class="fas fa-exclamation"></i> ')
			.toggleClass('btn-dark-blue')
			.addClass('btn-danger');
		return this;
	}
}
