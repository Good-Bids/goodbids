<?php
/**
 * Provide a licensing plans view for the plugin.
 *
 * This file is used to markup the licensing plans of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

?>

<div class="column has-background-white mr-5 px-5">
	<div>
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Licensing Plans</h2>
	</div>

	<!-- Licensing Plans Section -->
	<div class="pricing-table mt-4">
		<div class="pricing-plan is-warning mr-4">
			<header class="card-header is-shadowless pt-4 px-3">
				<div class="mt-5 ml-5">
					<span class="circle"><i class="fa-solid fa-paper-plane fa-3x"></i></span>
				</div>
				<h3 class="card-header-title is-size-3 mt-1">Trial</h3>
				<p class="is-size-5 mt-5 mr-3 has-text-weight-semibold">Free</p>
			</header>
			<div class="plan-footer mt-6">
				<button class="button is-fullwidth is-rounded" disabled="disabled">Current Plan</button>
			</div>
			<hr>
			<div class="plan-items mb-5">
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Applications Configurable</summary>
								<p class="list-item-description">Only 1 Application</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Endpoints</summary>
								<p class="list-item-description">
								<ul>
									<li>Issuer Endpoint</li>
									<li>Authorization Endpoint</li>
									<li>Token Endpoint</li>
									<li>Userinfo Endpoint</li>
									<li>OpenID Connect Discovery</li>
									<li>JWKS Endpoint</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Authorization Code Grant</summary>
								<p class="list-item-description">
								<ul>
									<li>Authorization Code Grant</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">JWT Signing Algorithms</summary>
								<p class="list-item-description">
								<ul>
									<li>HSA256</li>
									<li>RSA256</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Attribute Mapping</summary>
								<p class="list-item-description">
								<ul>
									<li>Basic Attribute Mapping (Not Customizable)</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Consent Screen Visibility</summary>
								<p class="list-item-description">
								<ul>
									<li>Everytime SSO is attempted</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="pricing-plan is-active ml-4" id="premium-pricing-plan">
			<header class="card-header is-shadowless pt-4 px-3">
				<div class="mt-5 ml-5">
					<span class="circle"><i class="fa-solid fa-shuttle-space fa-3x"></i></span>
				</div>
				<h3 class="card-header-title is-size-3 mt-1 has-text-white">Premium</h3>
				<p class="is-size-3 mt-4 mr-3 has-text-weight-bold has-text-white">$<span id="price-sum">500</span></p>
			</header>
			<div class="plan-price is-size-6 m-3 mb-0 pb-0">
				<div class="control has-icons-left">
					<div class="select is-fullwidth is-active">
						<select class="pricing-select has-text-link-dark">
							<option value="450">1-100 Users - $450 / year</option>
							<option value="550">101-200 Users - $550 / year</option>
							<option value="650">201-300 Users - $650 / year</option>
							<option value="750">301-400 Users - $750 / year</option>
							<option value="850">401-500 Users - $850 / year</option>
						</select>
					</div>
					<span class="icon is-small is-left">
						<i class="fas fa-users"></i>
					</span>
				</div>
			</div>
			<div class="plan-price is-size-6 mx-3 pt-0 mt-0">
				<div class="control has-icons-left">
					<div class="select is-fullwidth is-active">
						<select class="pricing-select has-text-link-dark">
							<option value="50">1 Client Application - $50</option>
							<option value="100">2 Client Applications - $100</option>
							<option value="150">3 Client Applications - $150</option>
							<option value="200">4 Client Applications - $200</option>
							<option value="250">5 Client Applications - $250</option>
							<option value="400">10 Client Applications - $400</option>
							<option value="525">15 Client Applications - $525</option>
							<option value="600">20 Client Applications - $600</option>
						</select>
					</div>
					<span class="icon is-small is-left">
						<i class="fa-solid fa-browser"></i>
					</span>
				</div>
			</div>
			<div class="plan-footer mt-0 pt-0">
				<a href="https://portal.miniorange.com/initializePayment?requestOrigin=wp_oauth_server_enterprise_plan" target="_blank">
					<button class="button is-fullwidth has-background-white has-text-grey has-text-weight-semibold is-rounded is-normal">
						Choose
					</button>
				</a>
			</div>
			<div class="plan-price is-size-6 pt-0 mt-0">
				<p class="has-text-link-light is-size-6">More than 500 users? <a href="admin.php?page=mo_oauth_server_settings&tab=contact_us&ref_page=licensing"><span class="has-text-weight-bold has-text-link">Contact Us</span></a></p>
			</div>
			<hr>
			<div class="plan-items mb-5">
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Applications Configurable</summary>
								<p class="list-item-description">Multiple Applications</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Endpoints</summary>
								<p class="list-item-description">
								<ul>
									<li>Issuer Endpoint</li>
									<li>Authorization Endpoint</li>
									<li>Token Endpoint</li>
									<li>Userinfo Endpoint</li>
									<li>OpenID Connect Discovery</li>
									<li>JWKS Endpoint</li>
									<li>Introspection Endpoint</li>
									<li>OpenID Single Logout Endpoint</li>
									<li>Revoke Endpoint</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Authorization Code Grant</summary>
								<p class="list-item-description">
								<ul>
									<li>Authorization Code Grant</li>
									<li>Password Grant</li>
									<li>Client Credentials Grant</li>
									<li>Implicit Grant</li>
									<li>Refresh Token Grant</li>
									<li>Authorization Code Grant with PKCE</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">JWT Signing Algorithms</summary>
								<p class="list-item-description">
								<ul>
									<li>HS256</li>
									<li>HS384</li>
									<li>HS512</li>
									<li>RS256</li>
									<li>RS384</li>
									<li>RS512</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Attribute Mapping</summary>
								<p class="list-item-description">
								<ul>
									<li>
										<p>Advanced Attribute Mapping <span class="is-italic">(Customize attribute names</span></p>
										<p><span class="is-italic"> and map additional attributes from usermeta table)</span></p>
									</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Consent Screen Visibility</summary>
								<p class="list-item-description">Can be turned OFF</p>
							</details>
						</div>
					</div>
				</div>
				<div class="plan-item">
					<div class="list-item">
						<div class="list-item-content">
							<details>
								<summary class="list-item-title has-text-weight-bold is-size-6">Role/Memberships Mapping</summary>
								<p class="list-item-description">
								<ul>
									<li>Map your WP roles / memberships created </li>
									<li>by various membership plugins</li>
								</ul>
								</p>
							</details>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<br>
</div>

<!-- This div close the parent container of main template. -->
</div>
