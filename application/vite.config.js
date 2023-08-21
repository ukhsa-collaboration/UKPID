// eslint-disable-next-line import/no-extraneous-dependencies
import { defineConfig } from "vite";
// eslint-disable-next-line import/no-unresolved
import laravel from "laravel-vite-plugin";

export default defineConfig({
  server: {
    host: true,
    port: 35174,
  },
  plugins: [
    laravel({
      input: ["resources/css/app.scss", "resources/js/app.js"],
      refresh: true,
    }),
  ],
});
