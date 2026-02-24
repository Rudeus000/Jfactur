import axios, { AxiosError, InternalAxiosRequestConfig } from "axios";

const API_BASE_URL = import.meta.env.VITE_API_URL || "http://localhost:8000/api/v1";

interface TokenPair {
  access: string;
  refresh: string;
}

// Token management
let accessToken: string | null = null;

export const getAccessToken = () => accessToken;

export const setTokens = (tokens: TokenPair) => {
  accessToken = tokens.access;
  localStorage.setItem("refresh_token", tokens.refresh);
};

export const clearTokens = () => {
  accessToken = null;
  localStorage.removeItem("refresh_token");
};

export const getRefreshToken = () => localStorage.getItem("refresh_token");

// Axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: { "Content-Type": "application/json" },
});

// Request interceptor
api.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  if (accessToken) {
    config.headers.Authorization = `Bearer ${accessToken}`;
  }
  return config;
});

// Response interceptor with refresh logic
let isRefreshing = false;
let failedQueue: Array<{
  resolve: (token: string) => void;
  reject: (error: unknown) => void;
}> = [];

const processQueue = (error: unknown, token: string | null = null) => {
  failedQueue.forEach((prom) => {
    if (error) {
      prom.reject(error);
    } else {
      prom.resolve(token!);
    }
  });
  failedQueue = [];
};

api.interceptors.response.use(
  (response) => response,
  async (error: AxiosError) => {
    const originalRequest = error.config as InternalAxiosRequestConfig & { _retry?: boolean };

    if (error.response?.status === 401 && !originalRequest._retry) {
      if (isRefreshing) {
        return new Promise((resolve, reject) => {
          failedQueue.push({
            resolve: (token: string) => {
              originalRequest.headers.Authorization = `Bearer ${token}`;
              resolve(api(originalRequest));
            },
            reject,
          });
        });
      }

      originalRequest._retry = true;
      isRefreshing = true;

      const refreshToken = getRefreshToken();
      if (!refreshToken) {
        clearTokens();
        window.location.href = "/login";
        return Promise.reject(error);
      }

      try {
        const { data } = await axios.post<TokenPair>(`${API_BASE_URL}/auth/token/refresh/`, {
          refresh: refreshToken,
        });
        setTokens(data);
        processQueue(null, data.access);
        originalRequest.headers.Authorization = `Bearer ${data.access}`;
        return api(originalRequest);
      } catch (refreshError) {
        processQueue(refreshError, null);
        clearTokens();
        window.location.href = "/login";
        return Promise.reject(refreshError);
      } finally {
        isRefreshing = false;
      }
    }

    return Promise.reject(error);
  }
);

export default api;

// Error message helper
export const getErrorMessage = (error: unknown): string => {
  if (axios.isAxiosError(error)) {
    const status = error.response?.status;
    const data = error.response?.data;

    // Sin respuesta = servidor no alcanzable (backend apagado, CORS, red)
    if (!error.response) {
      return "No se pudo conectar con el servidor. Compruebe que el backend esté en ejecución (http://localhost:8000) y vuelva a intentar.";
    }

    if (typeof data === "object" && data !== null) {
      const detail = (data as Record<string, unknown>).detail;
      if (typeof detail === "string") return detail;
      const nonFieldErrors = (data as Record<string, unknown>).non_field_errors;
      if (Array.isArray(nonFieldErrors)) return nonFieldErrors.join(", ");
    }

    switch (status) {
      case 400: return "Datos inválidos. Revise el formulario.";
      case 401: return "Correo o contraseña incorrectos.";
      case 403: return "No tiene permisos para esta acción.";
      case 404: return "Recurso no encontrado.";
      case 500: return "Error del servidor. Intente más tarde.";
      default: return "Error de conexión. Verifique su red.";
    }
  }
  return "Ocurrió un error inesperado.";
};
