import AdminPage from "../AdminPage";
import TemplateForm from "./TemplateForm";

export default function LessonTemplatesCreate() {
    return (
        <AdminPage heading="レッスン時間帯テンプレート作成">
            <TemplateForm />
        </AdminPage>
    );
}
