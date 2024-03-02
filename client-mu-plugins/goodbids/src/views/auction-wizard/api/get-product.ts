import { useQuery } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

export type ProductResponse = {
	id: number;
	name: string;
	regular_price: string;
	images: {
		id: number;
		src: string;
	}[];
	categories: {
		id: number;
		name: string;
		slug: string;
	}[];
	weight: string;
	dimensions: {
		length: string;
		width: string;
		height: string;
	};
	shipping_class: string;
	purchase_note: string;
};

export function useGetProduct(id: number) {
	return useQuery({
		queryKey: ['product', id],
		queryFn: async () =>
			apiFetch<ProductResponse>({
				path: `/wc/v3/products/${id}`,
			}),
	});
}
