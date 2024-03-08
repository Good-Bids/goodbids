import { ProgressBar, ProgressBarProps } from '../components/progress-bar';
import { H2 } from '~/components/typography';
import { Navigation } from './navigation';
import { __ } from '@wordpress/i18n';

type WrapperProps = ProgressBarProps & {
	children: React.ReactNode;
	step: 1 | 2 | 3;
	title: string;
};

export function Wrapper({ children, progress, step, title }: WrapperProps) {
	return (
		<>
			<ProgressBar progress={progress} />
			<div className="flex flex-col gap-8 px-10 pb-10 text-gb-green-900">
				<Navigation />

				<div>
					<span className="text-gb-lg text-gb-green-700">
						{__('Step', 'goodbids')} {step}
						{__('/3', 'goodbids')}
					</span>
					<H2 as="h1">{title}</H2>
				</div>

				<div>{children}</div>
			</div>
		</>
	);
}
