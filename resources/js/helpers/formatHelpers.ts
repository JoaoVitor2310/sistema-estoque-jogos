import dayjs from "dayjs";

export const formatDateToBR = (dateString: string): string => {
    if (!dateString) return '';

    const date = new Date(dateString);

    if (!isNaN(date.getTime())) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
    }

    if (dateString.includes('-')) {
        const [year, month, day] = dateString.split('-');
        return `${day}/${month}/${year}`;
    }

    console.error("Formato de data inválido:", dateString);
    return '';
};

export const formatDateToDB = (date: string): string => {
    return dayjs(date).isValid() ? dayjs(date).format('YYYY-MM-DD') : '';
};