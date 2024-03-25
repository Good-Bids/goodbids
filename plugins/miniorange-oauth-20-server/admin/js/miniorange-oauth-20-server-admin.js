document.addEventListener(
	'DOMContentLoaded',
	() => {
		// Functions to open and close a modal
		function openModal($el) {
			$el.classList.add('is-active');
		}

		function closeModal($el) {
			$el.classList.remove('is-active');
		}

		function closeAllModals() {
			(document.querySelectorAll('.modal') || []).forEach(
				($modal) => {
					closeModal($modal);
				}
			);
		}

		// Add a click event on buttons to open a specific modal
		(document.querySelectorAll('.js-modal-trigger') || []).forEach(
			($trigger) => {
				const modal = $trigger.dataset.target;
				const $target = document.getElementById(modal);
				$trigger.addEventListener(
					'click',
					() => {
						openModal($target);
					}
				);
			}
		);

		// Add a click event on various child elements to close the parent modal
		(document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button-cancel') || []).forEach(
			($close) => {
				const $target = $close.closest('.modal');
				$close.addEventListener(
					'click',
					() => {
						closeModal($target);
					}
				);
			}
		);

		// Add a keyboard event to close all modals
		document.addEventListener(
			'keydown',
			(event) => {
				const e = event || window.event;
				if (e.keyCode === 27) { // Escape key
					closeAllModals();
				}
			}
		);

		// js for countries flag
		jQuery("#contact_us_phone").intlTelInput({nationalMode:!1});

		// Show dynamic pricing
		jQuery(document).on(
			'change',
			'.pricing-select',
			function () {
				var sum = 0;

				jQuery("select.pricing-select option:selected").each(
					function () {
						if (jQuery(this).val()) {
							sum += Number(jQuery(this).val());
						}
						document.getElementById('price-sum').innerHTML = sum;
					}
				);
			}
		);

		// Copy to Clipboard
		let endpoints = document.getElementsByClassName("endpoint");
		for (var i = 0; i < endpoints.length; i++) {
			let copyIcon = endpoints[i].nextElementSibling.children[0].children[0].children[0];
			copyIcon.addEventListener(
				'click',
				function () {
					var endpointText = this.parentNode.parentNode.parentNode.previousElementSibling.innerText;
					if (endpointText == '') {
						endpointText = this.parentNode.parentNode.parentNode.previousElementSibling.children[0].value;
					}
					navigator.clipboard.writeText(endpointText);
				}
			);
		}

		// Change eye icon tooltip text
		var eyeTooltip = document.getElementById("eye-tooltip");
		if(eyeTooltip){
			eyeTooltip.addEventListener(
				'click',
				function () {
					eyeTooltip.getAttribute('data-tooltip') == 'Show Client Secret' ? eyeTooltip.setAttribute('data-tooltip', 'Hide Client Secret') : eyeTooltip.setAttribute('data-tooltip', 'Show Client Secret');
				}
			);
		}

		// Change copy icon tooltip text
		var copyTooltips = document.getElementsByClassName("copy-tooltip");
		Array.from(copyTooltips).forEach(function (copyTooltip) {
			copyTooltip.addEventListener(
				'click',
				() => {
					var copyTooltipText = copyTooltip.parentElement.dataset.tooltip;
					if (copyTooltipText.includes('Copy')) {
						copyTooltip.parentElement.dataset.tooltip = 'Copied!';
						setTimeout(function () {
							copyTooltip.parentElement.dataset.tooltip = copyTooltipText;
						}, 3000);
					}
				}
			);
		});

		// Show or Hide client secret
		let clientSecretTable = document.getElementById("client-secret");
		let eyeIcon = document.getElementById("eye_icon");
		if(eyeIcon){
			eyeIcon.addEventListener(
				'click',
				function () {
					if (clientSecretTable.type == "password") {
						clientSecretTable.type = "text";
						eyeIcon.className = "fa-solid fa-eye-slash";
					} else {
						clientSecretTable.type = "password";
						eyeIcon.className = "fa-solid fa-eye";
					}
				}
			);
		}
	}
);
