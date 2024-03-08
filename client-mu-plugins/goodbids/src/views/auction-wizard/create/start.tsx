import { Button } from '~/components/button';
import { useAuctionWizardState } from '../store';
import { __ } from '@wordpress/i18n';
import { PuzzleManImage } from '~/components/images/puzzle-man';
import { H1, H3, P } from '~/components/typography';

export function StartStep() {
	const {
		setStep,
		clearStore,
		product: { name },
		auction: { title },
	} = useAuctionWizardState();

	const setProductStep = () => {
		setStep('product');
	};

	const clearAndSetProductStep = () => {
		clearStore();
		setStep('product');
	};

	return (
		<div className="flex flex-col items-center gap-8 p-10">
			<PuzzleManImage className="aspect-auto h-50 py-10" />

			<div className="flex flex-col gap-3">
				<H1>{__('Let’s build an auction!', 'goodbids')}</H1>
				<P>
					{__(
						"Before you begin, we advise having your auction's start and end times, bidding parameters, and fundraising goals, as well as your prize details, including descriptions, and images ready. You’ll have the opportunity to preview your page before publishing at the end.",
						'goodbids',
					)}
				</P>
			</div>

			{name.value.length > 0 ? (
				<div className="flex w-full flex-col gap-3">
					<H3 as="h2">
						{__('Pick up where you left off?', 'goodbids')}
					</H3>

					<Button variant="solid" autoFocus onClick={setProductStep}>
						{__('Continue creating', 'goodbids')}{' '}
						{title.value || name.value}
					</Button>

					<Button variant="outline" onClick={clearAndSetProductStep}>
						{__('Start a new auction', 'goodbids')}
					</Button>
				</div>
			) : (
				<Button
					variant="solid"
					onClick={clearAndSetProductStep}
					autoFocus
				>
					{__('Get started', 'goodbids')}
				</Button>
			)}
		</div>
	);
}
