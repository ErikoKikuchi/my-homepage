import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";
import inertia from "@inertiajs/vite";
import { fileURLToPath, URL } from "node:url";

export default defineConfig({
    resolve: {
        alias: {
            "@": fileURLToPath(new URL("./resources/js", import.meta.url)),
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css", 
                "resources/js/app.js", 
                "resources/js/app.tsx",
            ],
            refresh: true,
        }),
        react(),
        inertia(),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
