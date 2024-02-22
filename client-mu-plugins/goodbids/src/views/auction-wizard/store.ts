import { create } from 'zustand';
import { createJSONStorage, persist } from 'zustand/middleware';
import { createTrackedSelector } from 'react-tracked';
import { mountStoreDevtool } from 'simple-zustand-devtools';

export type StepType = 'start' | 'product' | 'auction' | 'finish' | 'edit';

type ValueType<T> = {
	value: T;
	error?: string;
};

type ImageType = {
	id: number;
	src: string;
};

export type AuctionWizardProductState = {
	name: ValueType<string>;
	regularPrice: ValueType<string>;
	productImage: ValueType<ImageType> | null;
	productGallery: ValueType<ImageType>[];
	productType: ValueType<string>;
	weight: ValueType<string>;
	length: ValueType<string>;
	width: ValueType<string>;
	height: ValueType<string>;
	purchaseNote: ValueType<string>;
	shippingClass: ValueType<string>;
};

const defaultProductState: AuctionWizardProductState = {
	name: { value: '' },
	regularPrice: { value: '' },
	productImage: null,
	productGallery: [],
	productType: { value: 'physical' },
	weight: { value: '' },
	length: { value: '' },
	width: { value: '' },
	height: { value: '' },
	purchaseNote: { value: '' },
	shippingClass: { value: 'none' },
};

export type AuctionState = {
	startDate: ValueType<string>;
	endDate: ValueType<string>;
	bidIncrement: ValueType<string>;
	startingBid: ValueType<string>;
	bidExtensionMinutes: ValueType<string>;
	bidExtensionSeconds: ValueType<string>;
	auctionGoal: ValueType<string>;
	expectedHighBid: ValueType<string>;
	estimatedRetailValue: ValueType<string>;
};

const defaultAuctionState: AuctionState = {
	startDate: { value: '' },
	endDate: { value: '' },
	bidIncrement: { value: '' },
	startingBid: { value: '' },
	bidExtensionMinutes: { value: '' },
	bidExtensionSeconds: { value: '' },
	auctionGoal: { value: '' },
	expectedHighBid: { value: '' },
	estimatedRetailValue: { value: '' },
};

export type AuctionWizardStoreState = {
	step: StepType;
	product: AuctionWizardProductState;
	auction: AuctionState;
};

type AuctionWizardStoreActions = {
	setStep: (step: StepType) => void;
	setProductImage: (image: ImageType) => void;
	clearProductImage: () => void;
	addToProductGallery: (image: ImageType) => void;
	removeFromProductGallery: (id: number) => void;
	setProductValue: (
		key: keyof AuctionWizardProductState,
		value: string,
		error?: string,
	) => void;
	setAuctionValue: (
		key: keyof AuctionState,
		value: string,
		error?: string,
	) => void;
	clearStore: () => void;
};

const useAuctionWizardStore = create<
	AuctionWizardStoreState & AuctionWizardStoreActions
>()(
	persist(
		(set) => ({
			step: 'start',
			product: defaultProductState,
			auction: defaultAuctionState,
			setStep: (step) => set({ step }),
			setProductImage: (image) =>
				set((state) => ({
					product: {
						...state.product,
						productImage: { value: image, error: undefined },
					},
				})),
			clearProductImage: () =>
				set((state) => ({
					product: {
						...state.product,
						productImage: null,
					},
				})),
			addToProductGallery: (image) =>
				set((state) => ({
					product: {
						...state.product,
						productGallery: [
							...state.product.productGallery,
							{ value: image, error: undefined },
						],
					},
				})),
			removeFromProductGallery: (id) =>
				set((state) => ({
					product: {
						...state.product,
						productGallery: state.product.productGallery.filter(
							(image) => image.value.id !== id,
						),
					},
				})),
			setProductValue: (key, value, error) =>
				set((state) => ({
					product: {
						...state.product,
						[key]: { value, error },
					},
				})),
			setAuctionValue: (key, value, error) =>
				set((state) => ({
					auction: {
						...state.auction,
						[key]: { value, error },
					},
				})),
			clearStore: () =>
				set({
					product: defaultProductState,
					auction: defaultAuctionState,
				}),
		}),
		{
			name: 'auction-wizard',
			storage: createJSONStorage(() => sessionStorage),
			partialize: (state) => ({
				product: state.product,
				auction: state.auction,
				step:
					// Maintain step during dev for easier navigation
					process.env.NODE_ENV === 'development'
						? state.step
						: undefined,
			}),
		},
	),
);

export const useAuctionWizardState = createTrackedSelector(
	useAuctionWizardStore,
);

if (process.env.NODE_ENV === 'development') {
	mountStoreDevtool('Auction Wizard Store', useAuctionWizardStore);
}
