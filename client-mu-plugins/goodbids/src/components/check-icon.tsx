type CheckIconProps = {
	width?: number;
};

export function CheckIcon({ width = 24 }: CheckIconProps) {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={width}
			viewBox="0 -960 960 960"
			aria-hidden
		>
			<path
				fill="currentColor"
				d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"
			/>
		</svg>
	);
}
