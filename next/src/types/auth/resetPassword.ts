export interface ResetPasswordCredentials {
  token: string;
  email: string;
  password: string;
  passwordConfirmation: string;
}

export interface ResetPasswordSuccessResponse {
  message: string;
}
