import { animate, motion, useMotionValue, useTransform } from 'framer-motion';
import {
	MetricType,
	formatMetricHeading,
	formatMetricValue,
} from './format-metrics';
import { useEffect } from 'react';

type MetricBlockProps = {
	type: MetricType;
	value: number;
};

export function MetricBlock({ type, value }: MetricBlockProps) {
	const motionValue = useMotionValue(value);
	const calculatedValue = useTransform(motionValue, (value) =>
		formatMetricValue(type, value),
	);

	useEffect(() => {
		const animation = animate(motionValue, value, {
			duration: 0.5,
			ease: 'easeInOut',
		});

		return animation.stop;
	}, [value, motionValue]);

	return (
		<div className="rounded bg-contrast-5 px-4 py-2">
			<span className="font-bold text-sm block">
				{formatMetricHeading(type)}
			</span>
			<motion.span className="text-sm block">
				{calculatedValue}
			</motion.span>
		</div>
	);
}
