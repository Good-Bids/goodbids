import { useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

export function useDeleteImage() {
	return useMutation({
		mutationFn: async (id: number) => {
			return await apiFetch({
				path: `/wp/v2/media/${id}?force=1`,
				method: 'DELETE',
			});
		},
	});
}
