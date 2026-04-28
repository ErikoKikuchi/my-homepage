import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    rollupOptions: {
        input: {
            'site-nav': 'shared/components/site-nav.ts',
            'site-footer': 'shared/components/site-footer.ts',
        },
        output: {
            entryFileNames: 'assets/[name].js',
        }
    }
  }
})