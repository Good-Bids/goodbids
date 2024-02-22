import { Image } from './image';
import { Tooltip } from '../../../../components/tooltip';
import { useWizardState } from '../../store';

export function ProductImage() {
	const {
		setProductImage,
		clearProductImage,
		product: { productImage },
	} = useWizardState();

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
					{gbAuctionWizard.strings.productImageLabel}
				</h3>
				<Tooltip>{gbAuctionWizard.strings.productImageTooltip}</Tooltip>
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
						{gbAuctionWizard.strings.imageUploadSingle}
					</button>
				)}
			</div>
		</div>
	);
}
