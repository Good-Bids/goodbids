import { useRef, useState } from 'react';
import { ImageType, UploadedImageType } from './types';
import { useUploadImage } from './upload-image';
import { Image } from './image';
import { useDeleteImage } from './delete-image';
import { Tooltip } from '../../components/tooltip';

type ProductImageProps = {
	uploadedProductImage: UploadedImageType | undefined;
	setUploadedProductImage: (image: UploadedImageType | undefined) => void;
};

export function ProductImage({
	uploadedProductImage,
	setUploadedProductImage,
}: ProductImageProps) {
	const [image, setImage] = useState<ImageType>();
	const productImageUploaderRef = useRef<HTMLInputElement>(null);

	const uploadImage = useUploadImage({
		onSuccess: (data) => {
			setUploadedProductImage(data);
		},
	});

	const deleteImage = useDeleteImage();

	const handleProductImageUploadClick = () => {
		productImageUploaderRef.current?.click();
	};

	const handleProductImageUpload = (
		event: React.ChangeEvent<HTMLInputElement>,
	) => {
		const file = event.target.files?.[0];

		if (file) {
			const reader = new FileReader();

			reader.onload = (e) => {
				setImage({
					url: e.target?.result as string,
					fileName: file.name,
				});

				uploadImage.mutate(file);
			};

			reader.readAsDataURL(file);
		}
	};

	const handleDeleteImage = () => {
		if (uploadedProductImage) {
			deleteImage.mutate(uploadedProductImage.id);
			setImage(undefined);
			setUploadedProductImage(undefined);
		}
	};

	return (
		<div className="flex flex-col gap-3">
			<div className="flex gap-2 items-center">
				<h3 className="text-admin-label font-bold m-0">
					{gbAuctionWizard.strings.productImageLabel}
				</h3>
				<Tooltip>{gbAuctionWizard.strings.productImageTooltip}</Tooltip>
			</div>
			<div className="rounded-md bg-gray-200 w-fit p-2 mx-4">
				{image ? (
					<Image src={image.url} onRemove={handleDeleteImage} />
				) : (
					<>
						<button
							type="button"
							onClick={handleProductImageUploadClick}
							className="w-44 h-32 border border-dashed border-gray-600 text-admin-large bg-none rounded-md hover:bg-gray-300 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 text-admin-main"
						>
							{gbAuctionWizard.strings.imageUploadSingle}
						</button>
						<input
							className="hidden"
							type="file"
							ref={productImageUploaderRef}
							onChange={handleProductImageUpload}
						/>
					</>
				)}
			</div>
		</div>
	);
}
