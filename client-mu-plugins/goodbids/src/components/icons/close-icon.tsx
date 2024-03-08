import { IconProps } from './types';

export function CloseIcon({ width = 24 }: IconProps) {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={width}
			viewBox="0 -960 960 960"
			aria-hidden
		>
			<path
				fill="currentColor"
				d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
			/>
		</svg>
	);
}
