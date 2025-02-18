import { UseMutationOptions, useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

type CreateProductResponse = {
	id: number;
};

export function useCreateProduct(
	options?: UseMutationOptions<
		CreateProductResponse,
		unknown,
		CreateProductBody,
		unknown
	>,
) {
	return useMutation({
		...options,
		mutationFn: async (body: CreateProductBody) =>
			apiFetch<CreateProductResponse>({
				path: '/wc/v3/products',
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify(createProductBody(body)),
			}),
	});
}

export type ProductType = 'physical' | 'non-physical';

type BaseProductBody = {
	name: string;
	regular_price: string;
	images: { id: number }[];
	categories: { id: number }[];
};

type PhysicalProductBody = BaseProductBody & {
	type: 'physical';
	weight: string;
	dimensions: {
		length: string;
		width: string;
		height: string;
	};
	shipping_class: string;
};

type NonPhysicalProductBody = BaseProductBody & {
	type: 'non-physical';
	purchase_note: string;
};

type CreateProductBody = PhysicalProductBody | NonPhysicalProductBody;

function createProductBody(body: CreateProductBody) {
	const base = {
		name: body.name,
		regular_price: body.regular_price,
		images: body.images,
		type: 'simple',
		catalog_visibility: 'hidden',
		categories: body.categories,
		manage_stock: true,
		stock_quantity: 1,
		sold_individually: true,
		reviews_allowed: false,
	};

	if (body.type === 'physical') {
		return {
			...base,
			tax_status: 'taxable',
			tax_rate: 'standard',
			weight: body.weight,
			dimensions: body.dimensions,
			shipping_class: body.shipping_class,
		};
	}

	return {
		...base,
		virtual: true,
		tax_status: 'taxable',
		tax_rate: 'standard',
		purchase_note: body.purchase_note,
	};
}
