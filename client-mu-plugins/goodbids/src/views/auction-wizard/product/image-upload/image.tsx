import { CloseIcon } from '../../components/close-icon';

type ImageProps = {
	src: string;
	onRemove: () => void;
};

export function Image({ src, onRemove }: ImageProps) {
	return (
		<div className="relative flex items-center">
			<img src={src} className="max-w-44 max-h-32" />

			<button
				onClick={onRemove}
				className="border-none hover:bg-gray-500 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 absolute top-1 right-1 rounded-full bg-gray-300 flex items-center justify-center !p-1"
			>
				<CloseIcon width={16} />
			</button>
		</div>
	);
}
