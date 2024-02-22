import { Image } from './image';
import { Tooltip } from '../../../../components/tooltip';
import { useAuctionWizardState } from '../../store';

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
					{gbAuctionWizard.strings.productGalleryLabel}
				</h3>
				<Tooltip>
					{gbAuctionWizard.strings.productGalleryTooltip}
				</Tooltip>
			</div>
			<div className="rounded-md bg-gray-200 w-fit p-2 mx-4 grid grid-cols-4 gap-2">
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
					className="min-w-88 min-h-32 col-span-2 border border-dashed border-gray-600 text-admin-large bg-none rounded-md hover:bg-gray-300 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 text-admin-main"
				>
					{gbAuctionWizard.strings.imageUploadMultiple}
				</button>
			</div>
		</div>
	);
}
