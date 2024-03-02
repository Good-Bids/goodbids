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
			<div className="flex gap-2 items-center">
				<h3 className="text-admin-label font-bold m-0">
					{__('Product Image', 'goodbids')}
				</h3>
				<Tooltip>
					{__(
						'Select a single image as the focal image of your product.',
						'goodbids',
					)}
				</Tooltip>
			</div>
			<div className="rounded-md bg-gray-200 w-fit p-2 mx-4">
				{productImage ? (
					<Image
						src={productImage.value.src}
						onRemove={clearProductImage}
					/>
				) : (
					<button
						type="button"
						onClick={() => mediaUploader.open()}
						className="w-44 h-32 border border-dashed border-gray-600 text-admin-large bg-none rounded-md hover:bg-gray-300 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 text-admin-main"
					>
						{__('Click to upload', 'goodbids')}
					</button>
				)}
			</div>
		</div>
	);
}
