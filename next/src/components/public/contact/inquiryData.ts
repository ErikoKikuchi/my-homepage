// next/src/components/public/contact/inquiryData.ts

export interface InquiryItem {
  id: string;
  label: string;
  formUrl: string; // 仮URL、フォーム作成後に差し替え
}

export const inquiryItems: InquiryItem[] = [
  {
    id: "general",
    label: "全般のお問い合わせ",
    formUrl: "https://forms.google.com/PLACEHOLDER_GENERAL",
  },
  {
    id: "pilates",
    label: "Pilatesについてのお問い合わせ",
    formUrl: "https://forms.google.com/PLACEHOLDER_PILATES",
  },
  {
    id: "dx",
    label: "ホームページ等DX関連のご依頼",
    formUrl: "https://forms.google.com/PLACEHOLDER_DX",
  },
  {
    id: "lecture",
    label: "腰痛予防教室など講座のご依頼",
    formUrl: "https://forms.google.com/PLACEHOLDER_LECTURE",
  },
  {
    id: "thinkMotion",
    label: "ThinkMotionについてのお問い合わせ",
    formUrl: "https://forms.google.com/PLACEHOLDER_THINKMOTION",
  },
];
