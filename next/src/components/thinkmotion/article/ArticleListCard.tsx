import { ArticlePost } from "@/types/thinkmotion/article";
import Link from "next/link";
import styles from "@/components/thinkmotion/article/ArticleListCard.module.css";

type Props = {
  post: ArticlePost;
};
export default function ArticleListCard({ post }: Props) {
  return (
    <tr>
      <td className={styles.publishedAt}>{post.publishedAt}</td>
      <td>
        <Link className={styles.title} href={post.href}>
          {post.title}
        </Link>
      </td>
      <td>
        <Link className={styles.roomName} href={post.room.href}>
          {post.room.name}
        </Link>
      </td>
      <td>
        <Link className={styles.authorName} href={post.author.href}>
          {post.author.name}
        </Link>
      </td>
    </tr>
  );
}
