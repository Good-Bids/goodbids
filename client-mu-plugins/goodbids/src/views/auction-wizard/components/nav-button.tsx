type NavButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement> & {
	endIcon?: React.ReactElement;
	label: string;
	startIcon?: React.ReactElement;
};

export function NavButton(props: NavButtonProps) {
	const { endIcon, label, startIcon, ...rest } = props;

	return (
		<button
			{...rest}
			className="group flex cursor-pointer items-center gap-2 border-none bg-transparent text-gb-lg text-gb-green-700 outline-none"
		>
			{startIcon}
			<span className="group-hover:underline group-focus:underline">
				{label}
			</span>
			{endIcon}
		</button>
	);
}
