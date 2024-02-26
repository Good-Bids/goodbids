const { registerCheckoutFilters } = window.wc.blocksCheckout;

const freeBidCoupons = (coupons) => {
	return coupons.map((coupon) => {
		if (!coupon.label.startsWith('gb_freebid_')) {
			return coupon;
		}

		return {
			...coupon,
			label: 'Free Bid',
		};
	});
};

if (registerCheckoutFilters) {
	registerCheckoutFilters('goodbids/freebid-coupons', {
		coupons: freeBidCoupons,
	});
}
