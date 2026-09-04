import SiteNav from "@/components/public/common/SiteNav";
import SiteFooter from "@/components/public/common/SiteFooter";
import { Metadata } from "next";

export default function PilatesLayout({
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
export const metadata: Metadata = {
  openGraph: {
    images: ["/images/PilatesOGP.png"],
  },
};
