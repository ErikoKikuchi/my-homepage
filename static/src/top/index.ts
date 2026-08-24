// ===== 型定義 =====
type LeafState = "falling" | "settled" | "active";

interface LeafData {
  element: HTMLLIElement;
  state: LeafState;
  description: string;
}

// ===== ユーティリティ =====
const delay = (ms: number): Promise<void> =>
  new Promise((resolve) => setTimeout(resolve, ms));

// ===== 葉っぱ落下制御 =====
async function dropLeaves(leaves: LeafData[]): Promise<void> {
  for (const leaf of leaves) {
    const outer = leaf.element.closest(".leaf-outer") as HTMLElement;
    if (outer) {
      outer.style.animation = "fall 5.0s ease forwards";
    }
    await delay(200);
  }
  // 葉っぱは落ちながら揺れる
  await delay(800);
}

// ===== 葉っぱ定着 =====
function settleLeaves(leaves: LeafData[]): void {
  leaves.forEach((leaf) => {
    leaf.state = "settled";
    leaf.element.closest(".leaf-outer")!.classList.add("settled");
  });
}

// ===== 説明文表示 =====
function showDescription(text: string): void {
  const area = document.getElementById("leaf-description");
  const textEl = document.getElementById("description-text");
  if (!area || !textEl) return;

  textEl.textContent = text;
  area.classList.add("visible");
}

// ===== イベント登録 =====
function setupLeafEvents(leaves: LeafData[]): void {
  leaves.forEach((leaf) => {
    // hover
    leaf.element.addEventListener("mouseenter", () => {
      if (leaf.state === "settled") {
        showDescription(leaf.description);
        leaves.forEach((l) => l.element.classList.remove("active"));
        leaf.element.classList.add("active");
        leaf.state = "active";
      }
    });

    // タップ（モバイル）
    leaf.element.addEventListener(
      "touchstart",
      (e) => {
        e.preventDefault();
        if (leaf.state === "settled" || leaf.state === "active") {
          showDescription(leaf.description);
          leaves.forEach((l) => {
            l.element.classList.remove("active");
            if (l.state === "active") l.state = "settled";
          });
          leaf.element.classList.add("active");
          leaf.state = "active";
        }
      },
      { passive: false },
    );

    // click → ページ遷移（後で実装）
    leaf.element.addEventListener("click", () => {
      const page = leaf.element.dataset["page"];
      if (page) {
        // TODO: 遷移先URLが決まったら追加
        console.log(`navigate to: ${page}`);
      }
    });
  });
}

// ===== メイン =====
async function main(): Promise<void> {
  const conceptEl = document.getElementById("concept");
  const leafEls = document.querySelectorAll<HTMLLIElement>(".leaf-inner");

  if (!conceptEl) return;

  // 葉っぱデータを構築
  const leaves: LeafData[] = Array.from(leafEls).map((el) => ({
    element: el,
    state: "falling" as LeafState,
    description: el.dataset["description"] ?? "",
  }));

  // シーケンス開始
  await delay(3000); // タイトルを見せる時間
  await showConceptWords(conceptEl); // コンセプトワード（約4.5秒）
  await dropLeaves(leaves); // 葉っぱ落下
  settleLeaves(leaves); // 定着
  showDescription(leaves[0]?.description ?? ""); // 最初の説明文を表示
  setupLeafEvents(leaves); // イベント登録
}

main();
