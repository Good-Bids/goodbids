import { UseMutationOptions, useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

type SetStepBody = {
	step: string;
};

export function useSetStep(
	options?: UseMutationOptions<unknown, unknown, SetStepBody, unknown>,
) {
	return useMutation({
		...options,
		mutationFn: async (body: SetStepBody) =>
			apiFetch({
				path: '/wp/v2/onboarding/step',
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},

				body: JSON.stringify(body),
			}),
	});
}
