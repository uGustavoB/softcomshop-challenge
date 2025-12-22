import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {MatIconModule} from '@angular/material/icon';
import {
  Pedido,
  PedidoItem,
  PedidoItemRequest,
  PedidoRequest,
  PedidosService
} from '../../../services/pedidos/pedidos.service';
import {Mesa, MesasService} from '../../../services/mesas/mesas.service';
import {Prato, PratosService} from '../../../services/pratos/pratos.service';
import {ToastrService} from 'ngx-toastr';

@Component({
  selector: 'app-pedido-editar',
  imports: [
    CommonModule,
    FormsModule,
    MatIconModule
  ],
  templateUrl: './pedido-editar.html',
  styleUrl: './pedido-editar.css',
})
export class PedidoEditar implements OnInit{
  @Input() pedidoId: number | null = null;
  @Output() close = new EventEmitter<void>();

  // Dados
  pedido: Pedido | null = null;
  pedidoItems: PedidoItem[] = [];
  mesas: Mesa[] = [];
  pratos: Prato[] = [];

  // Form fields
  mesaId: string = '';
  tipoPedido: 'local' | 'delivery' | 'retirada' = 'local';
  formaPagamento: 'dinheiro' | 'cartao_credito' | 'cartao_debito' | 'pix' | 'outros' = 'dinheiro';
  status: 'pendente' | 'em_preparo' | 'pronto' | 'entregue' | 'cancelado' | 'finalizado' = 'pendente';
  observacoes: string = '';

  // Novos itens para adicionar
  novosItens: Array<{
    pratoId: number;
    quantidade: number;
    preco: number;
    pratoNome?: string;
    pratosFiltrados?: Prato[];
  }> = [];

  // Estados
  isLoading = false;
  isLoadingData = false;
  showAddItem = false;

  // Configuração de status
  statusOptions = [
    { value: 'pendente', label: 'Pendente' },
    { value: 'em_preparo', label: 'Em Preparo' },
    { value: 'pronto', label: 'Pronto' },
    { value: 'entregue', label: 'Entregue' },
    { value: 'cancelado', label: 'Cancelado' },
    { value: 'finalizado', label: 'Finalizado' }
  ];

  constructor(
    private pedidosService: PedidosService,
    private mesasService: MesasService,
    private pratosService: PratosService,
    private toastr: ToastrService
  ) {}

  ngOnInit(): void {
    if (this.pedidoId) {
      this.loadData();
    }
  }

  loadData(): void {
    this.isLoadingData = true;

    // Carrega dados do pedido
    this.pedidosService.getPedidoById(this.pedidoId!).subscribe({
      next: (pedido) => {
        this.pedido = pedido;
        this.mesaId = pedido.mesa_id.toString();
        this.tipoPedido = pedido.tipo_pedido;
        this.formaPagamento = pedido.forma_pagamento;
        this.status = pedido.status;
        this.observacoes = pedido.observacoes || '';

        // Carrega itens do pedido
        this.pedidosService.getPedidoItems(this.pedidoId!).subscribe({
          next: (items) => {
            this.pedidoItems = items;
          },
          error: (error) => {
            console.error('Erro ao carregar itens do pedido:', error);
            this.toastr.error('Erro ao carregar itens do pedido', 'Erro');
          }
        });
      },
      error: (error) => {
        console.error('Erro ao carregar pedido:', error);
        this.toastr.error('Erro ao carregar pedido', 'Erro');
      }
    });

    // Carrega mesas
    this.mesasService.getMesas().subscribe({
      next: (mesas) => {
        this.mesas = mesas;
      },
      error: (error) => {
        console.error('Erro ao carregar mesas:', error);
        this.toastr.error('Erro ao carregar mesas', 'Erro');
      }
    });

    // Carrega pratos
    this.pratosService.getPratos().subscribe({
      next: (pratos) => {
        this.pratos = pratos.filter(p => p.ativo);
        this.isLoadingData = false;
      },
      error: (error) => {
        console.error('Erro ao carregar pratos:', error);
        this.toastr.error('Erro ao carregar pratos', 'Erro');
        this.isLoadingData = false;
      }
    });
  }

  // Métodos para novos itens
  adicionarNovoItem(): void {
    this.novosItens.push({
      pratoId: 0,
      quantidade: 1,
      preco: 0,
      pratosFiltrados: []
    });
  }

  removerNovoItem(index: number): void {
    this.novosItens.splice(index, 1);
  }

  atualizarNovoItem(index: number, campo: string, valor: any): void {
    if (campo === 'pratoId' && typeof valor === 'object') {
      // Se valor for um objeto de prato
      const prato = valor;
      this.novosItens[index] = {
        ...this.novosItens[index],
        pratoId: prato.id,
        preco: prato.preco,
        pratoNome: prato.nome
      };
    } else if (campo === 'quantidade') {
      this.novosItens[index].quantidade = Number(valor);
    } else if (campo === 'preco') {
      this.novosItens[index].preco = Number(valor);
    }
  }

