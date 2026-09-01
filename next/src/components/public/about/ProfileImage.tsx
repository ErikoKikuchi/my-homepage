import styles from "./ProfileImage.module.css";
import Image from "next/image";

type ProfileImageProps = {
  src: string;
  alt: string;
};

export default function ProfileImage({ src, alt }: ProfileImageProps) {
  return (
    <div className={styles.profileImagePlaceholder}>
      <Image
        src={src}
        alt={alt}
        fill
        sizes="(max-width: 600px) 100vw, 33vw"
        className={styles.profilePhoto}
      />
    </div>
  );
}
