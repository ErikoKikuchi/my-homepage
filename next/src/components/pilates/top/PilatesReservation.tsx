"use client";

import Link from "next/link";
import styles from "./PilatesReservation.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

export default function PilatesReservation() {
  return (
    <>
      <p className={styles.label}>
        身体を知るところから、始めてみませんか。
        <br />
        こちらからご予約いただけます。
      </p>

      <div>
        <LinkButton href="/pilates/guest">空き確認・予約</LinkButton>
      </div>
    </>
  );
}
