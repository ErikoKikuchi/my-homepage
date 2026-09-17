export type ArticlePost = {
  id: string;
  title: string;
  publishedAt: string | null;
  href: string; // 記事本体へのリンク
  room: { name: string; href: string }; //ルームへのリンク
  author: { name: string; href: string }; // 投稿者ページへのリンク
};
