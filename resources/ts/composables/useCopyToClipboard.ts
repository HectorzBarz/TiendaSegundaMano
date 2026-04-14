import { useAppToast } from "./useAppToast";

export function useCopyToClipboard(textToCopy: string) {
    const { show } = useAppToast();

    const copyToClipboard = async () => {
        try {
            await navigator.clipboard.writeText(textToCopy);

            show({
                message: "¡Correo electrónico copiado en el portapapeles!",
                severity: "success",
                summary: "Copiado",
                life: 3000,
                position: "top-right",
            });
        } catch (err) {
            console.error("Error al copiar:", err);

            show({
                message: "No se pudo copiar el texto",
                severity: "error",
                summary: "Error",
                life: 3000,
                position: "top-right",
            });
        }
    };

    return { copyToClipboard };
}