  // Busca de pratos com normalização de acentos
  normalizarTexto(texto: string): string {
    if (!texto) return '';
    return texto
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .toLowerCase()
      .trim();
  }

  searchPratos(search: string, index: number): void {
    if (!search) {
      this.novosItens[index].pratosFiltrados = [];
      return;
    }

    const filterValue = this.normalizarTexto(search);

    this.novosItens[index].pratosFiltrados = this.pratos.filter(prato => {
      const nomeNormalizado = this.normalizarTexto(prato.nome);
      const descricaoNormalizada = prato.descricao ?
        this.normalizarTexto(prato.descricao) : '';

      return nomeNormalizado.includes(filterValue) ||
        descricaoNormalizada.includes(filterValue);
    });
  }

  selectPrato(prato: Prato, index: number): void {
    this.atualizarNovoItem(index, 'pratoId', prato);
    this.novosItens[index].pratosFiltrados = [];
  }

  // Métodos para itens existentes
  removerItem(itemId: number): void {
    if (!confirm('Tem certeza que deseja remover este item do pedido?')) {
      return;
    }

    this.pedidosService.deletePedidoItem(this.pedidoId!, itemId).subscribe({
      next: () => {
        this.pedidoItems = this.pedidoItems.filter(item => item.id !== itemId);
        this.toastr.success('Item removido com sucesso!', 'Sucesso');
      },
      error: (error) => {
        console.error('Erro ao remover item:', error);
        this.toastr.error('Erro ao remover item', 'Erro');
      }
    });
  }

  // Salvar alterações
  salvarAlteracoes(): void {
    if (!this.pedido) return;

    this.isLoading = true;

    const pedidoData: PedidoRequest = {
      mesa_id: Number(this.mesaId),
      tipo_pedido: this.tipoPedido,
      forma_pagamento: this.formaPagamento,
      status: this.status,
      observacoes: this.observacoes || undefined
    };

    this.pedidosService.updatePedido(this.pedido.id, pedidoData).subscribe({
      next: (pedidoAtualizado) => {
        // Adiciona novos itens, se houver
        if (this.novosItens.length > 0) {
          this.adicionarNovosItens(pedidoAtualizado.id);
        } else {
          this.toastr.success('Pedido atualizado com sucesso!', 'Sucesso');
          this.closeModal();
          this.isLoading = false;
        }
      },
      error: (error) => {
        console.error('Erro ao atualizar pedido:', error);
        this.toastr.error('Erro ao atualizar pedido', 'Erro');
        this.isLoading = false;
      }
    });
  }

  private adicionarNovosItens(pedidoId: number): void {
    let itemsProcessados = 0;
    const totalItems = this.novosItens.length;

    if (totalItems === 0) {
      this.toastr.success('Pedido atualizado com sucesso!', 'Sucesso');
      this.closeModal();
      this.isLoading = false;
      return;
    }

    this.novosItens.forEach(item => {
      if (item.pratoId > 0) {
        const itemData: PedidoItemRequest = {
          prato_id: item.pratoId,
          quantidade: item.quantidade,
          preco_unitario: item.preco
        };

        this.pedidosService.addPedidoItem(pedidoId, itemData).subscribe({
          next: () => {
            itemsProcessados++;
            if (itemsProcessados === totalItems) {
              this.toastr.success('Pedido atualizado com sucesso!', 'Sucesso');
              this.closeModal();
              this.isLoading = false;
            }
          },
          error: (error) => {
            console.error('Erro ao adicionar item:', error);
            this.toastr.error('Erro ao adicionar alguns itens', 'Erro');
            this.isLoading = false;
          }
        });
      }
    });
  }

  // Métodos auxiliares
  getPratoNome(pratoId: number): string {
    const prato = this.pratos.find(p => p.id === pratoId);
    return prato ? prato.nome : `Item #${pratoId}`;
  }

  formatCurrency(value: any): string {
    const num = typeof value === 'string' ? parseFloat(value) : value;
    return isNaN(num) ? '0.00' : num.toFixed(2);
  }

  calcularValorTotal(): number {
    const valorItensExistentes = this.pedidoItems.reduce((total, item) => {
      return total + (item.preco_unitario * item.quantidade);
    }, 0);

    const valorNovosItens = this.novosItens.reduce((total, item) => {
      return total + (item.preco * item.quantidade);
    }, 0);

    return valorItensExistentes + valorNovosItens;
  }

  closeModal(): void {
    this.close.emit();
  }

  getTotalItens(): number {
    return this.pedidoItems.length + this.novosItens.length;
  }

  getTotalQuantidade(): number {
    const totalExistentes = this.pedidoItems.reduce((acc, item) => acc + item.quantidade, 0);
    const totalNovos = this.novosItens.reduce((acc, item) => acc + item.quantidade, 0);
    return totalExistentes + totalNovos;
  }

  getValorTotalFormatado(): string {
    return this.formatCurrency(this.calcularValorTotal());
  }
}
