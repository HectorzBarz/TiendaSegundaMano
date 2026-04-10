export function useCopyToClipboard(textToCopy: string) {
    const copyToClipboard = async () => {
        try {
            await navigator.clipboard.writeText(textToCopy);
            console.log("Texto copiado!");
        } catch (err) {
            console.error("Error al copiar:", err);
        }
    };
    return { copyToClipboard };
}
