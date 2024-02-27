import { useQuery } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

type GetProductCategoriesSuccessResponse = {
	id: number;
	name: string;
	slug: string;
	parent: number;
	description: string;
	display: string;
	image: [];
	menu_order: number;
	count: number;
	_links: {
		self: [
			{
				href: string;
			},
		];
		collection: [
			{
				href: string;
			},
		];
		up: [
			{
				href: string;
			},
		];
	};
}[];

export function useGetProductCategories() {
	return useQuery({
		queryKey: ['product-categories'],
		queryFn: async () =>
			apiFetch<GetProductCategoriesSuccessResponse>({
				path: '/wc/v3/products/categories',
			}),
	});
}
