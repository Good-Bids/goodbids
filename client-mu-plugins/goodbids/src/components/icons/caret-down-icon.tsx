import { IconProps } from './types';

export function CaretDownIcon({ width = 16 }: IconProps) {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={width}
			viewBox="0 0 16 9"
			aria-hidden
		>
			<path
				fill="currentColor"
				d="M.22.47a.75.75 0 0 1 1.06 0L8 7.19 14.72.47a.75.75 0 1 1 1.06 1.06L8.53 8.78a.75.75 0 0 1-1.06 0L.22 1.53a.75.75 0 0 1 0-1.06Z"
			/>
		</svg>
	);
}
