// next/src/components/public/SiteNav.tsx
"use client";

import { useEffect, useRef } from "react";
import Link from "next/link";
import styles from "./SiteNav.module.css";

export default function SiteNav() {
  const navRef = useRef<HTMLElement>(null);

  useEffect(() => {
    const navEl = navRef.current;
    if (!navEl) return;

    const syncHeight = () => {
      const height = navEl.getBoundingClientRect().height;
      document.documentElement.style.setProperty(
        "--site-nav-height",
        `${height}px`,
      );
    };

    syncHeight();

    const resizeObserver = new ResizeObserver(syncHeight);
    resizeObserver.observe(navEl);

    return () => resizeObserver.disconnect();
  }, []);

  return (
    <nav ref={navRef} className={styles.siteNav}>
      <Link href="/" className={styles.logo}>
        からだ散歩
      </Link>
      <ul className={styles.navLinks}>
        <li>
          <Link href="/about">About</Link>
        </li>
        <li>
          <Link href="/pilates">Pilates</Link>
        </li>
        <li>
          <Link href="/thinkmotion">ThinkMotion</Link>
        </li>
        <li>
          <Link href="/code">Code</Link>
        </li>
        <li>
          <Link href="/contact">Contact</Link>
        </li>
      </ul>
    </nav>
  );
}
