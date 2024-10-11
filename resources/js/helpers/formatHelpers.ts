import dayjs from "dayjs";

export const formatDateToBR = (dateString: string): string => {
    if (!dateString) return '';
    const [year, month, day] = dateString.split('-');
    return `${day}/${month}/${year}`;
}

export const formatDateToDB = (date: string): string => {
    return dayjs(date).isValid() ? dayjs(date).format('YYYY-MM-DD') : '';
};