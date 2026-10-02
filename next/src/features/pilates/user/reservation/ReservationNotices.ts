export const reservationNotices = [
  {
    id: "place",
    text: "実施場所は予約申請後に確保し、確定後にご連絡いたします。施設状況等によりご希望に添えない場合は、別施設または日程をご相談させていただきます。",
  },
  {
    id: "publicFacility",
    text: "公共施設では冠婚葬祭・選挙等により急な予定変更依頼がある可能性がありますので、ご了承ください。",
  },
  {
    id: "line",
    text: "LINEにて連絡をいたしますので、お済みでない方は事前にマイページにて登録をお願いいたします。",
  },
  {
    id: "cancel",
    text: "キャンセルは前日の正午12：00までにお願いします。それ以降のお客様都合のキャンセルは一律500円いただきます。",
  },
] as const;

export type NoticeId = (typeof reservationNotices)[number]["id"];
export type Agreements = Record<NoticeId, boolean>;
export type Notice = (typeof reservationNotices)[number];
