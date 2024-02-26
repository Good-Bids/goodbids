if ('wc' in window) {
	const { registerCheckoutFilters } = window.wc.blocksCheckout;

	const rewardCoupons = (coupons) => {
		return coupons.map((coupon) => {
			if (!coupon.label.startsWith('gb_reward_')) {
				return coupon;
			}

			return {
				...coupon,
				label: 'Auction Reward',
			};
		});
	};

	registerCheckoutFilters('goodbids/reward-coupons', {
		coupons: rewardCoupons,
	});
}
