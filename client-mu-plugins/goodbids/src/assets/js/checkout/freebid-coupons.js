const { registerCheckoutFilters } = window.wc.blocksCheckout;

const freeBidCoupons = (coupons) => {
	return coupons.map((coupon) => {
		if (0 > coupon.label.indexOf('gb_freebid_')) {
			return coupon;
		}

		return {
			...coupon,
			label: 'Free Bid',
		};
	});
};

registerCheckoutFilters('goodbids/freebid-coupons', {
	coupons: freeBidCoupons,
});
