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
		<div className="flex flex-col items-start gap-4 min-w-60 max-w-2xl flex-1">
			<H1>{__('Site Status', 'goodbids')}</H1>

			<section className="border-2 border-admin-main border-solid flex flex-col p-4 gap-3 items-center">
				{status === 'pending' && (
					<Pending manuallySetToLive={manuallySetToLive} />
				)}

				{status === 'live' && <Live />}

				{status === 'inactive' && <Inactive />}
			</section>
		</div>
	);
}
