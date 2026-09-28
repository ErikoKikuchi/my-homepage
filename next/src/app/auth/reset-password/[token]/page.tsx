import { ResetPasswordForm } from "@/components/auth/ResetPasswordForm";

export default async function Page({
  params,
  searchParams,
}: {
  params: Promise<{ token: string }>;
  searchParams: Promise<{ email?: string }>;
}) {
  const { token } = await params;
  const { email } = await searchParams;
  return <ResetPasswordForm token={token} initialEmail={email ?? ""} />;
}
