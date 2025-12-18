import { Injectable } from '@angular/core';
import {ApiService} from '../API/api.service';
import {Observable} from 'rxjs';
import {Prato} from '../pratos/pratos.service';

export interface Pedido {
  id: number;
  mesa_id: number;
  usuario_id: number;
  status: 'pendente' | 'em_preparo' | 'pronto' | 'entregue' | 'cancelado' | 'finalizado';
  tipo_pedido: 'local' | 'delivery' | 'retirada';
  forma_pagamento: 'dinheiro' | 'cartao_credito' | 'cartao_debito' | 'pix' | 'outros';
  valor_total: number;
  observacoes?: string;
  data_pedido: string;
}

export interface PedidoRequest {
  mesa_id?: number;
  status?: string;
  tipo_pedido: string;
  forma_pagamento?: string;
  valor_total?: number;
  observacoes?: string;
  data_pedido?: string;
}

export interface PedidoItem {
  id: number;
  pedido_id: number;
  prato_id: number;
  quantidade: number;
  preco_unitario: number;
  observacoes?: string;
  status_item: 'pendente' | 'em_preparo' | 'pronto' | 'entregue' | 'cancelado';
}

export interface PedidoItemRequest {
  prato_id: number;
  quantidade: number;
  preco_unitario?: number;
  observacoes?: string;
  status_item?: string;
}

export interface Mesa {
  id: number;
  numero: number;
  capacidade: number;
  descricao?: string;
  status: 'livre' | 'ocupada' | 'reservada' | 'manutencao';
  ativo: number;
}

@Injectable({
  providedIn: 'root',
})
export class PedidosService {
  constructor(private api: ApiService) {}

  // Pedidos
  getPedidos(): Observable<Pedido[]> {
    return this.api.get<Pedido[]>('pedido');
  }

  getPedidoById(id: number): Observable<Pedido> {
    return this.api.get<Pedido>(`pedido/${id}`);
  }

  createPedido(pedido: PedidoRequest): Observable<Pedido> {
    return this.api.post<Pedido>('pedido', pedido);
  }

  updatePedido(id: number, pedido: PedidoRequest): Observable<Pedido> {
    return this.api.put<Pedido>(`pedido/${id}`, pedido);
  }

  deletePedido(id: number): Observable<any> {
    return this.api.delete<any>(`pedido/${id}`);
  }

  // Itens do Pedido
  getPedidoItems(pedidoId: number): Observable<PedidoItem[]> {
    return this.api.get<PedidoItem[]>(`pedido/${pedidoId}/itens`);
  }

  addPedidoItem(pedidoId: number, item: PedidoItemRequest): Observable<PedidoItem> {
    return this.api.post<PedidoItem>(`pedido/${pedidoId}/itens`, item);
  }

  updatePedidoItem(pedidoId: number, itemId: number, item: PedidoItemRequest): Observable<PedidoItem> {
    return this.api.put<PedidoItem>(`pedido/${pedidoId}/itens/${itemId}`, item);
  }

  deletePedidoItem(pedidoId: number, itemId: number): Observable<any> {
    return this.api.delete<any>(`pedido/${pedidoId}/itens/${itemId}`);
  }
}
