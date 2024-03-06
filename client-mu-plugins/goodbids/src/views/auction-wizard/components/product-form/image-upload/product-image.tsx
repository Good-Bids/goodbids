import { Tooltip } from '~/components/tooltip';
import { Image } from './image';
import { __ } from '@wordpress/i18n';
import { useAuctionWizardState } from '~/views/auction-wizard/store';

export function ProductImage() {
	const {
		setProductImage,
		clearProductImage,
		product: { productImage },
	} = useAuctionWizardState();

	const mediaUploader = wp.media({
		title: 'Select Product Image',
		button: { text: 'Set Product Image' },
		library: { type: 'image' },
	});

	mediaUploader.on('select', () => {
		const attachment = mediaUploader
			.state()
			.get('selection')
			.first()
			.toJSON();
		setProductImage({ id: attachment.id, src: attachment.url });
	});

	return (
		<div className="flex flex-col gap-3">
			<div className="flex items-center gap-2">
				<h3 className="m-0 text-admin-label font-bold">
					{__('Product Image', 'goodbids')}
				</h3>
				<Tooltip>
					{__(
						'Select a single image as the focal image of your product.',
						'goodbids',
					)}
				</Tooltip>
			</div>
			<div className="mx-4 w-fit rounded-md bg-gray-200 p-2">
				{productImage ? (
					<Image
						src={productImage.value.src}
						onRemove={clearProductImage}
					/>
				) : (
					<button
						type="button"
						onClick={() => mediaUploader.open()}
						className="h-32 w-44 rounded-md border border-dashed border-gray-600 bg-none text-admin-large text-admin-main transition-colors duration-150 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-admin-main focus:ring-opacity-50"
					>
						{__('Click to upload', 'goodbids')}
					</button>
				)}
			</div>
		</div>
	);
}
