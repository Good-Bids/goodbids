<?php
/**
 * Donation Verification Dialog
 *
 * @global BidNow $bid_now
 *
 * @since 1.0.1
 * @package GoodBids
 */

use GoodBids\Blocks\BidNow;

?>
<div class="fixed inset-0 left-0 top-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden outline-none focus:outline-none">
	<dialog class="opacity-1 absolute overflow-hidden rounded-xl border-red-50 p-12 text-left shadow-xl sm:my-8 sm:w-[384px] sm:max-w-lg" open="">
		<div class="flex-col sm:flex sm:items-start">
			<svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect width="72" height="72" rx="36" fill="#D9FFD2"></rect>
				<path d="M37.6655 44.3332C37.6655 43.4139 36.9203 42.6688 36.001 42.6688C35.0818 42.6688 34.3366 43.4139 34.3366 44.3332C34.3366 45.2524 35.0818 45.9976 36.001 45.9976C36.9203 45.9976 37.6655 45.2524 37.6655 44.3332ZM37.2335 31.2444C37.1502 30.6344 36.6268 30.1645 35.994 30.165C35.3037 30.1656 34.7445 30.7257 34.745 31.416L34.751 38.9187L34.7626 39.0883C34.8458 39.6983 35.3692 40.1682 36.002 40.1677C36.6924 40.1671 37.2516 39.607 37.251 38.9167L37.245 31.414L37.2335 31.2444ZM39.2821 22.0981C37.855 19.5184 34.1464 19.5184 32.7194 22.0981L19.8106 45.4339C18.428 47.9333 20.2357 50.9991 23.092 50.9991H48.9104C51.7667 50.9991 53.5744 47.9332 52.1917 45.4338L39.2821 22.0981ZM34.907 23.3083C35.3826 22.4484 36.6188 22.4483 37.0945 23.3082L50.0041 46.644C50.465 47.4771 49.8625 48.4991 48.9104 48.4991H23.092C22.1399 48.4991 21.5373 47.4772 21.9982 46.644L34.907 23.3083Z" fill="#0A3624"></path>
			</svg>
			<div class="mt-3 text-center sm:mt-0 sm:text-left">
				<h3 class="font-bolder text-base text-lg leading-6 text-gray-900">Every Bid is a Donation</h3>
				<div class="mt-2">
					<label for="donation-acknowledgement" class="block text-sm text-gb-green-700">
						Welcome! This is your first bid, and we want to remind you that all bids are non-refundable donations. Please type the word DONATION below to confirm that you understand that bids go directly to the charity and are non-refundable.
					</label>
				</div>
			</div>
		</div>
		<form class="flex w-full flex-col gap-4">
			<input id="donation-acknowledgement" required="" pattern="[Dd][Oo][Nn][Aa][Tt][Ii][Oo][Nn]" placeholder="type DONATION in this field to proceed" class="rounded border border-solid border-transparent bg-gray-100 px-6 py-2 leading-normal no-underline focus:outline-dotted focus:outline-1 focus:outline-offset-2">
			<input type="submit" class="btn-fill w-full text-md" value="I understand">
		</form>
	</dialog>
</div>

