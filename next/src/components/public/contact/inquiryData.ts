// next/src/components/public/contact/inquiryData.ts

export type InquiryItem = {
  id: "pilates" | "thinkMotion" | "lecture" | "dx";
  title: string;
  description: string;
  googleFormUrl: string;
  topics: string[]; // フォーム項目確定後に記入
};

export const inquiryItems: InquiryItem[] = [
  {
    id: "pilates",
    title: "Pilatesについてのお問い合わせ",
    description:
      "Pilatesのレッスン内容/開催場所/回数券/自主トレログBodyMind等に関するお問い合わせはこちら",
    googleFormUrl: "https://forms.gle/Tu92C42zi3UCMKtk6",
    topics: [
      "予約・キャンセル",
      "開催場所",
      "回数券",
      "レッスン内容",
      "BodyMind",
      "その他",
    ],
  },
  {
    id: "lecture",
    title: "講座（腰痛予防教室等）のご依頼・問い合わせ",
    description: "腰痛予防教室・産業理学療法に関するご依頼・問い合わせはこちら",
    googleFormUrl: "https://forms.gle/JXitAvkWtLitgHop8",
    topics: [
      "腰痛予防教室",
      "産業理学療法",
      "福利厚生でのピラティス教室",
      "その他",
    ],
  },
  {
    id: "thinkMotion",
    title: "ThinkMotionについてのお問い合わせ",
    description:
      "ThinkMotionのお問い合わせ・追加機能の依頼や法人利用のご相談等はこちらへ",
    googleFormUrl: "https://forms.gle/JSEYiNKwzm2CKPNr8",
    topics: ["ユーザー登録", "追加機能", "ルーム", "料金等", "その他"],
  },
  {
    id: "dx",
    title: "DX/ホームページ関連のご依頼・問い合わせ",
    description:
      "DX/ホームページ関連の依頼やお問い合わせ・エンジニアとしてのお仕事のご相談等はこちらへ",
    googleFormUrl: "https://forms.gle/5DJahp4GnMqQMZQR9",
    topics: [
      "LINE運用初期設定",
      "ホームページ作成依頼",
      "エンジニアとしてのお仕事のご相談",
      "その他",
    ],
  },
];
