import { IconProps } from './types';

export function ArrowLeftIcon({ width = 24 }: IconProps) {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={width}
			viewBox="0 0 24 25"
			aria-hidden
		>
			<path
				fill="currentColor"
				d="M10.733 20.29a.75.75 0 1 0 1.034-1.085L5.516 13.25H20.25a.75.75 0 0 0 0-1.5H5.516l6.251-5.955a.75.75 0 0 0-1.034-1.086l-7.42 7.067a.995.995 0 0 0-.3.58.753.753 0 0 0 .001.289.995.995 0 0 0 .3.579l7.419 7.067Z"
			/>
		</svg>
	);
}
