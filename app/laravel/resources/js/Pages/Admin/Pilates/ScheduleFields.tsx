import { FieldError } from "./AdminPage";
import { lessonTime, type LessonTemplate, type NamedOption } from "./schedule";

type ScheduleFieldsProps = {
    lessonTemplates: LessonTemplate[];
    locations: NamedOption[];
    templateId: string;
    locationId: string;
    onTemplateChange: (value: string) => void;
    onLocationChange: (value: string) => void;
    errors: { lesson_template_id?: string; location_id?: string };
};

export default function ScheduleFields({
    lessonTemplates, locations, templateId, locationId, onTemplateChange, onLocationChange, errors,
}: ScheduleFieldsProps) {
    return (
        <>
            <div>
                <label htmlFor="lesson_template_id">時間帯（必須）</label>
                <select id="lesson_template_id" value={templateId} required onChange={(event) => onTemplateChange(event.target.value)}>
                    <option value="">選択してください</option>
                    {lessonTemplates.map((template) => (
                        <option key={template.id} value={template.id}>
                            {lessonTime(template)}{!template.is_active && "（無効化済み）"}
                        </option>
                    ))}
                </select>
                <FieldError message={errors.lesson_template_id} />
            </div>
            <div>
                <label htmlFor="location_id">開催場所（固定する場合のみ選択）</label>
                <select id="location_id" value={locationId} onChange={(event) => onLocationChange(event.target.value)}>
                    <option value="">未定（予約時に確保）</option>
                    {locations.map((location) => <option key={location.id} value={location.id}>{location.name}</option>)}
                </select>
                <FieldError message={errors.location_id} />
            </div>
        </>
    );
}
