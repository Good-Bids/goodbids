import { motion } from 'framer-motion';
import { WarningIcon } from './icons/warning-icon';

type ErrorWrapperProps = {
	children: React.ReactNode;
};

export function ErrorWrapper({ children }: ErrorWrapperProps) {
	return (
		<motion.div
			initial={{ opacity: 0 }}
			animate={{ opacity: 1 }}
			exit={{ opacity: 0 }}
			transition={{ duration: 0.2 }}
			className="flex items-start gap-4 bg-warning-bg text-warning-text rounded p-4"
		>
			<div className="h-6 w-6">
				<WarningIcon />
			</div>
			{children}
		</motion.div>
	);
}
