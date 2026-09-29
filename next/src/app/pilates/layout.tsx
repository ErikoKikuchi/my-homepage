import SiteNav from "@/components/public/common/SiteNav";
import SiteFooter from "@/components/public/common/SiteFooter";
import { Metadata } from "next";
import { PilatesAuthProvider } from "@/contexts/PilatesAuthContext";
import PilatesSubNav from "@/components/public/pilates/PilatesSubNav";

export default function PilatesLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <PilatesAuthProvider>
      <SiteNav />
      <PilatesSubNav />
      {children}
      <SiteFooter />
    </PilatesAuthProvider>
  );
}
export const metadata: Metadata = {
  openGraph: {
    images: ["/site-images/PilatesOGP.png"],
  },
};
