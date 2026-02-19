import { useState, useEffect, useCallback } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useToast } from "@/hooks/use-toast";

interface UseApiListOptions {
  endpoint: string;
  autoFetch?: boolean;
}

export function useApiList<T = unknown>({ endpoint, autoFetch = true }: UseApiListOptions) {
  const [data, setData] = useState<T[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();

  const fetchData = useCallback(async (params?: Record<string, string>) => {
    setIsLoading(true);
    try {
      const { data: result } = await api.get(endpoint, { params });
      setData(Array.isArray(result) ? result : result.results ?? []);
    } catch (error) {
      toast({ title: "Error", description: getErrorMessage(error), variant: "destructive" });
    } finally {
      setIsLoading(false);
    }
  }, [endpoint, toast]);

  useEffect(() => {
    if (autoFetch) fetchData();
  }, [autoFetch, fetchData]);

  const deleteItem = async (id: number | string) => {
    try {
      await api.delete(`${endpoint}${id}/`);
      toast({ title: "Eliminado correctamente" });
      fetchData();
    } catch (error) {
      toast({ title: "Error", description: getErrorMessage(error), variant: "destructive" });
    }
  };

  return { data, isLoading, refresh: fetchData, deleteItem };
}
