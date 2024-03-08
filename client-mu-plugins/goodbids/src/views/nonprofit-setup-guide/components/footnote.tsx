type FootnoteProps = { children: React.ReactNode };

export function Footnote({ children }: FootnoteProps) {
	return <p className="m-0 text-gb-sm">{children}</p>;
}
