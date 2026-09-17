// フォーム側が送信する形(FormRequestの `rules()` に対応)
export interface LoginCredentials {
  email: string;
  password: string;
  remember: boolean;
}

// 成功時のレスポンス
export interface LoginSuccessResponse {
  redirectTo: string;
}

// Laravel標準のバリデーションエラー形式(422 + errorsキーあり)
export interface ValidationErrorResponse {
  message: string;
  errors: Record<string, string[]>;
}

// 認証情報不一致(422 + errorsキーなし)
export interface AuthErrorResponse {
  message: string;
}
