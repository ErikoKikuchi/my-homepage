import { Head } from "@inertiajs/react";

type Props = {
    pendingReservationCount: number;
};

export default function Dashboard({
    pendingReservationCount,
}: Props) {
    return (
        <>
            <Head title="管理画面 – からだ散歩" />

            <main className="min-h-screen bg-gray-50 p-8">
                <div className="mx-auto max-w-5xl">
                    <h1 className="text-2xl font-bold">
                        Pilates 管理画面
                    </h1>

                    <section className="mt-6 rounded-lg bg-white p-6 shadow-sm">
                        <h2 className="text-lg font-semibold">
                            会場確定待ちの予約
                        </h2>

                        <p className="mt-3 text-3xl font-bold">
                            {pendingReservationCount}
                            <span className="ml-2 text-base font-normal">
                                件
                            </span>
                        </p>
                    </section>
                </div>
            </main>
        </>
    );
}