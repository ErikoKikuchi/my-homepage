// next/src/components/public/tokushoho/tokushohoData.ts

export type TokushohoItem = {
  label: string;
  value: string; // 改行を含む場合は \n で表現し、表示側で分割する
};

export const tokushohoItems: TokushohoItem[] = [
  {
    label: "氏名",
    value: "菊地　恵理子(屋号:からだ散歩)",
  },
  {
    label: "所在地",
    value: "ご請求いただいた場合、遅滞なく開示いたします",
  },
  {
    label: "電話番号",
    value: "ご請求いただいた場合、遅滞なく開示いたします",
  },
  {
    label: "連絡先メールアドレス",
    value:
      "karada.sanpo@gmail.com\n住所・電話番号の開示請求は、こちらのメールアドレス宛にご連絡ください。ご連絡から1週間以内にご返答いたします。",
  },
  {
    label: "販売価格(Pilates)",
    value:
      "料金:5000円/1回\n14,000/3回\n45,000/10回（苫小牧の業務提携先でのご利用の場合は一律で１回あたり2,000円加算されます。）\nキャンセル料:[規定]\n交通費:業務提携先開催は加算料金に含まれています、安平町内開催は料金に含む\n講座依頼:内容により個別見積り(要問い合わせ)",
  },
  {
    label: "販売価格(ThinkMotion)",
    value:
      "基本機能:無料\nルーム作成料:[金額]円(確定次第記載)\n追加機能(PDF化等):今後提供予定、価格未定",
  },
  {
    label: "支払方法",
    value: "現金、クレジットカード決済、銀行振込(Stripe経由)",
  },
  {
    label: "支払時期",
    value: "購入時 / 予約確定時 / 講座実施後",
  },
  {
    label: "提供時期",
    value: "予約日当日 / ルーム作成は決済確認後、通常3営業日以内",
  },
  {
    label: "キャンセル・返品について",
    value:
      "ピラティス:キャンセル料は[規定]に基づき発生\nThinkMotion:デジタルサービスの性質上、提供開始後の返金は原則不可",
  },
];
