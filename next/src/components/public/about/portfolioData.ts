export interface PortfolioItem {
  href: string;
  cardTag: string;
  cardTitle: string;
  cardDesc: string;
  stackTags?: string[];
}

export const PortfolioItems: PortfolioItem[] = [
  {
    href: "https://github.com/ErikoKikuchi/my-homepage",
    cardTag: "GitHub",
    cardTitle: "からだ散歩",
    cardDesc:
      "このサイト。理学療法士・ピラティス・プログラミングの交差点として設計・開発中。",
    stackTags: [
      "Laravel 12",
      "TypeScript",
      "REACT",
      "Next.js",
      "Vite",
      "MySQL",
    ],
  },
  {
    href: "https://note.com/body_and_code",
    cardTag: "Note",
    cardTitle: "BodyAndCode",
    cardDesc: "開発への思いを掲載中",
  },
];
