"use client";

import { useState } from "react";
import Link from "next/link";
import ConceptWords from "@/components/public/top/ConceptWords";
import Leaves from "@/components/public/top/Leaves";
import SiteFooter from "@/components/public/common/SiteFooter";
import styles from "./page.module.css";

export default function Page() {
  const [leavesStarted, setLeavesStarted] = useState(false);

  return (
    <>
      <div className={styles.scene}>
        <h1 className={`${styles.title} ${styles.sceneItem}`}>からだ散歩</h1>
        <p className={`${styles.subtitle} ${styles.sceneItem}`}>
          Body and Code
        </p>
        <ConceptWords onComplete={() => setLeavesStarted(true)} />
        <Leaves start={leavesStarted} />
      </div>

      <nav className={styles.bottomNav}>
        <Link href="/about">About</Link>
        <Link href="/pilates">Pilates</Link>
        <Link href="/thinkmotion">ThinkMotion</Link>
        <Link href="/code">Code</Link>
        <Link href="/contact">Contact</Link>
      </nav>

      <div className={styles.startHere}>
        <p className={styles.guide}>初めて訪れた方へ</p>
        <p className={styles.guide}>この場所の見方とはじめ方をまとめています</p>
        <Link href="/start-here" className={styles.entranceButton}>
          入り口はこちら
        </Link>
      </div>

      <SiteFooter />
    </>
  );
}
