// @ts-check
import { defineConfig } from 'astro/config';
import tailwindcss from '@tailwindcss/vite';
import node from '@astrojs/node';

const isDev = process.env.NODE_ENV === 'development';

// https://astro.build/config
export default defineConfig({
  output: 'static',
  // Adapter only needed for production SSR (causes dev-toolbar conflicts in dev mode)
  adapter: isDev ? undefined : node({ mode: 'standalone' }),
  vite: {
    plugins: [tailwindcss()]
  }
});