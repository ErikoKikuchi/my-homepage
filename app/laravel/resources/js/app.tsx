import "./bootstrap";
import { createInertiaApp } from "@inertiajs/react";
import PilatesLayout from "@/Layouts/Admin/PilatesLayout";
import ThinkMotionLayout from "@/Layouts/Admin/ThinkMotionLayout";

createInertiaApp({
    pages: {
        path: "./Pages",
        extension: ".tsx",
    },
    layout: (name) => {
        if (name.startsWith("Admin/Pilates/")) {
            return PilatesLayout;
        }
        if (name.startsWith("Admin/ThinkMotion/")) {
            return ThinkMotionLayout;
        }

        return null;
    },
});
