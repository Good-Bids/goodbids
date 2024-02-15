<?php
/**
 * Watch Auction Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_register_script(
			'goodbids-watch-auction-block',
			GOODBIDS_PLUGIN_URL . 'blocks/watch-auction/view.js',
			[ 'jquery' ],
			goodbids()->get_version(),
			true
		);

		wp_localize_script(
			'goodbids-watch-auction-block',
			'watchAuctionVars',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			]
		);
	}
);
