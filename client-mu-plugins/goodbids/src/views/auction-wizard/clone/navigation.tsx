import { __ } from '@wordpress/i18n';
import { ArrowLeftIcon } from '~/components/icons/arrow-left-icon';
import { CloseIcon } from '~/components/icons/close-icon';
import { useAuctionWizardState } from '../store';
import { NavButton } from '../components/nav-button';
import clsx from 'clsx';

export function Navigation() {
	const { step, setStep, clearStore } = useAuctionWizardState();

	const handleBack = () => {
		setStep('review');
	};

	const handleCancel = () => {
		clearStore();
		window.location.replace(gbAuctionWizard.auctionsIndexURL);
	};

	const containerClasses = clsx(
		'flex items-center border-x-0 border-b border-t-0 border-solid border-gb-green-700 pb-6 pt-6',
		{
			'justify-end': step === 'start' || step === 'review',
			'justify-between': step !== 'start' && step !== 'review',
		},
	);

	return (
		<div className={containerClasses}>
			{step !== 'start' && step !== 'review' && (
				<NavButton
					label={__('Back', 'goodbids')}
					startIcon={<ArrowLeftIcon />}
					onClick={handleBack}
				/>
			)}
			<NavButton
				label={__('Cancel', 'goodbids')}
				endIcon={<CloseIcon />}
				onClick={handleCancel}
			/>
		</div>
	);
}
