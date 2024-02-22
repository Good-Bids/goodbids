type ButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement>;

export function Button(props: ButtonProps) {
	return (
		<button
			{...props}
			className="py-2 px-6 border border-solid rounded-admin-sm border-admin-main text-admin-main text-admin-content no-underline"
		/>
	);
}
