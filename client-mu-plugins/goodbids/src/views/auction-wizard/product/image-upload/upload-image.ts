import { UseMutationOptions, useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

type UploadImageAPISuccessResponse = {
	id: number;
	source_url: string;
};

type UploadImageSuccessResponse = {
	id: number;
	url: string;
	fileName: string;
};

export function useUploadImage(
	options?: UseMutationOptions<
		UploadImageSuccessResponse,
		unknown,
		File,
		unknown
	>,
) {
	return useMutation({
		...options,
		mutationFn: async (file: File) => {
			const result = await apiFetch<UploadImageAPISuccessResponse>({
				path: '/wp/v2/media',
				method: 'POST',
				body: file,
				headers: {
					'Content-Disposition':
						'attachment; filename=' +
						file.name.replace(/\s|\x{202f}/g, '_'),
					'Content-Type': file.type,
				},
			});

			return {
				id: result.id,
				url: result.source_url,
				fileName: file.name,
			};
		},
	});
}
