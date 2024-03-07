import { __ } from '@wordpress/i18n';
import { ArrowLeftIcon } from '~/components/icons/arrow-left-icon';
import { CloseIcon } from '~/components/icons/close-icon';
import { useAuctionWizardState } from '../store';

export function Navigation() {
	const { step, setStep, clearStore } = useAuctionWizardState();

	const handleBack = () => {
		if (step === 'product') {
			return setStep('start');
		}

		if (step === 'auction') {
			return setStep('product');
		}

		if (step === 'review') {
			return setStep('auction');
		}
	};

	const handleCancel = () => {
		clearStore();
	};

	return (
		<div className="flex items-center justify-between border-x-0 border-b border-t-0 border-solid border-gb-green-700 pb-6 pt-6">
			<NavButton
				label={__('Back', 'goodbids')}
				startIcon={<ArrowLeftIcon />}
				onClick={handleBack}
			/>
			<NavButton
				label={__('Cancel', 'goodbids')}
				endIcon={<CloseIcon />}
				onClick={handleCancel}
			/>
		</div>
	);
}

type NavButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement> & {
	endIcon?: React.ReactElement;
	label: string;
	startIcon?: React.ReactElement;
};

function NavButton(props: NavButtonProps) {
	const { endIcon, label, startIcon, ...rest } = props;

	return (
		<button
			{...rest}
			className="group flex items-center gap-2 border-none bg-transparent text-gb-lg text-gb-green-700 outline-none"
		>
			{startIcon}
			<span className="group-hover:underline group-focus:underline">
				{label}
			</span>
			{endIcon}
		</button>
	);
}
