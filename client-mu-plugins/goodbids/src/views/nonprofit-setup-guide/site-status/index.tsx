import { __ } from '@wordpress/i18n';
import { H1 } from '../../../components/typography';
import { Pending } from './pending';
import { useState } from 'react';
import { Live } from './live';
import { Inactive } from './inactive';
import { z } from 'zod';

const siteStatusSchema = z
	.enum(gbNonprofitSetupGuide.siteStatusOptions)
	.catch('pending');

export function SiteStatus() {
	const [status, setStatus] = useState(
		siteStatusSchema.parse(gbNonprofitSetupGuide.siteStatus),
	);

	const manuallySetToLive = () => {
		return setStatus('live');
	};

	return (
		<div className="flex min-w-60 max-w-2xl flex-1 flex-col items-start gap-4">
			<H1>{__('Site Status', 'goodbids')}</H1>

			<section className="flex flex-col items-center gap-3 border-2 border-solid border-admin-main p-4">
				{status === 'pending' && (
					<Pending manuallySetToLive={manuallySetToLive} />
				)}

				{status === 'live' && <Live />}

				{status === 'inactive' && <Inactive />}
			</section>
		</div>
	);
}
