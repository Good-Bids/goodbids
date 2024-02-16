<?php
/**
 * Watch Auction Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

// TODO: Remove Me.
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_register_script(
			'goodbids-watch-auction-block',
			GOODBIDS_PLUGIN_URL . 'blocks/watch-auction/view.js',
			[],
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
