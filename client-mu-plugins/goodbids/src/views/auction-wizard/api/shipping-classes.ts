import { useQuery } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

export type ShippingClasses = {
	count: number;
	description: string;
	id: number;
	name: string;
	slug: string;
}[];

export function useGetShippingClasses() {
	return useQuery({
		queryKey: ['shipping-classes'],
		queryFn: async () =>
			apiFetch<ShippingClasses>({
				path: '/wc/v3/products/shipping_classes',
			}),
	});
}
