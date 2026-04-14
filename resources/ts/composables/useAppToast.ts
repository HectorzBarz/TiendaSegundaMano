import { useToast as usePrimeToast } from "primevue/usetoast";

type ToastSeverity = "success" | "info" | "warn" | "error";

type ToastPosition =
    | "top-right"
    | "top-center"
    | "top-left"
    | "center"
    | "bottom-right"
    | "bottom-center"
    | "bottom-left";

interface ToastOptions {
    message: string;
    severity?: ToastSeverity;
    summary?: string;
    life?: number;
    position?: ToastPosition;
}

export function useAppToast(defaultPosition: ToastPosition = "top-right") {
    const toast = usePrimeToast();

    const show = ({
        message,
        severity = "info",
        summary = "Info",
        life = 3000,
        position,
    }: ToastOptions) => {
        const finalPosition = position ?? defaultPosition;

        toast.add({
            severity,
            summary,
            detail: message,
            life,
            group: finalPosition, // 👈 aquí está la clave
        });
    };

    return { show };
}
