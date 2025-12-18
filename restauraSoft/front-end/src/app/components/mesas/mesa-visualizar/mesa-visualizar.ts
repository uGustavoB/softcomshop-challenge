import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {MatIconModule} from '@angular/material/icon';
import {MesaSalvar} from '../mesa-salvar/mesa-salvar';
import {Mesa} from '../../../services/mesas/mesas.service';
import {Pedido, PedidoItem, PedidosService} from '../../../services/pedidos/pedidos.service';
import {ToastrService} from 'ngx-toastr';
import {PratosService} from '../../../services/pratos/pratos.service';
import {Observable, of} from 'rxjs';
import {catchError, map, tap} from 'rxjs/operators';

interface StatusConfig{
  label: string;
  color: string;
  bgColor: string;
}

@Component({
  selector: 'app-mesa-visualizar',
  imports: [
    CommonModule,
    FormsModule,
    MatIconModule,
    MesaSalvar
  ],
  templateUrl: './mesa-visualizar.html',
  styleUrl: './mesa-visualizar.css',
})
export class MesaVisualizar implements OnInit{
  @Input() mesa: Mesa | null = null;
  @Output() close = new EventEmitter<void>();
  @Output() openEditModal = new EventEmitter<void>();

  // Estados
  showNewOrder = false;
  isLoading = false;
  isEditModalOpen = false;

  // Dados
  pedidos: Pedido[] = [];
  pedidoItems: { [pedidoId: number]: PedidoItem[] } = {};
  private pratosCache = new Map<number, string>();

  // Formulário de novo pedido
  tipoPedido: 'local' | 'delivery' | 'retirada' = 'local';
  formaPagamento: 'dinheiro' | 'cartao_credito' | 'cartao_debito' | 'pix' | 'outros' = 'dinheiro';
  observacoes = '';

  // Itens do pedido
  itens: Array<{
    pratoId: number;
    quantidade: number;
    preco: number;
    pratoNome?: string;
  }> = [];

  // Configuração de status dos pedidos
  statusConfig: Record<string, StatusConfig> = {
    pendente: { label: "Pendente", color: "text-amber-400", bgColor: "bg-amber-500/10" },
    em_preparo: { label: "Em Preparo", color: "text-blue-400", bgColor: "bg-blue-500/10" },
    pronto: { label: "Pronto", color: "text-purple-400", bgColor: "bg-purple-500/10" },
    entregue: { label: "Entregue", color: "text-green-400", bgColor: "bg-green-500/10" },
    cancelado: { label: "Cancelado", color: "text-red-400", bgColor: "bg-red-500/10" },
    finalizado: { label: "Finalizado", color: "text-neutral-400", bgColor: "bg-neutral-500/10" },
  };

  constructor(
    private pedidosService: PedidosService,
    private pratosService: PratosService,
    private toastr: ToastrService
  ) {}

  ngOnInit(): void {
    if (this.mesa) {
      this.loadPedidos();
    }
  }

  loadPedidos(): void {
    if (!this.mesa) return;

    this.isLoading = true;
    this.pedidosService.getPedidos().subscribe({
      next: (pedidos) => {
        // Filtra pedidos da mesa atual
        this.pedidos = pedidos.filter(p => p.mesa_id === this.mesa!.id);

        // Carrega itens de cada pedido
        this.pedidos.forEach(pedido => {
          this.loadPedidoItems(pedido.id);
        });

        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao carregar pedidos:', error);
        this.toastr.error('Erro ao carregar pedidos', 'Erro');
        this.isLoading = false;
      }
    });
  }

  loadPedidoItems(pedidoId: number): void {
    this.pedidosService.getPedidoItems(pedidoId).subscribe({
      next: (items) => {
        this.pedidoItems[pedidoId] = items;
      },
      error: (error) => {
        console.error(`Erro ao carregar itens do pedido ${pedidoId}:`, error);
      }
    });
  }

  // Métodos para novo pedido
  adicionarItem(): void {
    this.itens.push({ pratoId: 0, quantidade: 1, preco: 0 });
  }

  removerItem(index: number): void {
    this.itens.splice(index, 1);
  }

  atualizarItem(index: number, campo: string, valor: any): void {
    if (campo === 'pratoId') {
      // buscar nome e valor do prato
      this.itens[index].pratoId = Number(valor);
    } else if (campo === 'quantidade') {
      this.itens[index].quantidade = Number(valor);
    } else if (campo === 'preco') {
      this.itens[index].preco = Number(valor);
    }
  }

  criarNovoPedido(): void {
    if (!this.mesa || this.itens.length === 0) {
      this.toastr.warning('Adicione itens ao pedido', 'Atenção');
      return;
    }

    this.isLoading = true;

    const pedidoData = {
      mesa_id: this.mesa.id,
      tipo_pedido: this.tipoPedido,
      forma_pagamento: this.formaPagamento,
      observacoes: this.observacoes || undefined,
      valor_total: this.calcularValorTotal()
    };

    this.pedidosService.createPedido(pedidoData).subscribe({
      next: (pedido) => {
        // Colocar adição dos itens
        this.toastr.success('Pedido criado com sucesso!', 'Sucesso');
        this.cancelarNovoPedido();
        this.loadPedidos();
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao criar pedido:', error);
        this.toastr.error('Erro ao criar pedido', 'Erro');
        this.isLoading = false;
      }
    });
  }

  calcularValorTotal(): number {
    return this.itens.reduce((total, item) => total + (item.preco * item.quantidade), 0);
  }

  getValorTotal(): string {
    return this.calcularValorTotal().toFixed(2);
  }

  cancelarNovoPedido(): void {
    this.showNewOrder = false;
    this.itens = [];
    this.observacoes = '';
    this.tipoPedido = 'local';
    this.formaPagamento = 'dinheiro';
  }

  getStatusInfo(status: string): StatusConfig {
    return this.statusConfig[status] || this.statusConfig["pendente"];
  }

  closeModal(): void {
    this.close.emit();
  }

  handleEditMesa(): void {
    this.isEditModalOpen = true;
  }

  handleCloseEditModal(): void {
    this.isEditModalOpen = false;
  }

  getTotalQuantidade(): number {
    return this.itens.reduce((acc, item) => acc + item.quantidade, 0);
  }

  getValorTotalPedido(pedido: Pedido): string {
    // Converte para número se for string
    const valor = typeof pedido.valor_total === 'string'
      ? parseFloat(pedido.valor_total)
      : pedido.valor_total;

    if (isNaN(valor)) return '0.00';

    return valor.toFixed(2);
  }

  formatNumber(value: any, decimals: number = 2): string {
    // Converte para número se for string
    const num = typeof value === 'string'
      ? parseFloat(value)
      : Number(value);

    if (isNaN(num) || num === null || num === undefined) {
      return '0'.padEnd(decimals + 1, '.00');
    }

    return num.toFixed(decimals);
  }

  getNomePrato(pratoId: number): Observable<string> {
    // Se já temos no cache, retorna
    if (this.pratosCache.has(pratoId)) {
      return of(this.pratosCache.get(pratoId)!);
    }

    // Se não, busca e cacheia
    return this.pratosService.getPratoById(pratoId).pipe(
      map(prato => prato.nome),
      tap(nome => this.pratosCache.set(pratoId, nome)),
      catchError(() => of(`Item #${pratoId}`))
    );
  }
}
