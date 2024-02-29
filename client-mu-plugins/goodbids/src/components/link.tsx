type LinkProps = Omit<
	React.AnchorHTMLAttributes<HTMLAnchorElement>,
	'className'
>;

export function Link(props: LinkProps) {
	return <a {...props} className="text-admin-content" />;
}
