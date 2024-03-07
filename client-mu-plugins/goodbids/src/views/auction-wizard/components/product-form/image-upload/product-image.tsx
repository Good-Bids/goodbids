import { Image } from './image';
import { __ } from '@wordpress/i18n';
import { P } from '~/components/typography';
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
			<h2 className="m-0 text-gb-md font-bold text-gb-green-700">
				{__('Featured Image', 'goodbids')}
			</h2>
			{productImage ? (
				<Image
					src={productImage.value.src}
					onRemove={clearProductImage}
				/>
			) : (
				<button
					className="h-25 w-full rounded-gb border border-dashed border-gb-gray-300 bg-gb-gray-100 p-3 text-gb-lg outline-none transition-colors hover:bg-gb-gray-200 focus:ring-2 focus:ring-gb-green-700 focus:ring-offset-2"
					type="button"
					onClick={() => mediaUploader.open()}
				>
					{__('Click to upload', 'goodbids')}
				</button>
			)}
			<P>
				{__(
					'Select a single image as the main image for this auction.',
					'goodbids',
				)}
			</P>
		</div>
	);
}
