import { AxiosResponse } from 'axios';


export const showResponse = (res: AxiosResponse, toastAdd: Function) => {
    if (res.status === 200 || res.status === 201) {
        toastAdd({ severity: 'success', summary: 'Sucesso', detail: res.data.message, life: 3000 });
    } else {
        const errorMessages = Object.values(res.data.errors)
            .flat() // Se for um array de mensagens, o flat junta todas as mensagens
            .join(', '); // Concatena as mensagens com uma vírgula ou outro separador

        toastAdd({
            severity: 'error',
            summary: 'Erro',
            detail: errorMessages,
            life: 3000
        });
    }
}