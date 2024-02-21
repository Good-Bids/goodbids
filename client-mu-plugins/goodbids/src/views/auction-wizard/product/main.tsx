import { useState } from 'react';
import { Wrapper } from '../components/wrapper';
import { Form, REWARD_FORM_ID } from './form';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { TooltipProvider } from '../components/tooltip';
import { Tips } from '../components/tips';

export function Main() {
	const [queryClient] = useState(() => new QueryClient());

	return (
		<QueryClientProvider client={queryClient}>
			<TooltipProvider>
				<Wrapper progress={50}>
					<div className="flex gap-4 justify-between">
						<Form />

						<Tips>
							<p>{gbAuctionWizard.strings.productTips}</p>
						</Tips>
					</div>

					<div className="w-full flex justify-center">
						<button
							type="submit"
							form={REWARD_FORM_ID}
							className="py-2 px-6 cursor-pointer border-none rounded-admin-sm bg-admin-main text-white text-admin-content hover:bg-admin-accent hover:text-black transition-colors focus:outline-opacity-50 focus:ring-2 focus:ring-admin-main focus:ring-opacity-50 w-full max-w-80"
						>
							{gbAuctionWizard.strings.nextButtonText}
						</button>
					</div>
				</Wrapper>
			</TooltipProvider>
		</QueryClientProvider>
	);
}
