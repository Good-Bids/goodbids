import { useCallback, useState } from 'react';

function fallbackCopy(text: string) {
	const tempTextArea = document.createElement('textarea');
	tempTextArea.value = text;
	document.body.appendChild(tempTextArea);
	tempTextArea.select();
	document.execCommand('copy');
	document.body.removeChild(tempTextArea);
}

export function useCopyToClipboard() {
	const [state, setState] = useState<string | null>(null);

	const clearState = useCallback(() => {
		setState(null);
	}, []);

	const copyToClipboard = useCallback((value: string) => {
		const handleCopy = async () => {
			try {
				if (navigator?.clipboard?.writeText) {
					await navigator.clipboard.writeText(value);
					setState(value);
				} else {
					throw new Error('writeText not supported');
				}
			} catch (e) {
				fallbackCopy(value);
				setState(value);
			}
		};

		void handleCopy();
	}, []);

	return [state !== null, clearState, copyToClipboard] as const;
}
