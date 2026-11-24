import PageHeader from "@/components/public/common/PageHeader";
import TwoFactorChallenge from "@/features/auth/TwoFactorChallenge";

export default function TwoFactorChallengePage() {
  return (
    <main>
      <PageHeader heading="二要素認証" />
      <TwoFactorChallenge />
    </main>
  );
}
