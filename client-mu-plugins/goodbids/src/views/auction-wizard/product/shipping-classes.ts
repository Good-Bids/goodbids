import { useQuery } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

type GetShippingClassesSuccessResponse = {
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
			apiFetch<GetShippingClassesSuccessResponse>({
				path: '/wc/v3/products/shipping_classes',
			}),
	});
}
