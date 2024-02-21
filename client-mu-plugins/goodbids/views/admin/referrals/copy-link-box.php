<?php
/**
 * Copy link to Clipboard
 *
 * @global Referrer $referrer
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Users\Referrals\Referrer;

?>
<div class="goodbids-referrals-copy-link">
	<div class="table w-full input-group">
		<div class="relative inline-block w-full h-12 tooltip">
			<span class="absolute z-10 hidden text-center tooltiptext left-1/2 py-1 px-0 -ml-[60px] w-32 bottom-[120%] bg-black text-white rounded-xs after:content-[''] after:-ml-1 after:absolute after:top-full after:left-1/2 after:border-8 after:border-solid after:border-t-black after:border-x-transparent after:border-b-transparent">
				<?php esc_html_e( 'Copied!', 'goodbids' ); ?>
			</span>
			<label>
				<span class="screen-reader-text"><?php esc_html_e( 'Your Custom Share Link', 'goodbids' ); ?></span>
				<input class="text-inherit relative table-cell w-full p-0 m-0 overflow-scroll text-left outline-none copy-link-input pl-6 h-[48px] bg-white text-[1em] !text-gray-400 transition-opacity duration-100 rounded-l-xs !rounded-r-none border border-gray-600 border-solid !border-r-0" readonly type="text" value="<?php echo esc_url( $referrer->get_link() ); ?>">
			</label>
		</div>

		<span class="table-cell w-1/12 align-middle input-group-button">
			<a
				href="#"
				class="relative inline-flex items-center justify-center p-0 m-0 appearance-none cursor-pointer select-none btn-copy whitespace-nowrap h-[50px] px-4 bg-gray-800 rounded-r-xs border border-gray-400 text-gray-100 font-bold text-['1.5em'] transition-colors duration-100 hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900"
				data-clipboard="<?php echo esc_url( $referrer->get_link() ); ?>"
				data-silent="1"
				title="<?php esc_attr_e( 'Copy to Clipboard', 'goodbids' ); ?>"
			>
				<span class="sr-only"><?php esc_attr_e( 'Copy to Clipboard', 'goodbids' ); ?></span>
				<svg fill="currentColor" class="w-6 h-5 align-middle" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 115.77 122.88" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M89.62,13.96v7.73h12.19h0.01v0.02c3.85,0.01,7.34,1.57,9.86,4.1c2.5,2.51,4.06,5.98,4.07,9.82h0.02v0.02 v73.27v0.01h-0.02c-0.01,3.84-1.57,7.33-4.1,9.86c-2.51,2.5-5.98,4.06-9.82,4.07v0.02h-0.02h-61.7H40.1v-0.02 c-3.84-0.01-7.34-1.57-9.86-4.1c-2.5-2.51-4.06-5.98-4.07-9.82h-0.02v-0.02V92.51H13.96h-0.01v-0.02c-3.84-0.01-7.34-1.57-9.86-4.1 c-2.5-2.51-4.06-5.98-4.07-9.82H0v-0.02V13.96v-0.01h0.02c0.01-3.85,1.58-7.34,4.1-9.86c2.51-2.5,5.98-4.06,9.82-4.07V0h0.02h61.7 h0.01v0.02c3.85,0.01,7.34,1.57,9.86,4.1c2.5,2.51,4.06,5.98,4.07,9.82h0.02V13.96L89.62,13.96z M79.04,21.69v-7.73v-0.02h0.02 c0-0.91-0.39-1.75-1.01-2.37c-0.61-0.61-1.46-1-2.37-1v0.02h-0.01h-61.7h-0.02v-0.02c-0.91,0-1.75,0.39-2.37,1.01 c-0.61,0.61-1,1.46-1,2.37h0.02v0.01v64.59v0.02h-0.02c0,0.91,0.39,1.75,1.01,2.37c0.61,0.61,1.46,1,2.37,1v-0.02h0.01h12.19V35.65 v-0.01h0.02c0.01-3.85,1.58-7.34,4.1-9.86c2.51-2.5,5.98-4.06,9.82-4.07v-0.02h0.02H79.04L79.04,21.69z M105.18,108.92V35.65v-0.02 h0.02c0-0.91-0.39-1.75-1.01-2.37c-0.61-0.61-1.46-1-2.37-1v0.02h-0.01h-61.7h-0.02v-0.02c-0.91,0-1.75,0.39-2.37,1.01 c-0.61,0.61-1,1.46-1,2.37h0.02v0.01v73.27v0.02h-0.02c0,0.91,0.39,1.75,1.01,2.37c0.61,0.61,1.46,1,2.37,1v-0.02h0.01h61.7h0.02 v0.02c0.91,0,1.75-0.39,2.37-1.01c0.61-0.61,1-1.46,1-2.37h-0.02V108.92L105.18,108.92z"/></g></svg>
			</a>
			<span class="sr-only tooltip-sr-text" role="alert"></span>
		</span>

	</div>
</div>
