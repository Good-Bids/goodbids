import { CloseIcon } from '~/components/icons/close-icon';

type ImageProps = {
	src: string;
	onRemove: () => void;
};

export function Image({ src, onRemove }: ImageProps) {
	return (
		<div className="relative flex w-fit items-center">
			<img src={src} className="max-h-32 max-w-44" />

			<button
				onClick={onRemove}
				className="absolute right-1 top-1 flex items-center justify-center rounded-full border-none bg-gb-gray-300 !p-1 outline-none transition-colors hover:bg-gb-gray-100 focus:bg-gb-green-100"
			>
				<CloseIcon width={16} />
			</button>
		</div>
	);
}
