import { Image } from './image';
import { useAuctionWizardState } from '~/views/auction-wizard/store';
import { __ } from '@wordpress/i18n';
import { P } from '~/components/typography';

export function ProductGallery() {
	const {
		addToProductGallery,
		removeFromProductGallery,
		product: { productImage, productGallery },
	} = useAuctionWizardState();

	const mediaUploader = wp.media({
		title: 'Select Product Gallery',
		button: { text: 'Set Product Gallery' },
		library: { type: 'image' },
		multiple: true,
	});

	mediaUploader.on('select', () => {
		mediaUploader
			.state()
			.get('selection')
			// TODO: Type better
			// eslint-disable-next-line @typescript-eslint/no-explicit-any
			.map((attachment: any) => {
				const attachmentData = attachment.toJSON();
				addToProductGallery({
					id: attachmentData.id,
					src: attachmentData.url,
				});
			});
	});

	return (
		<div className="flex flex-col gap-3">
			<h2 className="m-0 text-gb-md font-bold text-gb-green-700">
				{__('Gallery Images', 'goodbids')}
			</h2>

			{productGallery.length === 0 ? (
				<button
					className="h-25 w-full rounded-gb border border-dashed border-gb-gray-300 bg-gb-gray-100 p-3 text-gb-lg outline-none transition-colors hover:bg-gb-gray-200 focus:ring-2 focus:ring-gb-green-700 focus:ring-offset-2"
					type="button"
					onClick={() => mediaUploader.open()}
				>
					{__('Click to upload', 'goodbids')}
				</button>
			) : (
				<>
					<div className="flex flex-wrap gap-3">
						{productGallery.map((image) => (
							<Image
								key={image.value.id}
								src={image.value.src}
								onRemove={() =>
									removeFromProductGallery(image.value.id)
								}
							/>
						))}
						<button
							className="rounded-gb border border-dashed border-gb-gray-300 bg-gb-gray-100 p-3 text-gb-lg outline-none transition-colors hover:bg-gb-gray-200 focus:ring-2 focus:ring-gb-green-700 focus:ring-offset-2"
							type="button"
							onClick={() => mediaUploader.open()}
						>
							{__('Upload more', 'goodbids')}
						</button>
					</div>

					{(!productImage || productImage.value.src.length === 0) && (
						<div className="text-gb-red-500">
							<P>
								{__(
									'If you do not select a featured image, the first image in the gallery will be used as the main product image.',
									'goodbids',
								)}
							</P>
						</div>
					)}
				</>
			)}

			<P>
				{__(
					'Add additional images to the auction gallery. You can upload multiple images for your product. The "Featured Image" image will be used as the main product image.',
					'goodbids',
				)}
			</P>
		</div>
	);
}
