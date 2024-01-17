type ApiRequestParams = {
	path: string;
	method?: 'GET' | 'POST' | 'PUT' | 'DELETE';
	body?: unknown;
};

export async function apiHandler<T>({
	path,
	method,
	body,
}: ApiRequestParams): Promise<T> {
	const url = window.location.href.replace(/\/auction\/.*/g, '');

	const response = await fetch(`${url}${path}`, {
		method: method ?? 'GET',
		body: body ? JSON.stringify(body) : null,
		headers: {
			'Content-Type': 'application/json',
		},
	});

	if (response.ok) {
		return (await response.json()) as T;
	}

	if (response.status < 500) {
		return Promise.reject(await response.json());
	}

	return Promise.reject({ error: 'Server error' });
}
