import clsx from 'clsx';
import { motion, AnimatePresence } from 'framer-motion';

type SkeletonProps = {
	size?: 'sm' | 'lg';
	visible: boolean;
};

export function Skeleton({ size = 'sm', visible }: SkeletonProps) {
	const classes = clsx(
		'w-full animate-pulse rounded-sm bg-contrast-2 duration-500',
		{
			'h-8': size === 'sm',
			'h-24': size === 'lg',
		},
	);

	return (
		<AnimatePresence>
			{visible && (
				<motion.div
					initial={{ opacity: 1, position: 'relative' }}
					exit={{ opacity: 0, position: 'absolute' }}
					transition={{ duration: 0.2 }}
					className={classes}
				/>
			)}
		</AnimatePresence>
	);
}
