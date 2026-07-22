import { defineConfig } from 'vite';
import { resolve } from 'path';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    tailwindcss(),
  ],
  // 💡 ปิดการทำงานของ publicDir เพื่อไม่ให้ Vite ไปลากไฟล์ระบบ (index.php, .htaccess) ใน public มาคอมไพล์ซ้อน
  publicDir: false, 

  build: {
    outDir: resolve(__dirname, 'public/dist'),
    emptyOutDir: false, // ป้องกันการลบไฟล์อื่นในโฟลเดอร์ปลายทาง
    
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/ts/main.ts'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: ({ name }) => {
          if (name && name.endsWith('.css')) {
            return 'css/main.[ext]';
          }
          return 'assets/[name].[ext]';
        },
      },
    },
  },
});