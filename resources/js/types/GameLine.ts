export interface Fornecedor {
    id: number;
    perfilOrigem: string;
    quantidade_reclamacoes: number;
    created_at: string | null;
    updated_at: string | null;
}

export interface TipoReclamacao {
    id: number;
    name: string;
    created_at: string | null;
    updated_at: string | null;
}

export interface TipoFormato {
    id: number;
    name: string;
    created_at: string | null;
    updated_at: string | null;
}

export interface Leilao {
    id: number;
    name: string;
    created_at: string | null;
    updated_at: string | null;
}

export interface Plataforma {
    id: number;
    name: string;
    created_at: string | null;
    updated_at: string | null;
}

export interface GameLine {
    id: number;
    steamId: string;
    chaveRecebida: string;
    nameJogo: string;
    precoJogo: number;
    notaMetacritic: number;
    isSteam: boolean;
    randomClassificationG2A: string;
    randomClassificationKinguin: string;
    observacao: string;
    precoCliente: number;
    precoVenda: number;
    incomeReal: number;
    incomeSimulado: number;
    chaveEntregue: string;
    valorPagoTotal: string;
    valorPagoIndividual: number;
    vendido: boolean;
    leiloes: number;
    quantidade: number;
    devolucoes: boolean;
    lucroRS: number;
    lucroPercentual: number;
    dataAdquirida: string;
    dataVenda: string;
    dataVendida: string;
    perfilOrigem: string;
    email: string;
    
    fornecedor: Fornecedor;
    tipo_reclamacao: TipoReclamacao;
    tipo_formato: TipoFormato;
    leilao_g2a: Leilao;
    leilao_gamivo: Leilao;
    leilao_kinguin: Leilao;
    plataforma: Plataforma;
}