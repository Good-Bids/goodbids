import { Image } from './image';
import { Tooltip } from '../../../../components/tooltip';
import { useAuctionWizardState } from '../../store';
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
			<div className="flex gap-2 items-center">
				<h3 className="text-admin-label font-bold m-0">
					{__('Product Gallery', 'goodbids')}
				</h3>
				<Tooltip>
					{__(
						'Select additional images for your product gallery.',
						'goodbids',
					)}
				</Tooltip>
			</div>
			<div className="rounded-md bg-gray-200 w-full p-2 mx-4 grid grid-cols-4 gap-2">
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
					className="w-44 h-32 border border-dashed border-gray-600 text-admin-large bg-none rounded-md hover:bg-gray-300 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 text-admin-main"
				>
					{__('Click to upload multiple', 'goodbids')}
				</button>
			</div>
		</div>
	);
}
