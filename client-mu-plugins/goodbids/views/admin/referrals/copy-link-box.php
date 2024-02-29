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
			<span class="absolute z-10 hidden text-center tooltiptext left-1/2 py-1 px-0 -ml-[60px] w-32 bottom-[120%] bg-contrast-3 text-contrast rounded after:content-[''] after:-ml-1 after:absolute after:top-full after:left-1/2 after:border-8 after:border-solid after:border-t-contrast-3 after:border-x-transparent after:border-b-transparent">
				<?php esc_html_e( 'Copied!', 'goodbids' ); ?>
			</span>
			<label>
				<span class="screen-reader-text"><?php esc_html_e( 'Your Custom Share Link', 'goodbids' ); ?></span>
				<input class="relative table-cell w-full p-0 m-0 overflow-scroll bg-contrast-3 !border-0 text-left outline-none copy-link-input pl-6 h-[48px] text-[1rem] !text-contrast transition-opacity duration-100 rounded-l !rounded-r-none" readonly type="text" value="<?php echo esc_url( $referrer->get_link() ); ?>">
			</label>
		</div>

		<span class="table-cell w-1/12 align-middle input-group-button">
			<button
				class="relative border-0 inline-flex items-center justify-center h-12 gap-2 p-0 !px-6 m-0 font-bold no-underline transition-colors duration-100 rounded-r appearance-none cursor-pointer select-none border-contrast-3 bg-contrast-3 btn-copy whitespace-nowrap hover:bg-base-3 hover:text-contrast focus:bg-base-3 focus:text-contrast"
				data-clipboard="<?php echo esc_url( $referrer->get_link() ); ?>"
				data-silent="1"
				title="<?php esc_attr_e( 'Copy Link', 'goodbids' ); ?>"
			>
				<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path id="Shape" d="M15.9862 4.12273C15.8616 2.99801 14.9079 2.12329 13.75 2.12329H10.25C9.09205 2.12329 8.13841 2.99801 8.01379 4.12273L6.25 4.12329C5.00736 4.12329 4 5.13065 4 6.37329V19.8733C4 21.1159 5.00736 22.1233 6.25 22.1233H10.9996C10.6634 21.6756 10.4005 21.1697 10.2289 20.6233H6.25C5.83579 20.6233 5.5 20.2875 5.5 19.8733V6.37329C5.5 5.95908 5.83579 5.62329 6.25 5.62329L8.37902 5.62349C8.78267 6.22639 9.46997 6.62329 10.25 6.62329H13.75C14.5284 6.62329 15.2145 6.228 15.6185 5.6272H17.5023V5.62331L17.75 5.62329C18.1642 5.62329 18.5 5.95908 18.5 6.37329V14.1233H20V6.37329C20 5.13065 18.9926 4.12329 17.75 4.12329L15.9862 4.12273ZM10.25 3.62329H13.75C14.1642 3.62329 14.5 3.95908 14.5 4.37329C14.5 4.7875 14.1642 5.12329 13.75 5.12329H10.25C9.83579 5.12329 9.5 4.7875 9.5 4.37329C9.5 3.95908 9.83579 3.62329 10.25 3.62329ZM19 15.1233C21.2091 15.1233 23 16.9142 23 19.1233C23 21.2655 21.316 23.0144 19.1996 23.1184L19 23.1233C18.5858 23.1233 18.25 22.7875 18.25 22.3733C18.25 21.9936 18.5322 21.6798 18.8982 21.6301L19 21.6233C20.3807 21.6233 21.5 20.504 21.5 19.1233C21.5 17.7978 20.4685 16.7133 19.1644 16.6286L19 16.6233C18.5858 16.6233 18.25 16.2875 18.25 15.8733C18.25 15.4936 18.5322 15.1798 18.8982 15.1301L19 15.1233ZM15 15.1233C15.4142 15.1233 15.75 15.4591 15.75 15.8733C15.75 16.253 15.4678 16.5668 15.1018 16.6164L15 16.6233C13.6193 16.6233 12.5 17.7426 12.5 19.1233C12.5 20.4488 13.5315 21.5333 14.8356 21.618L15 21.6233C15.4142 21.6233 15.75 21.9591 15.75 22.3733C15.75 22.753 15.4678 23.0668 15.1018 23.1164L15 23.1233C12.7909 23.1233 11 21.3324 11 19.1233C11 16.9811 12.684 15.2322 14.8004 15.1282L15 15.1233ZM15.25 18.3733H18.75C19.1642 18.3733 19.5 18.7091 19.5 19.1233C19.5 19.503 19.2178 19.8168 18.8518 19.8664L18.75 19.8733H15.25C14.8358 19.8733 14.5 19.5375 14.5 19.1233C14.5 18.7436 14.7822 18.4298 15.1482 18.3801L15.25 18.3733Z" fill="currentColor"/>
				</svg>
				<span class="text-xs font-semibold"><?php esc_attr_e( 'Copy Link', 'goodbids' ); ?></span>
			</button>
			<span class="sr-only tooltip-sr-text" role="alert"></span>
		</span>

	</div>
</div>
