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
    label: "Pilatesの販売価格について",
    value:
      "料金:5000円/1回\n14,000円/3回\n45,000円/10回（苫小牧の業務提携先でのご利用の場合は一律で1回あたり2,000円加算されます。）\nキャンセル料:500円/回(*状況によりご相談に応じます)\n交通費:業務提携先開催は加算料金に含まれています、町内開催は料金に含まれます。\n講座依頼:内容により個別見積り(要問い合わせ)",
  },
  {
    label: "ThinkMotionの販売価格について",
    value:
      "基本機能:無料\nルーム作成料:[金額]円(確定次第記載)\n追加機能(PDF化等):今後提供予定、価格未定",
  },
  {
    label: "支払方法",
    value:
      "現金(Pilatesのみ)、クレジットカード決済(Stripe経由)、銀行振込(Stripe経由)",
  },
  {
    label: "ピラティス施術の支払いについて",
    value:
      "回数券:ご購入時に前払い\n都度払い:施術実施後にお支払い\n※ご予約はあくまで回数券利用の申告であり、その時点でのお支払いは発生しません",
  },
  {
    label: "ThinkMotionの支払い及び提供について",
    value:
      "決済確認後にご利用いただけます(前払い)/ ルーム作成は決済確認後、通常3営業日以内",
  },
  {
    label: "ThinkMotionのキャンセル・返品について",
    value: "デジタルサービスの性質上、提供開始後の返金は原則不可",
  },
];
