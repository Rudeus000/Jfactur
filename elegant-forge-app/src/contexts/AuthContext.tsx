import React, { createContext, useContext, useState, useEffect, useCallback } from "react";
import api, { setTokens, clearTokens, getAccessToken, getRefreshToken, getErrorMessage } from "@/lib/api";

interface User {
  id: string;
  email: string;
  first_name: string;
  last_name: string;
  company?: { id: string; razon_social?: string; nombre_comercial?: string };
  role?: string;
  default_branch?: string | null;
  default_branch_name?: string | null;
  default_warehouse?: string | null;
  default_cash_register?: string | null;
}

interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  login: (email: string, password: string) => Promise<void>;
  logout: () => void;
  refreshUser: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | null>(null);

export const useAuth = () => {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used within AuthProvider");
  return ctx;
};

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  const fetchUser = useCallback(async () => {
    try {
      const { data } = await api.get<User>("/users/me/");
      setUser(data);
    } catch {
      clearTokens();
      setUser(null);
    }
  }, []);

  useEffect(() => {
    const init = async () => {
      const refresh = getRefreshToken();
      if (refresh && !getAccessToken()) {
        try {
          const { data } = await api.post("/auth/token/refresh/", { refresh });
          setTokens(data);
          await fetchUser();
        } catch {
          clearTokens();
        }
      } else if (getAccessToken()) {
        await fetchUser();
      }
      setIsLoading(false);
    };
    init();
  }, [fetchUser]);

  const login = async (email: string, password: string) => {
    try {
      const { data } = await api.post("/auth/token/", { email, password });
      setTokens(data);
      await fetchUser();
    } catch (error) {
      throw new Error(getErrorMessage(error));
    }
  };

  const logout = () => {
    clearTokens();
    setUser(null);
  };

  const refreshUser = useCallback(async () => {
    await fetchUser();
  }, [fetchUser]);

  return (
    <AuthContext.Provider value={{ user, isAuthenticated: !!user, isLoading, login, logout, refreshUser }}>
      {children}
    </AuthContext.Provider>
  );
};
