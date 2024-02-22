type CloseIconProps = {
	width?: number;
};

export function CloseIcon({ width = 24 }: CloseIconProps) {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={width}
			viewBox="0 -24 24 24"
			aria-hidden
		>
			<path
				fill="currentColor"
				d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
			/>
		</svg>
	);
}
