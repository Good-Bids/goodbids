import { UseMutationOptions, useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

type PublishSiteBody = {
	site_id: number;
};

export function usePublishSite(
	options?: UseMutationOptions<unknown, unknown, PublishSiteBody, unknown>,
) {
	return useMutation({
		...options,
		mutationFn: async (body: PublishSiteBody) =>
			apiFetch({
				path: '/wp/v2/sites/publish',
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},

				body: JSON.stringify(body),
			}),
	});
}
