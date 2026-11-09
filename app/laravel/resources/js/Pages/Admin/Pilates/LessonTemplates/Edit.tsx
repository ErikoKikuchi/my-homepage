import AdminPage from "../AdminPage";
import TemplateForm from "./TemplateForm";
import type { LessonTemplate } from "../schedule";

export default function LessonTemplatesEdit({ lessonTemplate }: { lessonTemplate: LessonTemplate }) {
    return (
        <AdminPage heading="レッスン時間帯テンプレート編集">
            <TemplateForm template={lessonTemplate} />
        </AdminPage>
    );
}
