import { ArticlePost } from "@/types/thinkmotion/article";

export const dummyArticles: ArticlePost[] = [
  {
    id: "1",
    title: "ThinkMotionに込めた思い",
    publishedAt: "2026-09-15",
    href: "/thinkmotion/articles/dummy/1",
    room: {
      name: "管理人の部屋",
      href: "/thinkmotion/rooms/dummy/1",
    },
    author: {
      name: "Eriko",
      href: "/thinkmotion/authors/dummy/1",
    },
    category: "myStudy",
  },
  {
    id: "2",
    title: "理学療法と開発の交差点",
    publishedAt: "2026-09-16",
    href: "/thinkmotion/articles/dummy/2",
    room: {
      name: "管理人の部屋",
      href: "/thinkmotion/rooms/dummy/1",
    },
    author: {
      name: "Eriko",
      href: "/thinkmotion/authors/dummy/1",
    },
    category: "myStudy",
  },
  {
    id: "3",
    title: "病院に行きたくない人はめっちゃ多い",
    publishedAt: "2026-09-17",
    href: "/thinkmotion/articles/dummy/3",
    room: {
      name: "整形外科の部屋",
      href: "/thinkmotion/rooms/dummy/2",
    },
    author: {
      name: "Eriko",
      href: "/thinkmotion/authors/dummy/1",
    },
    category: "caseDiscussion",
  },
];
export function getMyStudies(): ArticlePost[] {
  return dummyArticles.filter((post) => post.category === "myStudy");
}
export function getCaseDiscussions(): ArticlePost[] {
  return dummyArticles.filter((post) => post.category === "caseDiscussion");
}
export function getReadings(): ArticlePost[] {
  return dummyArticles.filter((post) => post.category === "reading");
}
