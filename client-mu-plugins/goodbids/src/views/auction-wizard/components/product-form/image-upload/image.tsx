import { CloseIcon } from '~/components/close-icon';

type ImageProps = {
	src: string;
	onRemove: () => void;
};

export function Image({ src, onRemove }: ImageProps) {
	return (
		<div className="relative flex items-center">
			<img src={src} className="max-h-32 max-w-44" />

			<button
				onClick={onRemove}
				className="absolute right-1 top-1 flex items-center justify-center rounded-full border-none bg-gray-300 !p-1 hover:bg-gray-500 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50"
			>
				<CloseIcon width={16} />
			</button>
		</div>
	);
}
