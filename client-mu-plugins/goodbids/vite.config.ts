import { resolve } from 'path'
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
	plugins: [react()],
	build: {
		manifest: true,
		rollupOptions: {
			input: {
				main: resolve(__dirname, '/assets/js/main.tsx'),
				admin: resolve(__dirname, '/assets/js/admin.tsx'),
			},
		},
	},
})
