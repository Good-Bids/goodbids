<?php

/**
 * Provide a welcome page view for the plugin.
 *
 * This file is used to markup the welcome page of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

?>
<div class="column has-background-white mr-5 pt-0">
	<div class="mb-4">
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Select your Application</h2>
	</div>

	<p class="control has-icons-left">
		<input id="client-search-input-box" class="input" type="text" placeholder="Search your application">
		<span class="icon is-left">
			<i class="fas fa-search" aria-hidden="true"></i>
		</span>
	</p>

	<div class="container">
		<form id="choose-client-form" name="choose-client-form" action="" method="POST">
			<div class="columns mt-4 is-multiline">

				<input id="selected-client" type="hidden" name="selected-client">
				<?php foreach ($oauth_client_list_json_data as $client_name => $client_fields) : ?>
					<div onClick="moHandleClientClick(this);" id="<?php echo esc_attr($client_name); ?>" class="column is-one-fifth-widescreen is-clickable is-one-quarter-mobile">
						<figure class="image mx-auto is-rounded is-96x96 miniorange-oauth-20-server-logo is-flex is-align-items-center is-justify-content-center">
							<img src="<?php echo esc_attr(MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL) . 'assets/' . esc_attr($client_fields['image']); ?>">
						</figure>
						<p class="has-text-centered mt-2 is-size-6 has-text-weight-normal client-name"><?php echo esc_attr($client_fields['label']); ?></p>
					</div>
				<?php endforeach; ?>

			</div>
		</form>
	</div>
	<br>
	<div class="container" id="client-not-listed-box" style="background-color: #f4f0ff; padding: 3px;">
		<p class="is-size-6">Please configure custom client as your client is not listed.</p>
	</div>
</div>


<!-- This div close the parent container of main template. -->
</div>

<script>
	function moHandleClientClick(element) {
		document.getElementById('selected-client').setAttribute('value', element.id);
		document.getElementById('choose-client-form').submit();
	}

	const clientSearchInputBox = document.querySelector('#client-search-input-box');
	clientSearchInputBox.addEventListener('keyup', function(event) {
		let clientSearchInputBoxValue = event.target.value;
		clientSearchInputBoxValue = clientSearchInputBoxValue.toLowerCase();

		// match this value with the client name.
		const client_names = document.querySelectorAll('.client-name');
		let flag = false;
		client_names.forEach(client_name => {
			let client_nameValue = client_name.innerText;
			client_nameValue = client_nameValue.toLowerCase();
			if (client_nameValue.includes(clientSearchInputBoxValue)) {
				client_name.parentElement.style.display = 'block';
				flag = true;
			} else {
				client_name.parentElement.style.display = 'none';
			}
		});
		if (!flag) {
			clientNotListedBox.style.display = 'block';
			const client1 = document.getElementById('oauth2');
			const client2 = document.getElementById('openidconnect');
			client1.style.display = 'block';
			client2.style.display = 'block';
		} else {
			clientNotListedBox.style.display = 'none';
		}
	});

	const clientNotListedBox = document.querySelector('#client-not-listed-box');
	const clientNotListedButton = clientNotListedBox.querySelector('button');
	clientNotListedBox.style.display = 'none';
</script>
