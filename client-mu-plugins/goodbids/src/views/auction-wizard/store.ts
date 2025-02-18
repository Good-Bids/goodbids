import { create } from 'zustand';
import { createJSONStorage, persist } from 'zustand/middleware';
import { createTrackedSelector } from 'react-tracked';
import { mountStoreDevtool } from 'simple-zustand-devtools';

export type StepType = 'start' | 'product' | 'auction' | 'review' | 'finish';

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
	error: string | null;
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
	error: null,
};

export type AuctionState = {
	title: ValueType<string>;
	excerpt: ValueType<string>;
	startDate: ValueType<string>;
	endDate: ValueType<string>;
	bidIncrement: ValueType<string>;
	startingBid: ValueType<string>;
	bidExtensionMinutes: ValueType<string>;
	auctionGoal: ValueType<string>;
	expectedHighBid: ValueType<string>;
	estimatedRetailValue: ValueType<string>;
	clonedContent: string | null;
	error: string | null;
};

const defaultAuctionState: AuctionState = {
	title: { value: '' },
	excerpt: { value: '' },
	startDate: { value: '' },
	endDate: { value: '' },
	bidIncrement: { value: '10' },
	startingBid: { value: '10' },
	bidExtensionMinutes: { value: '60' },
	auctionGoal: { value: '' },
	expectedHighBid: { value: '' },
	estimatedRetailValue: { value: '' },
	clonedContent: null,
	error: null,
};

export type AuctionWizardStoreState = {
	step: StepType;
	auctionId: number | null;
	product: AuctionWizardProductState;
	auction: AuctionState;
};

export type EditableAuctionValues = keyof Omit<
	AuctionState,
	'error' | 'clonedContent'
>;
export type EditableProductValues = keyof Omit<
	AuctionWizardProductState,
	'error'
>;

type AuctionWizardStoreActions = {
	setStep: (step: StepType) => void;
	setAuctionId: (id: number) => void;
	setProductImage: (image: ImageType) => void;
	clearProductImage: () => void;
	addToProductGallery: (image: ImageType) => void;
	removeFromProductGallery: (id: number) => void;
	setProductValue: (
		key: EditableProductValues,
		value: string,
		error?: string,
	) => void;
	setProductError: (error: string) => void;
	setAuctionValue: (
		key: EditableAuctionValues,
		value: string,
		error?: string,
	) => void;
	setAuctionClonedContent: (content: string) => void;
	setAuctionError: (error: string) => void;
	setAuction: (auction: Omit<AuctionState, 'error'>) => void;
	setProduct: (product: Omit<AuctionWizardProductState, 'error'>) => void;
	clearStore: () => void;
};

const useAuctionWizardStore = create<
	AuctionWizardStoreState & AuctionWizardStoreActions
>()(
	persist(
		(set) => ({
			step: 'start',
			auctionId: null,
			product: defaultProductState,
			auction: defaultAuctionState,
			setStep: (step) => {
				window.scrollTo({ top: 0, behavior: 'instant' });
				return set({ step });
			},
			setAuctionId: (id) => set({ auctionId: id }),
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
			setProductError: (error) =>
				set((state) => ({ product: { ...state.product, error } })),
			setAuctionValue: (key, value, error) =>
				set((state) => ({
					auction: {
						...state.auction,
						[key]: { value, error },
					},
				})),
			setAuctionClonedContent: (content) =>
				set((state) => ({
					auction: { ...state.auction, clonedContent: content },
				})),
			setAuctionError: (error) =>
				set((state) => ({ auction: { ...state.auction, error } })),
			setAuction: (auction) =>
				set((state) => ({ auction: { ...state.auction, ...auction } })),
			setProduct: (product) =>
				set((state) => ({ product: { ...state.product, ...product } })),
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
