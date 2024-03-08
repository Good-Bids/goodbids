import { IconProps } from './types';

export function InfoIcon({ width = 24 }: IconProps) {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={width}
			viewBox="0 -960 960 960"
			aria-hidden
		>
			<path
				fill="currentColor"
				d="M480-680q-33 0-56.5-23.5T400-760q0-33 23.5-56.5T480-840q33 0 56.5 23.5T560-760q0 33-23.5 56.5T480-680Zm-60 560v-480h120v480H420Z"
			/>
		</svg>
	);
}
