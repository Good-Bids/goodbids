import { Image } from './image';
import { Tooltip } from '~/components/tooltip';
import { useAuctionWizardState } from '~/views/auction-wizard/store';
import { __ } from '@wordpress/i18n';

export function ProductGallery() {
	const {
		addToProductGallery,
		removeFromProductGallery,
		product: { productGallery },
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
			<div className="flex items-center gap-2">
				<h3 className="m-0 text-admin-label font-bold">
					{__('Product Gallery', 'goodbids')}
				</h3>
				<Tooltip>
					{__(
						'Select additional images for your product gallery.',
						'goodbids',
					)}
				</Tooltip>
			</div>
			<div className="mx-4 grid grid-cols-1 gap-2 rounded-md bg-gray-200 p-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
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
					type="button"
					onClick={() => mediaUploader.open()}
					className="h-32 w-44 rounded-md border border-dashed border-gray-600 bg-none text-admin-large text-admin-main transition-colors duration-150 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-admin-main focus:ring-opacity-50"
				>
					{__('Click to upload multiple', 'goodbids')}
				</button>
			</div>
		</div>
	);
}
