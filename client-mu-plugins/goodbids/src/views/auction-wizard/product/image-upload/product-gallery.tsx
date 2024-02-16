import { useRef, useState } from 'react';
import { ImageType, UploadedImageType } from './types';
import { useUploadImage } from './upload-image';
import { Image } from './image';
import { useDeleteImage } from './delete-image';
import { Tooltip } from '../../components/tooltip';

type ProductGalleryProps = {
	uploadedProductGalleryImages: UploadedImageType[];
	setUploadedProductGalleryImages: (images: UploadedImageType[]) => void;
};

export function ProductGallery({
	uploadedProductGalleryImages,
	setUploadedProductGalleryImages,
}: ProductGalleryProps) {
	const [images, setImages] = useState<ImageType[]>([]);
	const productGalleryUploaderRef = useRef<HTMLInputElement>(null);

	const uploadImage = useUploadImage({
		onSuccess: (data) => {
			setUploadedProductGalleryImages([
				data,
				...uploadedProductGalleryImages,
			]);
		},
	});

	const deleteImage = useDeleteImage();

	const handleProductGalleryUploadClick = () => {
		productGalleryUploaderRef.current?.click();
	};

	const handleProductGalleryUpload = (
		event: React.ChangeEvent<HTMLInputElement>,
	) => {
		const files = event.target.files;
		const filesForState: ImageType[] = images;

		if (files) {
			for (const file of files) {
				const reader = new FileReader();

				reader.onload = (e) => {
					filesForState.push({
						url: e.target?.result as string,
						fileName: file.name,
					});

					uploadImage.mutate(file);
				};

				reader.readAsDataURL(file);
			}
		}

		setImages(filesForState);
	};

	const handleDeleteImage = (fileName: string) => {
		const id = uploadedProductGalleryImages.find(
			(image) => image.fileName === fileName,
		)?.id;

		if (id) {
			deleteImage.mutate(id);
		}

		setUploadedProductGalleryImages(
			uploadedProductGalleryImages.filter((image) => image.id !== id),
		);
		setImages(images.filter((image) => image.fileName !== fileName));
	};

	return (
		<div className="flex flex-col gap-3">
			<div className="flex gap-2 items-center">
				<h3 className="text-admin-label font-bold m-0">
					Product Gallery
				</h3>
				<Tooltip>
					Select additional images for your product gallery.
				</Tooltip>
			</div>
			<div className="rounded-md bg-gray-200 w-fit p-2 mx-4 grid grid-cols-4 gap-2">
				{images.map((image, index) => (
					<Image
						key={image.fileName + index}
						src={image.url}
						onRemove={() => handleDeleteImage(image.fileName)}
					/>
				))}
				<>
					<button
						type="button"
						onClick={handleProductGalleryUploadClick}
						className="min-w-88 min-h-32 col-span-2 border border-dashed border-gray-600 text-admin-large bg-none rounded-md hover:bg-gray-300 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 text-admin-main"
					>
						Click to upload multiple
					</button>
					<input
						className="hidden"
						type="file"
						multiple
						ref={productGalleryUploaderRef}
						onChange={handleProductGalleryUpload}
					/>
				</>
			</div>
		</div>
	);
}
