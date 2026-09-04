// next/src/app/(public)/layout.tsx
import SiteNav from "@/components/public/common/SiteNav";
import SiteFooter from "@/components/public/common/SiteFooter";
import { Metadata } from "next";

export default function PublicLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <>
      <SiteNav />
      {children}
      <SiteFooter />
    </>
  );
}
