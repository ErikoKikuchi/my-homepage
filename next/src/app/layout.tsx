import type { Metadata } from "next";
import { Noto_Serif_JP, Noto_Sans_JP } from "next/font/google";
import "./globals.css";
import { Viewport } from "next";

const notoSerifJP = Noto_Serif_JP({
  subsets: ["latin"],
  weight: ["400", "700"],
  variable: "--font-serif",
  display: "swap",
});

const notoSansJP = Noto_Sans_JP({
  subsets: ["latin"],
  weight: ["400", "700"],
  variable: "--font-gothic",
  display: "swap",
});

export const metadata: Metadata = {
  title: {
    default: "からだ散歩",
    template: "%s | からだ散歩",
  },
  description:
    "からだ散歩は、ピラティスの予約や自主トレを記録できるサイトです。臨床家向けにブログ・症例検討・読書記録等の思考整理の場を提供するThinkMotionも運営しています。",
  metadataBase: new URL(
    process.env.NEXT_PUBLIC_SITE_URL ?? "http://localhost:3000",
  ),
  openGraph: {
    title: "からだ散歩",
    description:
      "からだ散歩は、ピラティスの予約や自主トレを記録できるサイトです。臨床家向けにブログ・症例検討・読書記録等の思考整理の場を提供するThinkMotionも運営しています。",
    images: ["/images/MainOGP.png"], // 汎用画像
    locale: "ja_JP",
    type: "website",
  },
};
export default function RootLayout({ children }: LayoutProps<"/">) {
  return (
    <html
      lang="ja"
      className={`${notoSerifJP.variable} ${notoSansJP.variable} h-full antialiased`}
    >
      <body className="min-h-full flex flex-col">{children}</body>
    </html>
  );
}
export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
};
