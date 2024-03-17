import clsx from 'clsx';

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
	error?: boolean;
	children: React.ReactNode;
};

export function ReviewTH({ error, children }: ReviewCellProps) {
	const classes = clsx('w-full max-w-1/3 py-2 pr-2 text-left text-gb-md', {
		'text-gb-red-500': error,
		'text-gb-gray-500': !error,
	});

	return <th className={classes}>{children}</th>;
}

export function ReviewTD({ error, children }: ReviewCellProps) {
	const classes = clsx('text-gb-md', {
		'text-gb-red-500': error,
	});

	return <td className={classes}>{children}</td>;
}
