// next/src/app/(public)/layout.tsx
import SiteNav from "@/components/public/SiteNav";
import SiteFooter from "@/components/public/SiteFooter";
import "./\(public\)/globals.css";

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
