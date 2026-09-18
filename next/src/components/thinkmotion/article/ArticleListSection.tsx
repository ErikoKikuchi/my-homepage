import ArticleListCard from "@/components/thinkmotion/article/ArticleListCard";
import type { ArticlePost } from "@/types/thinkmotion/article";
import styles from "@/components/thinkmotion/article/ArticleListSection.module.css";

interface ArticleListSectionProps {
  description: string;
  posts: ArticlePost[];
}

export default function ArticleListSection({
  description,
  posts,
}: ArticleListSectionProps) {
  return (
    <div className={styles.articleListSection}>
      <div className={styles.description}>{description}</div>
      <table className={styles.articleList}>
        <thead>
          <tr>
            <th style={{ width: "15%" }}>日付</th>
            <th style={{ width: "45%" }}>タイトル</th>
            <th style={{ width: "20%" }}>投稿ルーム</th>
            <th style={{ width: "20%" }}>投稿者</th>
          </tr>
        </thead>
        <tbody>
          {posts.map((post) => (
            <ArticleListCard key={post.href} post={post} />
          ))}
        </tbody>
      </table>
    </div>
  );
}
