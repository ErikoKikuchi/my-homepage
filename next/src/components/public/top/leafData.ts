export interface LeafItem {
  id: string;
  label: string;
  page: string;
  description: string;
}

export const leafItems: LeafItem[] = [
  {
    id: "about",
    label: "About",
    page: "/about",
    description:
      "からだ散歩について。理学療法士×ピラティス×エンジニアとして伝えたいこと",
  },
  {
    id: "pilates",
    label: "Pilates",
    page: "/pilates",
    description: "自分の身体を知るピラティス。予約・記録はこちらから。",
  },
  {
    id: "thinkMotion",
    label: "ThinkMotion",
    page: "/thinkmotion",
    description: "学びの記録。日々の気づきをストックする場所。",
  },
  {
    id: "code",
    label: "Code",
    page: "/coming-soon",
    description: "ITを学び始めて。子供に伝えたいことをまとめた場所。",
  },
];
