// フォーム側が送信する形(FormRequestの `rules()` に対応)
export interface LoginCredentials {
  email: string;
  password: string;
  remember: boolean;
}

// ログイン成功時のレスポンス
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
//登録の形
export interface RegisterCredentials {
  name: string;
  email: string;
  password: string;
  passwordConfirmation: string;
  service: "pilates" | "thinkmotion" | null;
}
//登録成功時のレスポンス
export interface RegisterSuccessResponse {
  redirectTo: string;
}

//ログインしているピラティスユーザ－の型
export type AuthPilatesUser = {
  name: string;
  canUseTrainingLog: boolean;
};

//認証状態
type AuthPilatesState = {
  user: AuthPilatesUser | null;
  isLoading: boolean;
};
export interface LogoutSuccessResponse {
  redirectTo: string;
}
//ログインしているThinkMotionユーザ－の型
export type AuthThinkMotionUser = {
  name: string;
};
