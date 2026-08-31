// components/public/start-here/navCardData.ts
export interface NavCardItem {
  id: string;
  name: string;
  description: string[]; // 複数行に分かれているため配列で保持
  href: string;
}

export const navCardItems: NavCardItem[] = [
  {
    id: "pilates",
    name: "Pilates",
    description: [
      "自分の身体を知るためのセッション。観察と対話を重ねながら、内側から動きを変えていきます。",
      "予約・記録はこちらから。",
    ],
    href: "/pilates",
  },
  {
    id: "thinkMotion",
    name: "ThinkMotion",
    description: [
      "理学療法士・身体の専門家としての思考の記録。原理と感覚の間で考えてきたことをストックしています。",
    ],
    href: "/thinkmotion",
  },
  {
    id: "code",
    name: "Code",
    description: [
      "考えるとは何か。プログラミングを通して感じたこと、子供に伝えたいことをアプリにしました。",
    ],
    href: "/code",
  },
  {
    id: "about",
    name: "About",
    description: [
      "私という人間について。身体・動き・学びへのスタンスをまとめています。",
    ],
    href: "/about",
  },
];
