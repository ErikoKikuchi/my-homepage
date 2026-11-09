export const admin = "/pilates/admin";

export type NamedOption = { id: string; name: string };
export type LessonTemplate = { id: string; start_time: string; end_time: string; is_active: boolean };
export type LessonSlot = {
    id: string;
    date: string;
    lesson_template_id: string;
    location_id: string | null;
    lesson_template: LessonTemplate;
    location?: NamedOption | null;
};

export function dateInputValue(value: string) {
    // Date-only Eloquent casts may be serialized as UTC timestamps.
    return value.includes("T")
        ? new Intl.DateTimeFormat("sv-SE", { timeZone: "Asia/Tokyo" }).format(new Date(value))
        : value;
}

export function lessonTime(template: LessonTemplate) {
    return `${template.start_time} 〜 ${template.end_time}`;
}
