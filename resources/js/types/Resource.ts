export interface Resource {
    id: number;
    nome: string;
    precoEUR: number;
    preco$: number;
    precoR$: number;
    created_at?: string | null;
    updated_at?: string | null;
}