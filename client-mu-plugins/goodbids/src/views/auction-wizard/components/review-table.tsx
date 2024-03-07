type ReviewTableProps = {
	children: React.ReactNode;
};

export function ReviewTable({ children }: ReviewTableProps) {
	return (
		<table className="w-full pb-2 pt-4">
			<tbody>{children}</tbody>
		</table>
	);
}

type ReviewCellProps = {
	children: React.ReactNode;
};

export function ReviewTH({ children }: ReviewCellProps) {
	return (
		<th className="w-full max-w-1/3 py-2 pr-2 text-left text-gb-md text-gb-gray-500">
			{children}
		</th>
	);
}

export function ReviewTD({ children }: ReviewCellProps) {
	return <td className="text-gb-md">{children}</td>;
}
