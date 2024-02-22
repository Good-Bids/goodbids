import { useState } from 'react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { TooltipProvider } from '../../components/tooltip';

// TODO: Uncomment when i18n is ready
// import { createI18n } from '@wordpress/i18n';
// import { I18nProvider } from '@wordpress/react-i18n';

// const i18n = createI18n();

type ProviderProps = {
	children: React.ReactNode;
};

export function Providers({ children }: ProviderProps) {
	const [queryClient] = useState(() => new QueryClient());

	// TODO: Uncomment when i18n is ready
	// return (
	// 	<I18nProvider i18n={i18n}>
	// 	<QueryClientProvider client={queryClient}>
	// 		<TooltipProvider>{children}</TooltipProvider>
	// 	</QueryClientProvider>
	// 	</I18nProvider>
	// );

	// TODO: Remove when i18n is ready
	return (
		<QueryClientProvider client={queryClient}>
			<TooltipProvider>{children}</TooltipProvider>
		</QueryClientProvider>
	);
}
